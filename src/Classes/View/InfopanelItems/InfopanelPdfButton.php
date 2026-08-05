<?php
namespace MDJ\View\InfopanelItems;

use MDJ\Interfaces\iView;

class InfopanelPdfButton implements iView
{
    private int $article_id;

    public function __construct(int $article_id)
    {
        $this->article_id = $article_id;
    }

    public function show()
    {
        echo '<div class="infopanel-pdf-download mt-3">
                <a href="?page=article&id=' . $this->article_id . '&download=pdf" class="btn btn-outline-secondary btn-sm">Download PDF</a>
              </div>';
    }
}
