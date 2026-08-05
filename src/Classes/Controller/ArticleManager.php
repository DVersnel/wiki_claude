<?php

namespace MDJ\Controller;
use MDJ\Model\Objects\Article;
use MDJ\Model\Repositories\ArticleRepository;
use MDJ\Model\Repositories\UserRepository;
use MDJ\Model\Repositories\TagRepository;
use MDJ\Model\Repositories\RatingRepository;

class ArticleManager
{
    // Build content_item_info arrays for the N most recently edited articles, ready for the ContentFactory
    // Input: $number; Amount of articles to include
    // Output: Array of 'article' content_item_info arrays (linked title, description, author)
    public function getRecentArticlesForHome(int $number): array
    {
        $article_repo = new ArticleRepository();
        $user_repo = new UserRepository();
        $tag_repo = new TagRepository();

        $articles = $article_repo->getMostRecentArticles($number);

        return array_map(fn($article) => $this->buildArticleSummary($article, $user_repo, $tag_repo), $articles);
    }

    // Build the content_item_info array for a single article's full page, resolving its author, tags and rating
    // Input: $id; Article ID
    // Output: content_item_info array consumable by ContentFactory's 'article' case
    // Throws: \Exception if no article exists with the given ID
    public function getArticleContentItem(int $id): array
    {
        $article = $this->fetchArticleById($id);
        $user_repo = new UserRepository();
        $tag_repo = new TagRepository();
        $rating_repo = new RatingRepository();

        $author = $user_repo->getUsernameById($article->user_id) ?: 'Unknown';
        $tags = $tag_repo->getTagsByArticleId($article->id) ?: [];
        $tag_names = array_map(fn($tag) => $tag->getTag(), $tags);

        return [
            'type' => 'article',
            'content' => [
                ['type' => 'article_title', 'title' => $article->name],
                ['type' => 'paragraph', 'text' => $article->text],
            ],
            'info_items' => [
                ['type' => 'textbox', 'author' => $author, 'tags' => $tag_names],
                [
                    'type' => 'rating',
                    'article_id' => $article->id,
                    'rating' => $rating_repo->getAverageRatingByArticleId($article->id),
                    'count' => $rating_repo->getVoteCount($article->id),
                ],
                ['type' => 'pdf_download', 'article_id' => $article->id],
            ],
        ];
    }

    // Build and stream a PDF of a single article directly to the browser as a download
    // Input: $id; Article ID
    // Throws: \Exception if no article exists with the given ID
    public function downloadArticlePdf(int $id): void
    {
        require_once __DIR__ . '/../../vendor/fpdf/fpdf.php';

        $article = $this->fetchArticleById($id);
        $user_repo = new UserRepository();
        $tag_repo = new TagRepository();

        $author = $user_repo->getUsernameById($article->user_id) ?: 'Unknown';
        $tags = $tag_repo->getTagsByArticleId($article->id) ?: [];
        $tag_names = array_map(fn($tag) => $tag->getTag(), $tags);

        $pdf = new \FPDF();
        $pdf->AddPage();

        $pdf->SetFont('Helvetica', 'B', 18);
        $pdf->MultiCell(0, 10, $article->name);
        $pdf->Ln(2);

        $pdf->SetFont('Helvetica', 'I', 11);
        $pdf->Cell(0, 8, 'By ' . $author, 0, 1);
        if ($tag_names)
        {
            $pdf->Cell(0, 8, 'Tags: ' . implode(', ', $tag_names), 0, 1);
        }
        $pdf->Ln(4);

        $pdf->SetFont('Helvetica', '', 12);
        $pdf->MultiCell(0, 7, $article->text);

        $pdf->Output('D', $this->slugify($article->name) . '.pdf');
    }

