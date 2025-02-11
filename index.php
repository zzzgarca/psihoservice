<?php

require_once 'app/init.php';


$basePath = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = str_replace($basePath, '', $uri);
$uri = trim($uri, '/');


$segments = explode('/', $uri);


$controllerName = !empty($segments[0]) ? ucfirst($segments[0]) . 'Controller' : 'HomeController';
$method = !empty($segments[1]) ? $segments[1] : 'index';
$params = array_slice($segments, 2);


$controllerFile = "app/controllers/{$controllerName}.php";

if (file_exists($controllerFile)) {
    require_once $controllerFile;

    $controller = new $controllerName();
    
    if (method_exists($controller, $method)) {
        call_user_func_array([$controller, $method], $params);
    } else {
        require_once 'app/views/errors/404.php';
    }
} else {
    require_once 'app/views/errors/404.php';
}
