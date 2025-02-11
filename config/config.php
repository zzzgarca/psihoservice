<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'silviuma_psihoservice');
define('DB_USER', 'root');
define('DB_PASS', '');

define('BASE_URL', 'http://localhost/psihoservice/');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("Eroare la conectarea la baza de date: " . $e->getMessage());
}
?>