    // Build the search_form content_item_info array, listing every author and tag in the db as a checkbox
    // Output: Array containing a single 'search_form' content_item_info array
    public function getSearchFormContent(): array
    {
        $user_repo = new UserRepository();
        $tag_repo = new TagRepository();

        $authors = $user_repo->getAuthorNames() ?: [];
        $tags = $tag_repo->getTags() ?: [];

        $author_fields = [];
        foreach ($authors as $id => $name)
        {
            $author_fields[] = ['type' => 'checkbox', 'name' => $name, 'label' => 'author_' . $id];
        }

        $tag_fields = [];
        foreach ($tags as $tag)
        {
            $tag_fields[] = ['type' => 'checkbox', 'name' => $tag->getTag(), 'label' => 'tag_' . $tag->getId()];
        }

        return [['type' => 'search_form', 'authors' => $author_fields, 'tags' => $tag_fields]];
    }

    // Parse the checked author/tag checkboxes from a submitted search (keyed 'author_{id}'/'tag_{id}', see getSearchFormContent) and find articles matching both
    // Input: $checked_keys; array of checkbox field names present in the submitted search (array_keys($_POST))
    // Output: Array of 'article' content_item_info arrays for the matching articles
    public function searchArticles(array $checked_keys): array
    {
        $user_repo = new UserRepository();
        $tag_repo = new TagRepository();
        $article_repo = new ArticleRepository();

        $author_ids = [];
        $tag_ids = [];
        foreach ($checked_keys as $key)
        {
            if (preg_match('/^author_(\d+)$/', $key, $matches))
            {
                $author_ids[] = (int)$matches[1];
            }
            elseif (preg_match('/^tag_(\d+)$/', $key, $matches))
            {
                $tag_ids[] = (int)$matches[1];
            }
        }

        // Treat an unchecked group as "match any" rather than "match none"
        if (empty($author_ids))
        {
            $author_ids = array_keys($user_repo->getAuthorNames() ?: []);
        }
        if (empty($tag_ids))
        {
            $tag_ids = array_map(fn($tag) => $tag->getId(), $tag_repo->getTags() ?: []);
        }

        if (empty($author_ids) || empty($tag_ids))
        {
            return [];
        }

        $articles = $article_repo->getArticlesByUserIdAndTagId($author_ids, $tag_ids);

        return array_map(fn($article) => $this->buildArticleSummary($article, $user_repo, $tag_repo), $articles);
    }

    // Fetch a single Article by ID, translating a not-found row into a catchable \Exception
    // Input: $id; Article ID
    // Output: Article object
    // Throws: \Exception if no article exists with the given ID
    private function fetchArticleById(int $id): Article
    {
        $article_repo = new ArticleRepository();

        try
        {
            return $article_repo->getArticleById($id);
        }
        catch (\Throwable $e)
        {
            throw new \Exception('Error: Article not found');
        }
    }

    // Turn an article title into a safe PDF download filename
    // Input: $text; Article title
    // Output: Lowercase, hyphenated slug
    private function slugify(string $text): string
    {
        $slug = trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $text), '-');
        return $slug !== '' ? strtolower($slug) : 'article';
    }

    // Build a single linked 'article' content_item_info array for an Article, resolving its author name and tags
    // Input: $article; Article object $user_repo; UserRepository used to resolve the author's name $tag_repo; TagRepository used to resolve the article's tags
    // Output: content_item_info array consumable by ContentFactory's 'article' case
    private function buildArticleSummary(Article $article, UserRepository $user_repo, TagRepository $tag_repo): array
    {
        $author = $user_repo->getUsernameById($article->user_id) ?: 'Unknown';
        $tags = $tag_repo->getTagsByArticleId($article->id) ?: [];
        $tag_names = array_map(fn($tag) => $tag->getTag(), $tags);

        return [
            'type' => 'article',
            'content' => [
                ['type' => 'article_title_link', 'title' => $article->name, 'id' => $article->id],
                ['type' => 'paragraph', 'text' => $article->description],
            ],
            'info_items' => [
                ['type' => 'textbox', 'author' => $author, 'tags' => $tag_names],
            ],
        ];
    }
}
