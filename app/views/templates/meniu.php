<nav>
    <ul>
        <li><a href="<?php echo BASE_URL; ?>">Home</a></li>
        <li><a href="<?php echo BASE_URL; ?>about">Despre Noi</a></li>
        <li><a href="<?php echo BASE_URL; ?>services">Servicii</a></li>
        <li><a href="<?php echo BASE_URL; ?>contact">Contact</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="<?php echo BASE_URL; ?>profile">Profilul meu</a></li>
            <li><a href="<?php echo BASE_URL; ?>login/logout">Logout</a></li>
        <?php else: ?>
            <li><a href="<?php echo BASE_URL; ?>login">Logare</a></li>
        <?php endif; ?>
    </ul>
</nav>