<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header('Location: ' . BASE_URL . 'dashboard');
    exit;
}
$displayName = trim(($_SESSION['nume'] ?? '') . ' ' . ($_SESSION['prenume'] ?? ''));
if ($displayName === '') $displayName = $_SESSION['email'] ?? '';
include_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../templates/meniu.php';
?>
<div class="dashboard-container">
    <?php if (file_exists(__DIR__ . '/../templates/sidebar.php')) include __DIR__ . '/../templates/sidebar.php'; ?>
    <div class="dashboard-content">
        <h1>Bun venit, <?= htmlspecialchars($displayName) ?>!</h1>
        <p>Aici vezi programările, facturile și materialele recomandate.</p>
    </div>
</div>
<?php include_once __DIR__ . '/../templates/footer.php'; ?>
