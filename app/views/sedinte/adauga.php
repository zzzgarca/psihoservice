<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'psiholog') { header('Location: ' . BASE_URL . 'dashboard'); exit; }
include_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../templates/meniu.php';
?>
<div class="dashboard-container">
    <?php if (file_exists(__DIR__ . '/../templates/sidebar.php')) include __DIR__ . '/../templates/sidebar.php'; ?>
    <div class="dashboard-content">
        <h1>Programează ședință</h1>
        <?php if (!empty($_SESSION['error'])): ?>
            <p style="color:red;"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
        <?php endif; ?>
        <form method="post" action="<?= BASE_URL ?>sedinte/salveaza">
            <label>Pacient</label>
            <select name="client_id" required>
                <option value="">Alege pacient</option>
                <?php foreach ($clienti as $c): ?>
                    <option value="<?= (int)$c['ID_User'] ?>"><?= htmlspecialchars(trim(($c['Nume'] ?? '').' '.($c['Prenume'] ?? ''))) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Contract</label>
            <select name="contract_id" required>
                <option value="">Alege contract</option>
                <?php foreach ($contracte as $ct): ?>
                    <option value="<?= (int)$ct['ID_Contract'] ?>">#<?= (int)$ct['ID_Contract'] ?> — <?= htmlspecialchars(trim(($ct['Nume'] ?? '').' '.($ct['Prenume'] ?? ''))) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Serviciu</label>
            <select name="serviciu_id" required>
                <option value="">Alege serviciu</option>
                <?php foreach ($servicii as $s): ?>
                    <option value="<?= (int)$s['ID_Serviciu'] ?>"><?= htmlspecialchars($s['Tip']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Data și ora</label>
            <input type="datetime-local" name="data_ora" required>

            <label>Notițe</label>
            <textarea name="notite" rows="4"></textarea>

            <button type="submit">Salvează</button>
        </form>
    </div>
</div>
<?php include_once __DIR__ . '/../templates/footer.php'; ?>
