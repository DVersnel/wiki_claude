<?php
namespace MDJ\View;

use MDJ\Interfaces\iView;

class ViewList implements iView
{
    private array $list_items;
    public function __construct(array $list_items)
    {
        $this->list_items = $list_items;
    }

    public function show()
    {
        $this->openList();
        $this->showListItems();
        $this->closeList();
    }

    protected function openList()
    {
        echo '<div class="card border-0 col-4 p-0"><div class="card-header">Authors</div><ul class="list-group list-group-flush">';
    }

    protected function showListItems()
    {
        foreach ($this->list_items as $list_item)
        {
            $list_item->show();
        }
    }

    protected function closeList()
    {
        echo '</ul></div>';
    }
}