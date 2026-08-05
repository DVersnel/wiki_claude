<?php
namespace MDJ\View;

use MDJ\Interfaces\iView;

class PageDescription implements iView
{
    protected string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function show()
    {
        echo '<div class="row d-flex mb-5 justify-content-center">'.$this->text.'</div>';
    }
}