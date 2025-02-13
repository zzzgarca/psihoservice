<?php
include_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../templates/meniu.php';
?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrator') {
    header('Location: ' . BASE_URL . 'dashboard');
    exit;
}

include_once __DIR__ . '/../templates/header.php';
?>

<div class="dashboard-container">
    <?php include_once __DIR__ . '/../templates/sidebar.php'; ?>

    <div class="dashboard-content">
        <h1>Bun venit, <?= htmlspecialchars($_SESSION['email']); ?>!</h1>
        <p>Aici poți verifica contracte, genera facturi și administra plățile.</p>
    </div>
</div>

<ul>
    <li><a href="<?= BASE_URL; ?>admin/aproba">Aprobă utilizatori</a></li>
    <li><a href="<?= BASE_URL; ?>contracte">Verifică contractele</a></li>
    <li><a href="<?= BASE_URL; ?>facturi">Generează facturi</a></li>
    <li><a href="<?= BASE_URL; ?>plati">Administrează plățile</a></li>
</ul>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>
