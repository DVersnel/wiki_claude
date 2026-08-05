<?php
namespace MDJ\View\NavItems;

use MDJ\View\NavItems\NavItem;

class IconItem extends NavItem
{
	 public function __construct(string $page, string $name, string $li_class, string $a_class, string $icon)
	 {
		parent::__construct($page, $name, $li_class, $a_class);
		$this->name = '<i class="bi bi-'.$icon.'"></i>&nbsp;'.$this->name; 
	 }
}