<?php
require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../vendor/ManKind/tools/pdo/Crud.php';

$c = ManKind\tools\pdo\Crud::getInstance();
echo 'Connected: ';
var_dump($c->isConnected());
echo 'Error: ' . $c->getLastError() . PHP_EOL;