<?php
namespace MDJ\View\ListItems;

use MDJ\Interfaces\iView;

class LinkListItem extends ListItem implements iView
{
    protected string $page;

    public function __construct(string $name, string $page)
    {
        $this->page = $page;
        parent::__construct($name);
    }

    public function show()
    {
        echo '<a class="list-group-item list-group-item-action" href="?page='.$this->page.'">'.$this->name.'</a>';
    }
}