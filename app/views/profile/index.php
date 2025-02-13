<?php
include_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../templates/meniu.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'login');
    exit;
}

// Load the correct sidebar based on user role
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
?>

<div class="dashboard-container">
    <?php include_once __DIR__ . '/../templates/sidebar.php';?>

    <div class="dashboard-content">
        <h1>Dashboard</h1>
        <h2>Profilul tău</h2>
        <p>Email: <?= htmlspecialchars($email); ?></p>
        <p>Rol: <?= htmlspecialchars($role); ?></p>

        <?php if (!empty($successMessage)) : ?>
            <p style="color: green;"><?= htmlspecialchars($successMessage); ?></p>
        <?php elseif (!empty($errorMessage)) : ?>
            <p style="color: red;"><?= htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>profile/update" method="post">
            <label for="email">Schimbă email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email); ?>" required>

            <label for="password">Schimbă parola (opțional):</label>
            <input type="password" id="password" name="password">

            <button type="submit">Salvează modificările</button>
        </form>
    </div>
</div>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>
</body>
</html>
