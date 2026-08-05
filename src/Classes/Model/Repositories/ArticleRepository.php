<?php
namespace MDJ\Model\Repositories;

use MDJ\Interfaces\iArticleRepository;
use MDJ\Model\Objects\Article;
use ManKind\tools\pdo\QueryParams;

class ArticleRepository extends BaseRepository implements iArticleRepository
{

    // Get a singular Article object by its ID
    // Input: $id; Article ID
    // Output: Article object
    public function getArticleById(int $id): Article
    {
        $params = (new QueryParams())->add('id', $id, true);

        $article_data = $this->db->selectOne(
            "
            SELECT
                a.id,
                a.name,
                a.description,
                a.text,
                a.code,
                a.user_id,
                a.last_edit
            FROM articles a
            WHERE a.id = :id
            ",
            $params
        );

        return $this->mapToArticle($article_data);
    }

    // Get an array of Article objects that belongs to a specific user
    // Input: $user_id; User's id
    // Output: Array of Article objects
    public function getArticlesByUserId(int $user_id): array
    {
        $params = (new QueryParams())->add('user_id', $user_id, true);

        $articles_data = $this->db->select(
            "
            SELECT
                a.id,
                a.name,
                a.description,
                a.text,
                a.code,
                a.user_id,
                a.last_edit
            FROM articles a
            WHERE a.user_id = :user_id
            ",
            $params
        );

        return array_map(fn($article_data) => $this->mapToArticle($article_data), $articles_data ?: []);
    }

    // Get an array of Article objects that match any given user IDs and tag IDs
    // Input: $user_id; User's ID and $tag_id; Tag ID
    // Output: Array of Article objects
    public function getArticlesByUserIdAndTagId(array $user_ids, array $tag_ids): array
    {
        $params = new QueryParams();

        $user_placeholders = [];
        foreach ($user_ids as $i => $user_id) {
            $key = "user_id_$i";
            $params->add($key, $user_id, true);
            $user_placeholders[] = ":$key";
        }

        $tag_placeholders = [];
        foreach ($tag_ids as $i => $tag_id) {
            $key = "tag_id_$i";
            $params->add($key, $tag_id, true);
            $tag_placeholders[] = ":$key";
        }

        $articles_data = $this->db->select(
            "
            SELECT DISTINCT
                a.id,
                a.name,
                a.description,
                a.text,
                a.code,
                a.user_id,
                a.last_edit
            FROM articles a
            JOIN join_articles_tags jat
                ON jat.article_id = a.id
            WHERE a.user_id IN (" . implode(',', $user_placeholders) . ") AND jat.tag_id IN (" . implode(',', $tag_placeholders) . ")
            ",
            $params
        );

        return array_map(fn($article_data) => $this->mapToArticle($article_data), $articles_data ?: []);
    }

    // Get N most recent edited/added articles, sorted by newest to oldest
    // Input: $number; Amount of articles to return
    // Output: Array of Article objects
    public function getMostRecentArticles(int $number): array
    {
        $params = (new QueryParams())->add('number', $number, true);

        $articles_data = $this->db->select(
            "
            SELECT
                a.id,
                a.name,
                a.description,
                a.text,
                a.code,
                a.user_id,
                a.last_edit
            FROM articles a
            ORDER BY a.last_edit DESC
            LIMIT :number
            ",
            $params
        );

        return array_map(fn($article_data) => $this->mapToArticle($article_data), $articles_data ?: []);
    }

    // Add a new article to the database with its provided tags
    // Input: $article; Article object with article data $tagIds; array of associated tag IDs
    public function createArticle(Article $article, array $tag_ids): void
    {
        $params = (new QueryParams())
            ->add('name', $article->name, false)
            ->add('description', $article->description, false)
            ->add('text', $article->text, false)
            ->add('code', $article->code, false)
            ->add('user_id', $article->user_id, true);

        $article_id = $this->db->doInsert(
            "
            INSERT INTO articles
                (name, description, text, code, user_id)
            VALUES (:name, :description, :text, :code, :user_id)
            ",
            $params
        );

        foreach ($tag_ids as $tag_id) {
            $this->addTagToArticle($article_id, $tag_id);
        }
    }

    // Add a tag to an existing article
    // Input: $article_id; Article ID $tag_id; Tag ID
    public function addTagToArticle(int $article_id, int $tag_id): void
    {
        $params = (new QueryParams())
            ->add('article_id', $article_id, true)
            ->add('tag_id', $tag_id, true);

        $this->db->doInsert(
            "
            INSERT INTO join_articles_tags
                (article_id, tag_id)
            VALUES (:article_id, :tag_id)
            ",
            $params
        );
    }

    // Update an existing article's data and its associated tags
    // Input: $article; Article object to update $tagIds; Array of tag IDs for the updated article
    public function updateArticle(Article $article, array $tag_ids): void
    {
        $params = (new QueryParams())
            ->add('name', $article->name, false)
            ->add('description', $article->description, false)
            ->add('text', $article->text, false)
            ->add('code', $article->code, false)
            ->add('user_id', $article->user_id, true)
            ->add('id', $article->id, true);

        $this->db->doUpdate(
            "
            UPDATE articles
            SET
                name = :name,
                description = :description,
                text = :text,
                code = :code,
                user_id = :user_id
            WHERE id = :id
            ",
            $params
        );

        $this->db->doDelete(
            "
            DELETE
            FROM join_articles_tags
            WHERE article_id = :article_id
            ",
            (new QueryParams())->add('article_id', $article->id, true)
        );

        foreach ($tag_ids as $tag_id) {
            $this->addTagToArticle($article->id, $tag_id);
        }
    }

    // Delete an article by its ID
    // Input: $id; Article ID
    public function deleteArticle(int $id): void
    {
        $params = (new QueryParams())->add('id', $id, true);

        $this->db->doDelete(
            "
            DELETE
            FROM articles
            WHERE id = :id
            ",
            $params
        );
    }

    // Helper function to map the associative array from the database to an Article object
    // Input: $row; Array of data from database
    // Output: Article object with data
    private function mapToArticle(array $row): Article
    {
        $article = new Article;
        $article->id = $row['id'];
        $article->name = $row['name'];
        $article->description = $row['description'];
        $article->text = $row['text'];
        $article->code = $row['code'];
        $article->user_id = $row['user_id'];
        $article->last_edit = $row['last_edit'];
        return $article;
    }
}
