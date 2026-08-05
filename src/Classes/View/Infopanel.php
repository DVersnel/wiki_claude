<?php
namespace MDJ\View;

use MDJ\Interfaces\iView;

class Infopanel implements iView
{
    protected array $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function show()
    {
        $this->openPanel();
        $this->showItems();
        $this->closePanel();
    }

    private function openPanel()
    {
        echo '<div class="infopanel d-flex justify-content-end">';
        echo '<div class="card mb-3">';
        echo '<div class="card-body">';
    }

    private function showItems()
    {
        foreach ($this->items as $item) {
            $item->show();
        }
    }

    private function closePanel()
    {
        echo '</div></div></div>';
    }
}