<?php
namespace MDJ\View\InfopanelItems;

use MDJ\Interfaces\iView;

class InfopanelImage implements iView
{

    private $image;

    public function __construct(string $image)
    {
        $this->image = $image;
    }

    public function show()
    {
        echo '<div class="card mb-3">';
        echo '<div class="card-body">';
        echo '<div class="infopanel-image mb-3 justify-content-center d-flex">';
        echo '<img src="' . htmlspecialchars($this->image) . '" class="img-fluid">';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}