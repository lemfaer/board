<?php

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

require __DIR__ . "/inc/autoload.php";
require __DIR__ . "/src/autoload.php";

$conf = new \Board\Service\Config(include "config.php");
$cont = new \Board\Service\Container([ \Board\Service\Config::class => $conf ]);

$uri = trim($_SERVER["REQUEST_URI"], "/");
$method = $_SERVER["REQUEST_METHOD"];

$router = $cont->get(\Board\Service\Router::class);
echo $router->dispatch($method, $uri);
