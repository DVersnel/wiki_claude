<?php
namespace MDJ\View;


class ArticleContentBox extends ContentBox
{
    private array $sidebar_items;

    public function __construct(array $content_items, array $sidebar_items)
    {
        parent::__construct($content_items);
        $this->sidebar_items = $sidebar_items;
    }

    protected function openContent()  // override parent
    {
        echo '<div class="row">
            <div class="col-2"></div>
            <div class="col-8"><div class="row">
            <div class="col-8">';   // content column
    }

    protected function closeContent() // override parent
    {
        echo '</div>';  // close col-8 content
        // render sidebar here
        echo '<div class="col-4">';
        
        foreach ($this->sidebar_items as $item) 
        { 
            $item->show(); 
        }

        echo '</div></div></div><div class="col-2"></div></div>';
    }
}