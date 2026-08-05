<?php
namespace MDJ\View\InfopanelItems;

use MDJ\Interfaces\iView;

class InfopanelRating implements iView
{
    private $article_id;
    private $rating;
    private $count;

    public function __construct(int $article_id, float $rating, int $count)
    {
        $this->article_id = $article_id;
        $this->rating = $rating;
        $this->count = $count;
    }

    public function show()
    {
        echo '<div class="infopanel-rating">';
        echo '<div class="rating_stars" data-article_id="'.$this->article_id.'" data-avg_rating="'.($this->rating ?? 0).'">
                    <span class="star" data-value="1">☆</span>
                    <span class="star" data-value="2">☆</span>
                    <span class="star" data-value="3">☆</span>
                    <span class="star" data-value="4">☆</span>
                    <span class="star" data-value="5">☆</span>
                </div>';
        echo '<div class="avg_rating">
                    Average: '.(round($this->rating ?? 0, 2)).'/5 ('.($this->count ?? 0).')
            </div>';
        echo '</div>';
    }
}