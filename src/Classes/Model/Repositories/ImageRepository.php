<?php
namespace MDJ\Model\Repositories;

use MDJ\Interfaces\iImageRepository;
use MDJ\Model\Objects\Image;
use ManKind\tools\pdo\QueryParams;

class ImageRepository extends BaseRepository implements iImageRepository
{

    // Get the primary image for an article, if any
    // Input: $article_id; Article ID
    // Output: Image object, or false if the article has no image
    public function getImageByArticleId(int $article_id): Image|false
    {
        $params = (new QueryParams())->add('article_id', $article_id, true);

        $result = $this->db->selectOne(
            "
            SELECT id, description, last_edit, path, article_id, display_order
            FROM images
            WHERE article_id = :article_id
            ORDER BY display_order ASC
            LIMIT 1
            ",
            $params
        );

        return $result ? $this->mapToImage($result) : false;
    }

    // Add a new image to the database
    // Input: $image; Image object with description, path, article_id, display_order
    // Output: New image's ID, or false on failure
    public function createImage(Image $image): int|false
    {
        $params = (new QueryParams())
            ->add('description', $image->description, false)
            ->add('path', $image->path, false)
            ->add('article_id', $image->article_id, true)
            ->add('display_order', $image->display_order, true);

        return $this->db->doInsert(
            "
            INSERT INTO images
                (description, path, article_id, display_order)
            VALUES (:description, :path, :article_id, :display_order)
            ",
            $params
        );
    }

    // Delete all images belonging to an article
    // Input: $article_id; Article ID
    public function deleteImagesByArticleId(int $article_id): void
    {
        $params = (new QueryParams())->add('article_id', $article_id, true);

        $this->db->doDelete(
            "
            DELETE
            FROM images
            WHERE article_id = :article_id
            ",
            $params
        );
    }

    // Helper function to map an associative array from the database to an Image object
    // Input: $row; Array of data from the database
    // Output: Image object with data
    private function mapToImage(array $row): Image
    {
        $image = new Image();
        $image->id = (int)$row['id'];
        $image->description = $row['description'];
        $image->last_edit = $row['last_edit'];
        $image->path = $row['path'];
        $image->article_id = (int)$row['article_id'];
        $image->display_order = (int)$row['display_order'];
        return $image;
    }
}
