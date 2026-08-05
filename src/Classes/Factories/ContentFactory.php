<?php
namespace MDJ\Factories;

use MDJ\Interfaces\iView;
use MDJ\View\PageDescription;
use MDJ\View\ViewList;
use MDJ\View\ListItems\ListItem;
use MDJ\View\ListItems\LinkListItem;
use MDJ\View\Form;
use MDJ\View\Forms\SearchForm;
use MDJ\View\Paragraph;
use MDJ\View\Code;
use MDJ\View\Infopanel;
use MDJ\View\ArticleContentBox;
use MDJ\View\Titles\ArticleTitle;
use MDJ\View\Titles\ArticleTitleLink;
use MDJ\Interfaces\iFactory;


class ContentFactory implements iFactory
{
    public function createItem(array $content_item_info): iView
    {
        switch ($content_item_info['type'])
        {
            case 'list':
                $list_collection = new Collection($content_item_info['list'], $this);
                return new ViewList($list_collection->getItems());
            case 'list_item':
                return new ListItem($content_item_info['name']);
            case 'link_item':
                return new LinkListItem($content_item_info['name'], $content_item_info['page']);
            case 'description':
                return new PageDescription($content_item_info['text']);
            case 'paragraph':
                return new Paragraph($content_item_info['text']);
            case 'code':
                return new Code($content_item_info['text']);
            case 'article_title':
                return new ArticleTitle($content_item_info['title']);
            case 'article_title_link':
                return new ArticleTitleLink($content_item_info['title'], $content_item_info['id']);
            case 'infopanel':
                $info_collection = new Collection($content_item_info['info_items'], new InfopanelFactory());
                return new Infopanel($info_collection->getItems());
            case 'article':
                $content_collection = new Collection($content_item_info['content'], $this);
                $info_collection = new Collection($content_item_info['info_items'] ?? [], new InfopanelFactory());
                return new ArticleContentBox($content_collection->getItems(), $info_collection->getItems());
            case 'form':
                $form_collection = new Collection($content_item_info['form_fields'], new FormFieldFactory());
                return new Form($form_collection->getItems());
            case 'search_form':
                $author_collection = new Collection($content_item_info['authors'], new FormFieldFactory());
                $tag_collection = new Collection($content_item_info['tags'], new FormFieldFactory());
                return new SearchForm($author_collection->getItems(), $tag_collection->getItems());
                
            default:
                throw new \Exception('Error: Item not found'. $content_item_info['type'].'') ;
       }
    }
}