<?php

namespace MDJ\Controller;
use MDJ\Model\Objects\Article;
use MDJ\Model\Objects\Image;
use MDJ\Model\Repositories\ArticleRepository;
use MDJ\Model\Repositories\UserRepository;
use MDJ\Model\Repositories\TagRepository;
use MDJ\Model\Repositories\RatingRepository;
use MDJ\Model\Repositories\ImageRepository;

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

        $image_repo = new ImageRepository();
        $image = $image_repo->getImageByArticleId($article->id);

        return [
            'type' => 'article',
            'content' => [
                ['type' => 'article_title', 'title' => $article->name],
                ['type' => 'paragraph', 'text' => $article->text],
                ['type' => 'code', 'text' => $article->code],
            ],
            'info_items' => [
                ...($image ? [['type' => 'image', 'image' => $image->path]] : []),
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

    // Build the 'article_form' content_item_info array for the article edit page, pre-filled with the
    // existing article's data. An $id of 0 builds an empty form for creating a new article.
    // Input: $id; Article ID (0 for a new article) $user_id; ID of the user requesting the form
    // Output: Array containing a single 'article_form' content_item_info array
    // Throws: \Exception if the article doesn't exist, or isn't owned by $user_id
    public function getEditFormContent(int $id, int $user_id): array
    {
        $tag_repo = new TagRepository();
        $all_tags = $tag_repo->getTags() ?: [];

        if ($id === 0)
        {
            return $this->buildArticleFormContent(0, '', '', '', '', $all_tags, []);
        }

        $article = $this->fetchArticleById($id);
        if ($article->user_id !== $user_id)
        {
            throw new \Exception('Error: You do not have permission to edit this article');
        }

        $image_repo = new ImageRepository();
        $image = $image_repo->getImageByArticleId($article->id);

        $selected_tags = $tag_repo->getTagsByArticleId($article->id) ?: [];
        $selected_tag_ids = array_map(fn($tag) => $tag->getId(), $selected_tags);

        return $this->buildArticleFormContent($article->id, $article->name, $article->text, $article->code, $image ? $image->path : '', $all_tags, $selected_tag_ids);
    }

    // Create or update an article owned by the given user, optionally attaching an uploaded image
    // Input: $id; Article ID (0 for a new article) $user_id; Owning user's ID $title/$text/$code; Article field values
    //        $tag_ids; IDs of the tags selected in the edit form $uploaded_file; $_FILES['image'] entry, or null if no file was submitted
    // Output: array with 'success' bool and 'message' string
    // Throws: \Exception if editing an article that isn't owned by $user_id
    public function saveArticle(int $id, int $user_id, string $title, string $text, string $code, array $tag_ids, ?array $uploaded_file): array
    {
        $title = trim($title);
        if ($title === '')
        {
            return ['success' => false, 'message' => 'Error: Title is required'];
        }

        $article_repo = new ArticleRepository();
        $tag_repo = new TagRepository();

        // Only keep tag IDs that actually exist, in case of a stale or tampered submission
        $valid_tag_ids = array_map(fn($tag) => $tag->getId(), $tag_repo->getTags() ?: []);
        $tag_ids = array_values(array_intersect($tag_ids, $valid_tag_ids));

        $article = new Article();
        $article->id = $id;
        $article->name = $title;
        $article->description = mb_strimwidth($text, 0, 1023, '...');
        $article->text = $text;
        $article->code = $code;
        $article->user_id = $user_id;

        if ($id === 0)
        {
            $article_id = $article_repo->createArticle($article, $tag_ids);
            if ($article_id === false)
            {
                return ['success' => false, 'message' => 'Error: Could not save article'];
            }
        }
        else
        {
            $existing = $this->fetchArticleById($id);
            if ($existing->user_id !== $user_id)
            {
                throw new \Exception('Error: You do not have permission to edit this article');
            }

            $article_repo->updateArticle($article, $tag_ids);
            $article_id = $id;
        }

        if ($uploaded_file && ($uploaded_file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE)
        {
            $image_error = $this->storeArticleImage($article_id, $uploaded_file);
            if ($image_error !== null)
            {
                return ['success' => true, 'message' => 'Article saved, but image upload failed: ' . $image_error];
            }
        }

        return ['success' => true, 'message' => $id === 0 ? 'Article created' : 'Article updated'];
    }

    // Delete an article owned by the given user
    // Input: $id; Article ID $user_id; ID of the user requesting the deletion
    // Throws: \Exception if the article doesn't exist, or isn't owned by $user_id
    public function deleteArticleForUser(int $id, int $user_id): void
    {
        $article = $this->fetchArticleById($id);
        if ($article->user_id !== $user_id)
        {
            throw new \Exception('Error: You do not have permission to delete this article');
        }

        (new ArticleRepository())->deleteArticle($id);
    }

    // Build content_item_info arrays for a user's own profile page: a 'new article' button followed by
    // their articles, each linked and carrying edit/delete controls
    // Input: $user_id; User ID
    // Output: Array of content_item_info arrays
    public function getProfileContent(int $user_id): array
    {
        $article_repo = new ArticleRepository();
        $user_repo = new UserRepository();
        $tag_repo = new TagRepository();

        $articles = $article_repo->getArticlesByUserId($user_id);

        $content = [['type' => 'new_article_button']];
        foreach ($articles as $article)
        {
            $content[] = $this->buildOwnerArticleSummary($article, $user_repo, $tag_repo);
        }

        return $content;
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

    // Build a single 'article' content_item_info array for an owner's profile listing: linked title/description
    // plus author/tags and edit/delete controls in the sidebar
    // Input: $article; Article object $user_repo; UserRepository used to resolve the author's name $tag_repo; TagRepository used to resolve the article's tags
    // Output: content_item_info array consumable by ContentFactory's 'article' case
    private function buildOwnerArticleSummary(Article $article, UserRepository $user_repo, TagRepository $tag_repo): array
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
                ['type' => 'edit_button', 'article_id' => $article->id],
                ['type' => 'delete_button', 'article_id' => $article->id],
            ],
        ];
    }

    // Build the 'article_form' content_item_info array shared by getEditFormContent's new/edit paths
    // Input: $id; Article ID (0 for a new article) $name/$text/$code; Field values to pre-fill $image_path; Existing image path, or '' if none
    //        $all_tags; Every Tag in the database, for the tags dropdown $selected_tag_ids; IDs of the tags already on this article
    // Output: Array containing a single 'article_form' content_item_info array
    private function buildArticleFormContent(int $id, string $name, string $text, string $code, string $image_path, array $all_tags, array $selected_tag_ids): array
    {
        $tag_options = [];
        foreach ($all_tags as $tag)
        {
            $tag_options[$tag->getId()] = $tag->getTag();
        }

        return [
            [
                'type' => 'article_form',
                'article_id' => $id,
                'image' => $image_path,
                'form_fields' => [
                    ['type' => 'text', 'name' => 'Title:', 'label' => 'title', 'placeholder' => 'Enter a title', 'value' => $name],
                    ['type' => 'textarea', 'name' => 'Article text:', 'label' => 'text', 'placeholder' => 'Write your article text here', 'value' => $text],
                    ['type' => 'textarea', 'name' => 'Code:', 'label' => 'code', 'placeholder' => 'Write your code example here (optional)', 'value' => $code],
                    ['type' => 'tags_select', 'name' => 'Tags:', 'label' => 'tags', 'options' => $tag_options, 'selected' => $selected_tag_ids],
                    ['type' => 'file', 'name' => 'Image:', 'label' => 'image'],
                ],
            ],
        ];
    }

    // Validate and store an uploaded article image, replacing any image the article already has
    // Input: $article_id; Article ID $uploaded_file; $_FILES['image'] entry
    // Output: null on success, or an error message string on failure
    private function storeArticleImage(int $article_id, array $uploaded_file): ?string
    {
        if (($uploaded_file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK)
        {
            return 'Error: Upload failed';
        }

        $allowed = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif', 'webp' => 'image/webp'];
        $ext = strtolower(pathinfo($uploaded_file['name'], PATHINFO_EXTENSION));
        $image_info = @getimagesize($uploaded_file['tmp_name']);

        if (!isset($allowed[$ext]) || $image_info === false || ($image_info['mime'] ?? '') !== $allowed[$ext])
        {
            return 'Error: File must be a valid image (jpg, png, gif, or webp)';
        }

        $filename = 'article_' . $article_id . '_' . uniqid() . '.' . $ext;
        $destination = __DIR__ . '/../../Images/' . $filename;

        if (!move_uploaded_file($uploaded_file['tmp_name'], $destination))
        {
            return 'Error: Could not save uploaded image';
        }

        $image_repo = new ImageRepository();
        $image_repo->deleteImagesByArticleId($article_id);

        $image = new Image();
        $image->description = 'Image for article ' . $article_id;
        $image->path = 'Images/' . $filename;
        $image->article_id = $article_id;
        $image->display_order = 1;

        return $image_repo->createImage($image) !== false ? null : 'Error: Could not save image record';
    }
}
