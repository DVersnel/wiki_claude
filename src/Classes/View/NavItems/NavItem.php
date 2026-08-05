<?php
namespace MDJ\View\NavItems;

use MDJ\Interfaces\iView;
// use MDJ\src\Config;

class NavItem implements iView 
{
    protected string $page;
    protected string $name;
	protected string $li_class;
	protected string $a_class;

    public function __construct(string $page, string $name, string $li_class, string $a_class) 
	{
        $this->page = $page;
        $this->name = $name;
		$this->li_class = $li_class;
		$this->a_class = $a_class;
    }

    public function show() 
	{
        echo '<li class="'.$this->li_class.'"> 
                    <a class=" '.$this->a_class.'" href="'.\Config::BASEURL.'?page='.$this->page.'">'.$this->name.'</a>
              </li>';
    }
}