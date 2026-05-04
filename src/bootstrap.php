<?php

declare(strict_types=1);

define('BASE_PATH' , dirname(__DIR__));

require BASE_PATH . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->safeLoad();

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/config/routes.php';