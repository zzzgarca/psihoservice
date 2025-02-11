<?php include_once __DIR__ . '/../templates/header.php'; ?>

<h1>Înregistrare</h1>

<?php if (!empty($errorMessage)): ?>
    <p style="color: red;"><?= htmlspecialchars($errorMessage); ?></p>
<?php endif; ?>

<form action="<?= BASE_URL ?>register/submit" method="post">
    <label for="nume">Nume:</label>
    <input type="text" id="nume" name="nume" required>

    <label for="prenume">Prenume:</label>
    <input type="text" id="prenume" name="prenume" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Parolă:</label>
    <input type="password" id="password" name="password" required>

    <label for="rol">Alege rolul:</label>
    <select id="rol" name="rol" required>
        <option value="client">Client</option>
        <option value="psiholog">Psiholog (necesită aprobare)</option>
        <option value="administrator">Administrator (necesită aprobare)</option>
    </select>

    <button type="submit">Înregistrare</button>
</form>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>
