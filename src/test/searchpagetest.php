<?php
namespace MDJ\test;

use MDJ\Factories\ContentFactory;


use MDJ\Factories\Collection;
use MDJ\Factories\NavItemFactory;
use MDJ\View\BasePage;
use MDJ\View\Header;
use MDJ\View\Footer;
use MDJ\View\Titles\PageTitle;
use MDJ\View\ContentBox;


require_once __DIR__ . '/../src/config.php';

$author_array = 
[

    [
        'type' => 'checkbox',
        'name' => 'Mark',
        'label' => 'id_mark'
    ],
    [
        'type' => 'checkbox',
        'name' => 'Daniel',
        'label' => 'id_daniel'
    ],
    [
        'type' => 'checkbox',
        'name' => 'Jesse',
        'label' => 'id_jesse'
    ]
];

$tag_array = 
[

    [
        'type' => 'checkbox',
        'name' => 'Tag 1',
        'label' => 'tag_id1'
    ],
    [
        'type' => 'checkbox',
        'name' => 'Tag 2',
        'label' => 'tag_id2'
    ],
    [
        'type' => 'checkbox',
        'name' => 'Tag 3',
        'label' => 'tag_id3'
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
        'type' => 'search_form',
        'authors' => $author_array,
        'tags' => $tag_array
    ]
];

$elements = [];

$content_factory = new ContentFactory();
$content_items = new Collection($content_items_array, $content_factory);

$nav_factory = new NavItemFactory();
$hamburger_items = (new Collection($hamburger_items_array, $nav_factory))->getItems();
$nav_items = (new Collection($nav_items_array, $nav_factory))->getItems();
$elements[] = new Header($hamburger_items, $nav_items, 'Logo');
$elements[] = new PageTitle('Search');
$elements[] = new ContentBox($content_items->getItems());

$elements[] = new Footer('');
$page = new BasePage($elements);
$page->show();