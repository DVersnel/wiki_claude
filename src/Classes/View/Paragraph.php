<?php
namespace MDJ\View;

use MDJ\Interfaces\iView;

class Paragraph implements iView
{
    protected string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function show()
    {
        echo '<div class="d-flex mb-5 justify-content-start">'.$this->text.'</div>';
    }
}