<?php
namespace MDJ\View\Titles;

use MDJ\View\Title;

class PageTitle extends Title 
{
    protected function showTitle()
    {
            echo '<div class="row my-5">
                    <div class="col-1"></div>
                    <div class="col d-flex justify-content-start">
                        <h1>'.$this->title.'</h1>
                    </div>
                </div>';
    }
}