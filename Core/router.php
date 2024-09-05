<?php
// STARI NAČIN

// $uri = parse_url($_SERVER['REQUEST_URI'])["path"];
// $uri = str_replace('/videoteka','',parse_url($_SERVER['REQUEST_URI'])["path"]);
// $routes = require base_path('routes.php');
// dd($uri);
// function routeToController(string $uri, array $routes)
// {
//     if (array_key_exists($uri, $routes)) {
//         // echo $uri;
//         // echo base_path($routes[$uri]);
//         $subDir = "/videoteka";
//         require base_path($routes[$uri]);
//     } else {
//         // echo $uri;
//         abort();
//     }
// }

// routeToController($uri, $routes);

// POMOĆU Router klase

use Core\Router;
use Core\Session;

$subDir = "/videoteka";

$uri = str_replace('/videoteka','',parse_url($_SERVER['REQUEST_URI'])["path"]);
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
// dd($uri);
$router = new Router();
require base_path('routes.php');
$router->route($uri, $method, $subDir);
