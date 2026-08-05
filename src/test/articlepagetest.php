<?php
namespace MDJ\test;

use MDJ\Factories\ContentFactory;

use MDJ\Factories\FormFieldFactory;
use MDJ\Factories\InfopanelFactory;
use MDJ\Factories\InfopanelCollection;
use MDJ\Factories\Collection;
use MDJ\Controller\TitleFactory;
use MDJ\Factories\NavItemFactory;
use MDJ\Model\Repositories\ArticleRepository;
use MDJ\View\BasePage;
use MDJ\View\Header;
use MDJ\View\Footer;
use MDJ\View\Infopanel;
use MDJ\View\Form;
use MDJ\View\PageDescription;
use MDJ\View\Titles\PageTitle;
use MDJ\View\ContentBox;
use MDJ\View\ArticleContentBox;

use MDJ\View\Titles\ArticleTitle;
use MDJ\View\ArticleContentBox;
use MDJ\View\Title;

require_once __DIR__ . '/../src/config.php';


$infopanel_items_array = 
[
    [
        'type' => 'image',
        'image' => '../Images/olifant.jpg'
    ],
    [
        'type' => 'textbox',
        'author' => 'Moeder Maria',
        'tags' => ['tag1', 'tag2', 'tag3']
    ],
    [
        'type' => 'rating',
        'article_id' => 1,
        'rating' => 4,
        'count' => 10
    ]
];

$hamburger_items_array = 
[
    [
        'type' => '',
        'page' => 'about',
        'name' => 'About',
        'li_class' => 'nav-item',
        'a_class' => 'nav-link'
    ],
        [
        'type' => '',
        'page' => 'contact',
        'name' => 'Contact',
        'li_class' => 'nav-item',
        'a_class' => 'nav-link'
    ]
];

$nav_items_array = 
[
    [
        'type' => '',
        'page' => 'login',
        'name' => 'Login',
        'li_class' => 'nav-item',
        'a_class' => 'nav-link'
    ],
    [
        'type' => '',
        'page' => 'register',
        'name' => 'Register',
        'li_class' => 'nav-item',
        'a_class' => 'nav-link'
    ]
];

$content_items_array = 
[
    [
        'type' => 'paragraph',
        'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
    ],
    [
        'type' => 'code',
        'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
        Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
        Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, 
        consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'
    ]
];

$page_title = 
[
    [
        'type' => 'page',
        'name' => 'page title2'
    ],
    [
        'type' => 'article',
        'name' => 'article title2'
    ]
];

$elements = [];

$content_factory = new ContentFactory();
$content_items = new Collection($content_items_array, $content_factory);

$nav_factory = new NavItemFactory();
$hamburger_items = (new Collection($hamburger_items_array, $nav_factory))->getItems();
$nav_items = (new Collection($nav_items_array, $nav_factory))->getItems();
$elements[] = new Header($hamburger_items, $nav_items, 'Logo');
$elements[] = new ArticleTitle('Article title');
//$elements[] = new PageTitle('Article Title', 'left');
   
$elements[] = new ArticleRepository()->getMostRecentArticles(5);
// $field_factory = new FormFieldFactory();
// $form_fields = (new FormFieldCollection($form_fields_array, $field_factory))->getFormFields();
// $elements[] = new Form($form_fields);

$infopanel_factory = new InfopanelFactory();
$infopanel_items = (new InfopanelCollection($infopanel_items_array, $infopanel_factory))->getInfopanelItems();
$elements[] = new ArticleContentBox($content_items->getItems(), $infopanel_items);

$elements[] = new Footer('');
$page = new BasePage($elements);
$page->show();