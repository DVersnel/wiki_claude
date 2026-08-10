<?php
namespace MDJ\View\InfopanelItems;

use MDJ\Interfaces\iView;

class InfopanelDeleteButton implements iView
{
    private int $article_id;

    public function __construct(int $article_id)
    {
        $this->article_id = $article_id;
    }

    public function show()
    {
        echo '<form method="post" action="' . \Config::BASEURL . '" class="d-inline" onsubmit="return confirm(\'Delete this article? This cannot be undone.\');">
                <input type="hidden" name="page" value="delete_article">
                <input type="hidden" name="id" value="' . $this->article_id . '">
                <button type="submit" class="btn btn-outline-danger btn-sm mt-2">Delete</button>
              </form>';
    }
}
