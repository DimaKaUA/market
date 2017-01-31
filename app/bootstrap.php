<?php 
require_once __DIR__ . '/../vendor/autoload.php';

define('ROOT', dirname(__FILE__));

require_once ROOT . '/libs/DB.php';
require_once ROOT . '/core/Model.php';
require_once ROOT . '/core/View.php';
require_once ROOT . '/core/Controller.php';
require_once ROOT . '/core/Router.php';

$router = new Router();
$router->start(); //initiate router