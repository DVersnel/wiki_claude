<?php
namespace MDJ\View\Titles;

class ArticleTitleLink extends ArticleTitle
{
    private int $article_id;

    public function __construct(string $title, int $article_id)
    {
        parent::__construct($title);
        $this->article_id = $article_id;
    }

    protected function showTitle() //TO DO: CSS
    {
        echo '
                <div class="row mb-2">
                    <div class="col-2">&nbsp;</div>
                    <div class="col-8"><h2><a href="?page=article&id='.$this->article_id.'" class="text-decoration-none">'.$this->title.'</a></h2></div>
                    <div class="col-2"></div>
                </div>
              ';
    }
}
