<?php

require_once 'app/init.php'; // Inițializează aplicația (încarcă clasele esențiale)


$baseUrl = 'psihoservice'; // Ajustează în funcție de necesități

// Elimină baza URL-ului din calea URI
$uri = $_SERVER['REQUEST_URI'];
if (!empty($baseUrl)) {
    $uri = preg_replace("#^/$baseUrl#", '', $uri);
}

$uri = trim($uri, '/');
$segments = explode('/', $uri);

// Procesează segmentele pentru a determina controller-ul, metoda și parametrii
$controllerName = !empty($segments[0]) ? ucfirst($segments[0]) . 'Controller' : 'HomeController';
$method = !empty($segments[1]) ? $segments[1] : 'index';
$params = array_slice($segments, 2);

// $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') ? 'https' : 'http';
// $currentUrl = $https . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
// $urlPath = trim($_SERVER['REQUEST_URI'], '/');
// $url = explode('/', filter_var(rtrim($currentUrl, '/'), FILTER_SANITIZE_URL));

// // Determină controller-ul, metoda, și parametrii din URL
// $controllerName = (isset($url[0]) && $url[0] != '') ? ucfirst($url[0]) . 'Controller' : 'HomeController';
// $method = isset($url[1]) ? $url[1] : 'index';
// $params = array_slice($url, 2);

// Construiește numele fișierului controller-ului
$controllerFile = "app/controllers/{$controllerName}.php";


// echo "URL-ul actual este: $currentUrl<br>";
// echo "URLPath este: $urlPath<br>";
// echo "URL: ", isset($url[0]) ? $url[0] : 'Home', "<br>";
// echo "Controller: $controllerName<br>";
// echo "Controller File: $controllerFile<br>";
// echo "Method: $method<br>";
// echo "Params: ";
// print_r($params);
// die(); // Oprire temporară pentru a vedea output-ul


if (file_exists($controllerFile)) {
    require_once $controllerFile;

    $controller = new $controllerName();
    if (method_exists($controller, $method)) {
        call_user_func_array([$controller, $method], $params);
    } else {
        // Metoda nu există
        echo "Metoda $method nu există în controller-ul $controllerName.";
    }
} else {
    // Controller-ul nu există
    echo "Controller-ul $controllerName nu există.";
}
