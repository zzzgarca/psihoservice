<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

spl_autoload_register(function ($class) {
    // Adăugăm un slash (/) după __DIR__ și unul înainte de 'app'
    $path = __DIR__ . '/app/controllers/' . $class . '.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        // Opțional: Logica pentru a verifica și în alte locații, dacă este cazul
    }
});

require_once __DIR__ . '/../config/config.php';

try {
    // Creează o instanță PDO
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $db = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // Manejarea erorilor de conectare
    error_log($e->getMessage());
    exit('Conexiunea la baza de date a eșuat!');
}