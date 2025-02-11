<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav>
    <ul class="nav-menu">
        <li><a href="<?= BASE_URL; ?>">Home</a></li>
        <li><a href="<?= BASE_URL; ?>about">Despre Noi</a></li>
        <li><a href="<?= BASE_URL; ?>services">Servicii</a></li>
        <li><a href="<?= BASE_URL; ?>contact">Contact</a></li>

        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="<?= BASE_URL; ?>dashboard">Dashboard</a></li>
            <li><a href="<?= BASE_URL; ?>profile">Profilul meu</a></li>
            <li><a href="<?= BASE_URL; ?>login/logout">Logout</a></li>
        <?php else: ?>
            <li><a href="<?= BASE_URL; ?>login">Logare</a></li>
        <?php endif; ?>
    </ul>
</nav>
