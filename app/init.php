<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/controllers/',
        __DIR__ . '/models/'
    ];

    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            break;
        }
    }
});

require_once __DIR__ . '/../config/config.php';

if (!isset($pdo) || !$pdo) {
    exit('Eroare la conectarea la baza de date!');
}
?>
