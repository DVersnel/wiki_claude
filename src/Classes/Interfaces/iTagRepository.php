<?php
namespace MDJ\Interfaces;

use MDJ\Model\Objects\Tag;

interface iTagRepository
{
    public function getTagById(int $id): Tag|false;
    public function getTagsByArticleId(int $article_id): array|false;
    public function getTags(): array|false;
    public function createTag(Tag $tag): int|false;
}