<?php
namespace MDJ\View;

use MDJ\Interfaces\iView; 

class ContentBox implements iView
{
    private array $content_items;

    public function __construct(array $content_items)
    {
        $this->content_items = $content_items;
    }

    public function show()
    {
        $this->openContent();
        $this->showContent();
        $this->closeContent();
    }

    protected function openContent()
    {
        echo '<div class="row">
        <div class="col-1"></div>
        <div class="col-10">
        <div class="row">';
    }

    protected function showContent()
    {
        foreach ($this->content_items as $content_item) 
		{
            $content_item->show();
        }
	}
    

    protected function closeContent()
    {
        echo '</div></div></div>';
    }

}