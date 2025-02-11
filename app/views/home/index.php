<?php
include_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../templates/meniu.php';
?>

<h1><?= isset($data['title']) ? htmlspecialchars($data['title']) : 'Titlu implicit' ?></h1>
<h3><?= isset($data['content']) ? htmlspecialchars($data['content']) : 'Conținut indisponibil.' ?></h3>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>
