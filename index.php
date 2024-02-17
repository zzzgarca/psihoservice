<?php

require_once 'app/init.php'; // Inițializează aplicația (încarcă clasele esențiale)

$url = isset($_GET['url']) ? explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL)) : [];

// Determină controller-ul, metoda, și parametrii din URL
$controllerName = (isset($url[0]) && $url[0] != '') ? ucfirst($url[0]) : 'HomeController';
$method = isset($url[1]) ? $url[1] : 'index';
$params = array_slice($url, 2);

// Construiește numele fișierului controller-ului
$controllerFile = "app/controllers/{$controllerName}.php";

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
