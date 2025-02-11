<?php
include_once __DIR__ . '/../templates/header.php'; // Include header-ul
require_once __DIR__ . '/../templates/meniu.php';  // Include meniul
?>

<h1><?= isset($data['title']) ? htmlspecialchars($data['title']) : 'Titlu implicit' ?></h1>
<h3><?= isset($data['content']) ? htmlspecialchars($data['content']) : 'Conținut indisponibil.' ?></h3>

<?php include_once __DIR__ . '/../templates/footer.php'; // Include footer-ul ?>
