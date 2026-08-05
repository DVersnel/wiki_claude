<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/config.php';

use MDJ\Controller\MainController;

session_start();

$controller = new MainController();
$controller->handleRequest();
