<?php
namespace MDJ\Interfaces;

use MDJ\Model\Objects\Image;

interface iImageRepository
{
    public function getImageByArticleId(int $article_id): Image|false;
    public function createImage(Image $image): int|false;
    public function deleteImagesByArticleId(int $article_id): void;
}
