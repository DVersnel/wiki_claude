<?php
namespace MDJ\View;

use MDJ\Interfaces\iView;

class NewArticleButton implements iView
{
    public function show()
    {
        echo '<div class="row mb-4">
                <div class="col-2"></div>
                <div class="col-8 d-flex justify-content-end">
                    <a href="?page=edit" class="btn btn-primary">New Article</a>
                </div>
                <div class="col-2"></div>
              </div>';
    }
}
