<?php
global $pdo;
require_once 'config/config.php';

try {
    $stmt = $pdo->query("SELECT COUNT(*) AS numar_utilizatori FROM Users");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Conexiunea este funcțională! Număr utilizatori: " . $row['numar_utilizatori'];
} catch (Exception $e) {
    echo "Eroare la interogare: " . $e->getMessage();
}
?>
