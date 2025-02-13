<?php
include_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../templates/meniu.php';
?>

<h1><?= isset($title) ? $title : 'Autentificare' ?></h1>
<h3><?= isset($content) ? $content : 'Introduceți datele pentru a vă autentifica.' ?></h3>


<?php if (!empty($errorMessage)) : ?>
    <p style="color: red;"><?= htmlspecialchars($errorMessage) ?></p>
<?php endif; ?>

<form action="<?= BASE_URL ?>login/authenticate" method="post">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Parolă:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Logare</button>
</form>

<p>Nu ai cont? <a href="<?= BASE_URL; ?>register">Înregistrează-te</a></p>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>
</body>
</html>
