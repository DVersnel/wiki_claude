<?php
namespace MDJ\View\ListItems;

use MDJ\Interfaces\iView;

class ListItem implements iView
{
    protected string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function show()
    {
        echo '<li class="list-group-item">'.$this->name.'</li>';
    }
}