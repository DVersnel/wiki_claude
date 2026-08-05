<?php
namespace MDJ\View\ListItems;

use MDJ\Interfaces\iView;

class NestedListItem implements iView
{
    protected array $child_items;

    public function __construct(array $child_items)
    {
        $this->child_items = $child_items;
    }

    public function show()
    {
        $this->openList();
        $this->showList();
        $this->closeList();
    }

    protected function openList()
    {
        echo '<li class="list-group-item"><ul class="list-group list-group-flush">';
    }

    protected function showList()
    {
        foreach ($this->child_items as $child_item) 
        {
            $child_item->show();
        }
    }

    protected function closeList()
    {
         echo '</ul></li>';
    }

}