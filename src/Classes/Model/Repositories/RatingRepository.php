<?php
namespace MDJ\Model\Repositories;

use MDJ\Interfaces\iRatingRepository;
use MDJ\Model\Objects\Rating;
use ManKind\tools\pdo\QueryParams;

class RatingRepository extends BaseRepository implements iRatingRepository
{

    // Get the average rating for an article
    // Input: $article_id; Article ID
    // Output: Average rating as a float
    public function getAverageRatingByArticleId(int $article_id): float
    {
        $params = (new QueryParams())->add("article_id", $article_id, true);
        
        $result = $this->db->selectOne(
            "
            SELECT AVG(rating) as avg_rating
            FROM ratings 
            WHERE article_id = :article_id
            ",
            $params
        );
        
        return $result ? (float)$result['avg_rating'] : 0.0;
    }

    // Get the total number of ratings for an article
    // Input: $article_id; Article ID
    // Output: Vote count as an integer
    public function getVoteCount(int $article_id): int
    {
        $params = (new QueryParams())->add("article_id", $article_id, true);
        
        $result = $this->db->selectOne(
            "
            SELECT COUNT(*) as vote_count
            FROM ratings 
            WHERE article_id = :article_id
            ",
            $params
        );
        
        return $result ? (int)$result['vote_count'] : 0;
    }

    // Get a user's existing rating for an article, if any
    // Input: $user_id; User ID $article_id; Article ID
    // Output: Rating object, or false if the user hasn't rated this article
    public function getRatingByUserAndArticle(int $user_id, int $article_id): Rating|false
    {
        $params = (new QueryParams())
            ->add("user_id", $user_id, true)
            ->add("article_id", $article_id, true);

        $result = $this->db->selectOne(
            "
            SELECT user_id, article_id, rating, timestamp
            FROM ratings
            WHERE user_id = :user_id
                AND article_id = :article_id
            ",
            $params
        );

        return $result ? $this->mapToRating($result) : false;
    }

    // Save a new rating to the database
    // Input: $rating; Rating object with article_id, user_id, and rating
    public function createRating(Rating $rating): int|false
    {
        $params = (new QueryParams())
            ->add("article_id", $rating->article_id, true)
            ->add("user_id", $rating->user_id, true)
            ->add("rating", $rating->rating, true);

        return $this->db->doInsert(
            "
            INSERT INTO ratings
                (article_id, user_id, rating)
            VALUES (:article_id, :user_id, :rating)
            ",
            $params
        );
    }

    // Update an existing rating in the database
    // Input: $rating; Rating object with updated rating value
    public function updateRating(Rating $rating): int|false
    {
        $params = (new QueryParams())
            ->add("rating", $rating->rating, true)
            ->add("article_id", $rating->article_id, true)
            ->add("user_id", $rating->user_id, true);

        return $this->db->doUpdate(
            "
            UPDATE ratings
            SET rating = :rating
            WHERE article_id = :article_id
                AND user_id = :user_id
            ",
            $params
        );
    }

    // Helper function to map an associative array from the database to a Rating object
    // Input: $row; Array of data from the database
    // Output: Rating object with data
    private function mapToRating(array $row): Rating
    {
        return new Rating(
            rating: (int)$row['rating'],
            article_id: (int)$row['article_id'],
            user_id: (int)$row['user_id'],
            timestamp: $row['timestamp']
        );
    }
}