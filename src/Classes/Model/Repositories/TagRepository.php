<?php
namespace MDJ\Model\Repositories;

use MDJ\Model\Objects\Tag;
use MDJ\Interfaces\iTagRepository;

use ManKind\tools\pdo\QueryParams;


class TagRepository extends BaseRepository implements iTagRepository
{

    // Get a singular Tag object by its ID
    // Input: $id; Tag ID
    // Output: Tag object
    public function getTagById(int $id): Tag|false
    {
        $sql = 
            "
            SELECT *
            FROM tags
            WHERE id = :id
            ";
        
        $params = (new QueryParams())->add('id', $id, true);

        $result = $this->db->selectOne($sql, $params);
        
        return count($result) > 0 ? $this->mapToTag($result) : false;
    }

    // Get all tags associated with a specific article
    // Input: $articleId; Article ID
    // Output: Array of Tag objects
    public function getTagsByArticleId(int $article_id): array|false
    {
        $sql = 
            "
            SELECT t.*
            FROM tags t
            JOIN join_articles_tags jat
                ON t.id = jat.tag_id
            WHERE jat.article_id = :article_id
            ";
        
        $params = (new QueryParams())->add('article_id', $article_id, true);

        $result = $this->db->select($sql, $params);

        return count($result) > 0 ? array_map(fn($tag_data) => $this->mapToTag($tag_data), $result) : false;
    }

    // Get all tags from the database
    // Output: Array of Tag objects
    public function getTags(): array|false
    {
        $result = $this->db->select
        (
            "
            SELECT
                *
            FROM tags
            "
        );

        return array_map(fn($tag_data) => $this->mapToTag($tag_data), $result);
    }

    // Add a new tag into the database
    // Input: $tag; Tag object with the tag data
    public function createTag(Tag $tag): int|false
    {
        $params = (new QueryParams())->add("tag", $tag->getTag(), false);

        return $this->db->doInsert
        (
            "
            INSERT INTO tags
            (tag)
            VALUES
            (:tag)
            ",
            $params
        );
    }

    // Helper function to map an associative array from the database to a Tag object
    // Input: $row; Array of data from the database
    // Output: Tag object with data
    private function mapToTag(array $row): Tag
    {
       return new Tag
       (
            $row['id'],
            $row['tag']
        );
    }
}