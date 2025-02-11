<?php
include_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../templates/meniu.php';
?>

<h1><?= isset($title) ? $title : 'Contact' ?></h1>
<p><?= isset($content) ? $content : 'Trimiteți-ne un mesaj.' ?></p>


<?php if (!empty($successMessage)) : ?>
    <p style="color: green;"><?= htmlspecialchars($successMessage) ?></p>
<?php elseif (!empty($errorMessage)) : ?>
    <p style="color: red;"><?= htmlspecialchars($errorMessage) ?></p>
<?php endif; ?>


<form action="<?= BASE_URL ?>contact/sendMessage" method="post">
    <label for="nume">Nume:</label>
    <input type="text" id="nume" name="nume" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="subiect">Subiect:</label>
    <input type="text" id="subiect" name="subiect" required>

    <label for="mesaj">Mesaj:</label>
    <textarea id="mesaj" name="mesaj" required maxlength="1000"></textarea>

    <!-- Întrebare anti-spam -->
    <label for="captcha">Cât fac 3 + 4?</label>
    <input type="text" id="captcha" name="captcha" required>

    <button type="submit">Trimite</button>
</form>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>
</body>
</html>
