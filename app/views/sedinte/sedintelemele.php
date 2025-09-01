<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'psiholog') { header('Location: ' . BASE_URL . 'dashboard'); exit; }
include_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../templates/meniu.php';
?>
<div class="dashboard-container">
    <?php if (file_exists(__DIR__ . '/../templates/sidebar.php')) include __DIR__ . '/../templates/sidebar.php'; ?>
    <div class="dashboard-content">
        <h1>Ședințele mele</h1>
        <?php if (!empty($_SESSION['error'])): ?>
            <p style="color:red;"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
        <?php endif; ?>
        <?php if (!empty($sedinte)): ?>
            <table border="1" cellpadding="6" cellspacing="0" style="width:100%; border-collapse:collapse;">
                <thead>
                <tr>
                    <th style="text-align:left;">ID</th>
                    <th style="text-align:left;">Data și ora</th>
                    <th style="text-align:left;">Client</th>
                    <th style="text-align:left;">Contract</th>
                    <th style="text-align:left;">Serviciu</th>
                    <th style="text-align:left;">Notițe</th>
                    <th style="text-align:left;">Sumă / Acțiuni</th>
                    <th style="text-align:left;">Status</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($sedinte as $s): ?>
                    <tr>
                        <td><?= (int)$s['ID_Sedinta'] ?></td>
                        <td>
                            <form method="post" action="<?= BASE_URL ?>sedinte/actualizeazaData" style="display:flex; flex-direction:column; gap:8px; max-width:220px;">
                                <input type="hidden" name="sedinta_id" value="<?= (int)$s['ID_Sedinta'] ?>">
                                <input type="datetime-local" name="data_ora" value="<?= htmlspecialchars(str_replace(' ', 'T', substr($s['DataOra'] ?? '', 0, 16))) ?>">
                                <button type="submit">Salvează data</button>
                            </form>
                        </td>
                        <td><?= htmlspecialchars(trim(($s['NumeClient'] ?? '').' '.($s['PrenumeClient'] ?? ''))) ?></td>
                        <td><?= htmlspecialchars('#'.($s['ID_Contract'] ?? '')) ?></td>
                        <td><?= htmlspecialchars($s['Serviciu'] ?? '') ?></td>
                        <td>
                            <form method="post" action="<?= BASE_URL ?>sedinte/actualizeazaNotite" style="display:flex; flex-direction:column; gap:8px; max-width:300px;">
                                <input type="hidden" name="sedinta_id" value="<?= (int)$s['ID_Sedinta'] ?>">
                                <textarea name="notite" rows="4"><?= htmlspecialchars($s['Notite'] ?? '') ?></textarea>
                                <button type="submit">Salvează notițe</button>
                            </form>
                        </td>
                        <td>
                            <?php if (empty($s['FacturaEmisa'])): ?>
                                <form method="post" action="<?= BASE_URL ?>sedinte/operatiuni" style="display:flex; flex-direction:column; gap:8px; max-width:260px;">
                                    <input type="hidden" name="sedinta_id" value="<?= (int)$s['ID_Sedinta'] ?>">
                                    <input type="number" name="suma" step="0.01" min="0" placeholder="Suma" value="<?= htmlspecialchars($s['SumaPlata'] ?? ($s['SumaFactura'] ?? '')) ?>">
                                    <select name="tip_plata">
                                        <option value="">Metodă de plată</option>
                                        <?php foreach ($tipuri as $tp): ?>
                                            <option value="<?= (int)$tp['ID_TipPlata'] ?>" <?= (!empty($s['ID_TipPlata']) && (int)$s['ID_TipPlata']===(int)$tp['ID_TipPlata']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($tp['Cod']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                        <button type="submit" name="do" value="factura">Emite factură</button>
                                        <button type="submit" name="do" value="plata">Marchează plătită</button>
                                    </div>
                                </form>
                            <?php else: ?>
                                <div style="display:flex; flex-direction:column; gap:8px; max-width:260px;">
                                    <div>
                                        <strong>Sumă:</strong>
                                        <input type="number" step="0.01" min="0" value="<?= htmlspecialchars($s['SumaPlata'] ?? ($s['SumaFactura'] ?? '')) ?>" disabled>
                                    </div>

                                    <?php if (empty($s['Platita'])): ?>
                                        <form method="post" action="<?= BASE_URL ?>sedinte/operatiuni" style="display:flex; flex-direction:column; gap:8px;">
                                            <input type="hidden" name="sedinta_id" value="<?= (int)$s['ID_Sedinta'] ?>">
                                            <input type="hidden" name="suma" value="<?= htmlspecialchars($s['SumaPlata'] ?? ($s['SumaFactura'] ?? '')) ?>">
                                            <select name="tip_plata" required>
                                                <option value="">Metodă de plată</option>
                                                <?php foreach ($tipuri as $tp): ?>
                                                    <option value="<?= (int)$tp['ID_TipPlata'] ?>" <?= (!empty($s['ID_TipPlata']) && (int)$s['ID_TipPlata']===(int)$tp['ID_TipPlata']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($tp['Cod']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="submit" name="do" value="plata">Marchează plătită</button>
                                        </form>
                                    <?php endif; ?>

                                    <form method="get" action="<?= BASE_URL ?>facturi/descarca/<?= (int)$s['ID_Sedinta'] ?>">
                                        <button type="submit">Descarcă factura</button>
                                    </form>

                                    <?php if (!empty($s['Platita'])): ?>
                                        <form method="get" action="<?= BASE_URL ?>plati/descarca-chitanta/<?= (int)$s['ID_Sedinta'] ?>">
                                            <button type="submit">Descarcă chitanța</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div>Factura: <?= !empty($s['FacturaEmisa']) ? 'Emisă' : '—' ?></div>
                            <div>Plată: <?= !empty($s['Platita']) ? 'Plătită' : '—' ?></div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nu există ședințe.</p>
        <?php endif; ?>
    </div>
</div>
<?php include_once __DIR__ . '/../templates/footer.php'; ?>
