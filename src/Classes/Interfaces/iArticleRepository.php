<?php
namespace MDJ\Interfaces;

use MDJ\Model\Objects\Article;

interface iArticleRepository
{
  public function getArticleById(int $id): Article;
  public function getArticlesByUserId(int $user_id): array;
  public function getArticlesByUserIdAndTagId(array $user_ids, array $tag_ids): array;
  public function getMostRecentArticles(int $number): array;
  public function createArticle(Article $article, array $tag_ids): void;
  public function addTagToArticle(int $article_id, int $tag_id): void;
  public function updateArticle(Article $article, array $tag_ids): void;
  public function deleteArticle(int $id): void;
}