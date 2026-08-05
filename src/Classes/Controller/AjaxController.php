<?php
namespace MDJ\Controller;

use MDJ\Model\Repositories\RatingRepository;
use MDJ\Model\Objects\Rating;
use MDJ\Model\Objects\User;

class AjaxController
{
    private array $request;
    private array $response;

    public function handleRequest()
    {
        header('Content-Type: application/json; charset=utf-8');

        $this->getRequest();

        try
        {
            $this->validateRequest();
        }
        catch (\Exception $e)
        {
            $this->response = ['success' => false, 'message' => $e->getMessage()];
        }

        echo json_encode($this->response);
    }

    private function getRequest()
    {
        $this->request = $_POST;
    }

    private function validateRequest()
    {
        switch ($this->request['action'] ?? '')
        {
            case 'add_rating':
                $this->addRating();
                break;
            default:
                throw new \Exception('Unknown action');
        }
    }

    private function addRating()
    {
        $user = $_SESSION['user'] ?? null;
        if (!($user instanceof User))
        {
            throw new \Exception('You must be logged in to rate an article');
        }

        $article_id = (int)($this->request['article_id'] ?? 0);
        $rating_value = (int)($this->request['rating'] ?? 0);

        if ($rating_value < 1 || $rating_value > 5)
        {
            throw new \Exception('Error: Rating must be between 1 and 5');
        }

        $rating = new Rating(
            rating: $rating_value,
            article_id: $article_id,
            user_id: $user->getId(),
            timestamp: date('Y-m-d H:i:s')
        );

        $rating_repo = new RatingRepository();
        $existing = $rating_repo->getRatingByUserAndArticle($user->getId(), $article_id);
        $result = $existing
            ? $rating_repo->updateRating($rating)
            : $rating_repo->createRating($rating);

        if ($result === false)
        {
            $this->response = ['success' => false, 'message' => 'Error: Could not save rating'];
            return;
        }

        $this->response = [
            'success' => true,
            'message' => 'Rating saved',
            'average' => $rating_repo->getAverageRatingByArticleId($article_id),
            'count' => $rating_repo->getVoteCount($article_id),
        ];
    }
}
