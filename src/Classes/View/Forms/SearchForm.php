<?php
namespace MDJ\View\Forms;
use MDJ\View\Form;

class SearchForm extends Form {
    private array $authors;
    private array $tags;

    public function __construct(array $authors, array $tags)
    {
        $this->authors = $authors;
        $this->tags = $tags;
    }

    protected function showFields()
    {
        echo '<div class="d-flex gap-5 justify-content-center p-4">';
        echo '<div class="card border-0 p-0 col-5">';
        echo '<div class="card-header">Authors</div>';
        echo '<div class="card-body overflow-auto w-100" style="max-height: 400px;">';
        foreach ($this->authors as $author) 
        {
            $author->show();
        }
        echo '</div></div>';

        echo '<div class="card border-0 p-0 col-5">';
        echo '<div class="card-header">Tags</div>';
        echo '<div class="card-body overflow-auto w-100" style="max-height: 400px;">';
        foreach ($this->tags as $tag) 
        {
            $tag->show();
        }
        echo '</div></div></div>';     
    }
}