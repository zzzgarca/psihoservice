<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'psiholog') { header('Location: ' . BASE_URL . 'dashboard'); exit; }
include_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../templates/meniu.php';
?>
<div class="dashboard-container">
    <?php if (file_exists(__DIR__ . '/../templates/sidebar.php')) include __DIR__ . '/../templates/sidebar.php'; ?>
    <div class="dashboard-content">
        <h1>Contractele mele</h1>
        <?php if (!empty($_SESSION['error'])): ?>
            <p style="color:red;"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
        <?php endif; ?>
        <p><a href="<?= BASE_URL ?>contracte/adauga">Adaugă contract</a></p>
        <?php if (!empty($contracte)): ?>
            <table border="1" cellpadding="6" cellspacing="0">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Serviciu</th>
                    <th>Psiholog</th>
                    <th>Client</th>
                    <th>Data început</th>
                    <th>Data sfârșit</th>
                    <th>Detalii</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($contracte as $c): ?>
                    <tr>
                        <td><?= (int)$c['ID_Contract'] ?></td>
                        <td><?= htmlspecialchars($c['Serviciu'] ?? '') ?></td>
                        <td><?= htmlspecialchars(trim(($c['NumePsiholog'] ?? '').' '.($c['PrenumePsiholog'] ?? ''))) ?></td>
                        <td><?= htmlspecialchars(trim(($c['NumeClient'] ?? '').' '.($c['PrenumeClient'] ?? ''))) ?></td>
                        <td><?= htmlspecialchars($c['DataInceput'] ?? '') ?></td>
                        <td>
                            <form method="post" action="<?= BASE_URL ?>contracte/actualizeazaSfarsit" style="display:flex; gap:6px; align-items:center;">
                                <input type="date" name="data_sfarsit" value="<?= htmlspecialchars($c['DataSfarsit'] ?? '') ?>">
                                <input type="hidden" name="contract_id" value="<?= (int)$c['ID_Contract'] ?>">
                                <button type="submit">Salvează</button>
                            </form>
                        </td>
                        <td><?= nl2br(htmlspecialchars($c['Detalii'] ?? '')) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nu ai încă niciun contract.</p>
        <?php endif; ?>
    </div>
</div>
<?php include_once __DIR__ . '/../templates/footer.php'; ?>
