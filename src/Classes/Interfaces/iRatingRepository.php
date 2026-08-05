<?php
namespace MDJ\Interfaces;

use MDJ\Model\Objects\Rating;

interface iRatingRepository
{
    public function getAverageRatingByArticleId(int $article_id): float;
    public function getVoteCount(int $article_id): int;
    public function getRatingByUserAndArticle(int $user_id, int $article_id): Rating|false;
    public function createRating(Rating $rating): int|false;
    public function updateRating(Rating $rating): int|false;
}