<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
?>

<div class="sidebar">
    <h3>Dashboard</h3>
    <ul>
        <li><a href="<?= BASE_URL; ?>dashboard">Home Dashboard</a></li>

        <?php if ($role === 'psiholog'): ?>
            <li><a href="<?= BASE_URL; ?>sedinte">Programare Ședințe</a></li>
            <li><a href="<?= BASE_URL; ?>evaluari">Evaluări</a></li>
            <li><a href="<?= BASE_URL; ?>rapoarte">Rapoarte</a></li>

        <?php elseif ($role === 'client'): ?>
            <li><a href="<?= BASE_URL; ?>chestionare">Completează Chestionar</a></li>
            <li><a href="<?= BASE_URL; ?>materiale">Materiale Terapeutice</a></li>
            <li><a href="<?= BASE_URL; ?>programari">Consultă Programările</a></li>

        <?php elseif ($role === 'administrator'): ?>
            <li><a href="<?= BASE_URL; ?>admin/contracte">Verifică Contractele</a></li>
            <li><a href="<?= BASE_URL; ?>admin/facturi">Generează Facturi</a></li>
            <li><a href="<?= BASE_URL; ?>admin/plati">Administrează Plățile</a></li>
            <li><a href="<?= BASE_URL; ?>admin/aproba">Aproba Utulizatori</a></li>
        <?php endif; ?>

        <li><a href="<?= BASE_URL; ?>profile">Profilul Meu</a></li>
        <li><a href="<?= BASE_URL; ?>login/logout">Logout</a></li>
    </ul>
</div>
