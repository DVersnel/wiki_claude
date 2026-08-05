<?php
namespace MDJ\View\Titles;

use MDJ\View\Title;

class ArticleTitle extends Title
{
  protected function showTitle() //TO DO: CSS
    {
         echo '
                <div class="row mb-2">
                    <div class="col-2">&nbsp;</div>
                    <div class="col-8"><h2>'.$this->title.'</h2></div>
                    <div class="col-2"></div>
                </div>
              ';
    }  
}