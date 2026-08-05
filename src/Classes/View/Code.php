<?php
namespace MDJ\View;

use MDJ\Interfaces\iView;

class Code implements iView
{
    protected string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function show()
    {
        echo '<div class="d-flex mb-5 justify-content-start"><code style="color: limegreen;">'.$this->text.'</code></div>';
    }
}