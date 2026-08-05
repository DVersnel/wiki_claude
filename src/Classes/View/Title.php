<?php
namespace MDJ\View;

use MDJ\Interfaces\iView;

class Title implements iView
{
    protected string $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    protected function openTitle()
    {
        echo '<div>';       
    }

    protected function showTitle()
    {
         
    }

    protected function closeTitle()
    {
        echo '</div>';
    }

    public function show()
    {
        $this->openTitle();
        $this->showTitle();
        $this->closeTitle();
    }
}