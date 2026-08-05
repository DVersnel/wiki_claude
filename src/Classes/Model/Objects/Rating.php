<?php
namespace MDJ\Model\Objects;

class Rating
{
    public int $rating;
    public int $article_id;
    public int $user_id;
    public mixed $timestamp;

    public function __construct(int $rating, int $article_id, int $user_id, mixed $timestamp)
    {
        $this->rating = $rating;
        $this->article_id = $article_id;
        $this->user_id = $user_id;
        $this->timestamp = $timestamp;
    }

    public function getRating(): int
    {
        return $this->rating;
    }
    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    public function getArticleId(): int
    {
        return $this->article_id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }
}