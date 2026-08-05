<?php
namespace MDJ\test;

use MDJ\Factories\ContentFactory;

use MDJ\Factories\FormFieldFactory;
use MDJ\Factories\InfopanelFactory;
use MDJ\Factories\Collection;
use MDJ\Controller\TitleFactory;
use MDJ\Factories\NavItemFactory;
use MDJ\View\BasePage;
use MDJ\View\Header;
use MDJ\View\Footer;
use MDJ\View\Infopanel;
use MDJ\View\Form;
use MDJ\View\PageDescription;
use MDJ\View\Titles\PageTitle;
use MDJ\View\ContentBox;
use MDJ\View\Titles\ArticleTitle;
use MDJ\View\Title;

require_once __DIR__ . '/../src/config.php';

$form_fields_array = 
[
    [
        'type' => 'email',
        'name' => 'test email',
        'label' => 'testlabel1'
    ],
    [
        'type' => 'password',
        'name' => 'test password',
        'label' => 'testlabel2'
    ],
    [
        'type' => 'textarea',
        'name' => 'test je moeder',
        'label' => 'testlabel3'
    ],
    [
        'type' => 'checkbox',
        'name' => 'test je checkbox',
        'label' => 'testlabel4'
    ]
];

$infopanel_items_array = 
[
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

$list_items_array =
[
    [
        'type' => 'link_item', 
        'name' => 'Mark',
        'page' => 'mark'
    ],
    [
        'type' => 'link_item', 
        'name' => 'Jesse',
        'page' => 'jesse'
    ],
    [
        'type' => 'link_item', 
        'name' => 'Daniel',
        'page' => 'daniel'
    ],
    [
        'type' => 'link_item', 
        'name' => 'Rulian',
        'page' => 'rulian'
    ],
    [
        'type' => 'link_item', 
        'name' => 'Matthijs',
        'page' => 'matthijs'
    ]
    
];


$content_items_array = 
[
    [
        'type' => 'description',
        'text' => 'This is the about page.'
    ],
    [
        'type' => 'list',
        'list' => $list_items_array
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
// $elements[] = new ArticleTitle('article title');
$elements[] = new PageTitle('About');
$elements[] = new ContentBox($content_items->getItems());
   
// $field_factory = new FormFieldFactory();
// $form_fields = (new FormFieldCollection($form_fields_array, $field_factory))->getFormFields();
// $elements[] = new Form($form_fields);

// $infopanel_factory = new InfopanelFactory();
// $infopanel_items = (new InfopanelCollection($infopanel_items_array, $infopanel_factory))->getInfopanelItems();
// $elements[] = new Infopanel($infopanel_items);

$elements[] = new Footer('');
$page = new BasePage($elements);
$page->show();