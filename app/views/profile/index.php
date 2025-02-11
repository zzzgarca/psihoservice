<?php
include_once __DIR__ . '/../templates/header.php';
?>

<h1>Profilul tău</h1>
<p>Email: <?= htmlspecialchars($email) ?></p>
<p>Rol: <?= htmlspecialchars($role) ?></p>

<?php if (!empty($successMessage)) : ?>
    <p style="color: green;"><?= htmlspecialchars($successMessage) ?></p>
<?php elseif (!empty($errorMessage)) : ?>
    <p style="color: red;"><?= htmlspecialchars($errorMessage) ?></p>
<?php endif; ?>

<form action="<?= BASE_URL ?>profile/update" method="post">
    <label for="email">Schimbă email:</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

    <label for="password">Schimbă parola (opțional):</label>
    <input type="password" id="password" name="password">

    <button type="submit">Salvează modificările</button>
</form>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>
</body>
</html>
