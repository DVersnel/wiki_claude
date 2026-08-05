<?php
namespace MDJ\Factories;

use MDJ\View\ListItems\ListItem;
use MDJ\View\ListItems\NestedListItem;
use MDJ\View\ListItems\LinkListItem;
use MDJ\Interfaces\iView;
use MDJ\Interfaces\iFactory;


class ListItemFactory implements iFactory
{
    public function createItem(array $list_item_info): iView
    {
        switch ($list_item_info['item_type']) 
        {
            case 'list':
                $list_item_collection = new ListItemCollection($list_item_info['items'], $this);
                return new NestedListItem($list_item_collection->getListItems());
                
            case 'link':
                return new LinkListItem($list_item_info['page'], $list_item_info['name']);

            default:
                return new ListItem($list_item_info['name']);
        }
    }
}