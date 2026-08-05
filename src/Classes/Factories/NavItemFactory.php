<?php
namespace MDJ\Factories;

use MDJ\View\NavItems\HamburgerItem;
use MDJ\View\NavItems\NavItem;
use MDJ\View\NavItems\DropdownItem;
use MDJ\View\NavItems\IconItem;
use MDJ\Interfaces\iView;
use MDJ\Interfaces\iFactory;


class NavItemFactory implements iFactory
{
    public function createItem(array $nav_item_info): iView
    {
        switch ($nav_item_info['type']) 
		{
            // case 'dropdown':
			// 	$nav_item_collection = new NavItemCollection(['dropdown_items'], $this);
			// 	return new DropdownItem($nav_item_collection->getNavItems(), $nav_item_info['name']);
			
			case 'icon':
                return new IconItem($nav_item_info['page'], $nav_item_info['name'], $nav_item_info['li_class'], $nav_item_info['a_class'], $nav_item_info['icon']);
			case 'hamburger':
                return new HamburgerItem($nav_item_info['page'], $nav_item_info['name'], $nav_item_info['li_class'], $nav_item_info['a_class']);

			default:
                return new NavItem($nav_item_info['page'], $nav_item_info['name'], $nav_item_info['li_class'], $nav_item_info['a_class']);
        }
    }
}