<?php
include_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../templates/meniu.php';
global $pdo;


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['successMessage'])) {
    echo '<p style="color: green;">' . htmlspecialchars($_SESSION['successMessage']) . '</p>';
    unset($_SESSION['successMessage']);
}


$stmt = $pdo->query("SELECT * FROM Users WHERE Status = 'pending'");
$pendingUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="dashboard-container">
    <?php include_once __DIR__ . '/../templates/sidebar.php'; ?>

    <div class="dashboard-content">

        <h1>Aprobă utilizatori</h1>


        <?php if (empty($pendingUsers)): ?>
            <p>Nu există utilizatori în așteptare.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Nume</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acțiuni</th>
                </tr>
                <?php foreach ($pendingUsers as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['Nume'] . ' ' . $user['Prenume']) ?></td>
                        <td><?= htmlspecialchars($user['Email']) ?></td>
                        <td><?= htmlspecialchars($user['Rol']) ?></td>
                        <td>
                            <a href="<?= BASE_URL; ?>admin/approve/<?= $user['ID_User']; ?>">Aprobă</a> |
                            <a href="<?= BASE_URL; ?>admin/reject/<?= $user['ID_User']; ?>">Respinge</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>
