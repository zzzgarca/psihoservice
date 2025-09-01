<?php
require_once 'app/init.php';

$reqPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$basePath = trim(parse_url(BASE_URL, PHP_URL_PATH) ?? '', '/');
$path = trim($reqPath, '/');
if ($basePath && str_starts_with($path, $basePath)) {
    $path = trim(substr($path, strlen($basePath)), '/');
}

if ($path === '' || $path === 'index.php') {
    $segments = [];
} else {
    $segments = array_values(array_filter(explode('/', $path), 'strlen'));
}

$controllerRaw = $segments[0] ?? 'home';
$method = $segments[1] ?? 'index';
$params = array_slice($segments, 2);

$controllerSafe = preg_replace('~[^a-z0-9_-]~i', '', strtolower($controllerRaw));
$method = preg_replace('~[^a-z0-9_-]~i', '', strtolower($method));
$controllerName = ucfirst($controllerSafe) . 'Controller';
$controllerFile = "app/controllers/{$controllerName}.php";

if (is_file($controllerFile)) {
    require_once $controllerFile;
    if (class_exists($controllerName)) {
        $controller = new $controllerName();
        if ($method && method_exists($controller, $method)) {
            call_user_func_array([$controller, $method], $params);
            exit;
        }
    }
}

http_response_code(404);
require_once 'app/views/errors/404.php';
