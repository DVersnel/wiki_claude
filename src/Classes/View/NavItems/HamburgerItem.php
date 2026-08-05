<?php
namespace MDJ\View\NavItems;

use MDJ\Interfaces\iView;
// use MDJ\src\Config;

class HamburgerItem extends NavItem 
{
    public function show() 
	{
        echo '<li class="'.$this->li_class.'"> 
                    <a class="px-3 '.$this->a_class.'" href="'.\Config::BASEURL.'?page='.$this->page.'">'.$this->name.'</a>
              </li>';
    }
}