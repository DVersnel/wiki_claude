<?php
namespace MDJ\View\InfopanelItems;

use MDJ\Interfaces\iView;

class InfopanelEditButton implements iView
{
    private int $article_id;

    public function __construct(int $article_id)
    {
        $this->article_id = $article_id;
    }

    public function show()
    {
        echo '<div class="infopanel-edit mt-2">
                <a href="?page=edit&id=' . $this->article_id . '" class="btn btn-outline-secondary btn-sm">Edit</a>
              </div>';
    }
}
