<?php
include_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../templates/meniu.php';
?>

<h1><?= isset($title) ? $title : 'Despre Noi' ?></h1>
<p><?= isset($content) ? $content : 'Aceasta este pagina "Despre Noi".' ?></p>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>
