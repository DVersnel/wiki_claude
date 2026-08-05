<?php
namespace MDJ\Factories;

use MDJ\Interfaces\iElementFactory;
use MDJ\Model\Repositories\ArticleRepository;
use MDJ\View\Header;
use MDJ\View\Footer;
use MDJ\View\PageDescription;
use MDJ\View\Titles\PageTitle;
use MDJ\View\ContentBox;
use MDJ\View\Forms\Form;
use MDJ\Interfaces\iView;
use MDJ\View\BasePage;
use MDJ\Model\Repositories\PageDataRepository;

class ElementFactory implements iElementFactory
{
    public function createItem(array $page_info): array
    {
        $elements = [];

        $nav_factory = new NavItemFactory();
        $hamburger_items = (new Collection($page_info['hamburger'], $nav_factory))->getItems();
        $nav_items = (new Collection($page_info['nav_items'], $nav_factory))->getItems();

        $elements[] = new Header($hamburger_items,
                                $nav_items,
                                $page_info['logo']);
        $elements[] = new Footer($page_info['footer']);

        switch ($page_info['page'])
        {
            case 'contact':
            case 'login':
            case 'register':
            case 'edit':
            case 'search':
                $content_factory = new ContentFactory();
                $content_items = new Collection($page_info['content'] ?? [], $content_factory);
                $elements[] = new PageTitle($page_info['title']);
                $elements[] = new PageDescription($page_info['description']);
                $elements[] = new ContentBox($content_items->getItems());
                break;

            case 'home':
                $content_factory = new ContentFactory();
                $article_items = new Collection($page_info['content'] ?? [], $content_factory);
                $elements[] = new PageTitle($page_info['title']);
                $elements[] = new PageDescription($page_info['description']);
                foreach ($article_items->getItems() as $article_item)
                {
                    $elements[] = $article_item;
                }
                break;

            case 'article':
                $content_factory = new ContentFactory();
                $article_items = new Collection($page_info['content'] ?? [], $content_factory);
                foreach ($article_items->getItems() as $article_item)
                {
                    $elements[] = $article_item;
                }
                break;

            default:
                $elements[] = new PageTitle($page_info['title']);
                $elements[] = new PageDescription($page_info['description']);
        }

        return $elements;
    }
}
