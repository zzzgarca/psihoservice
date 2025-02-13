<?php
include_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../templates/meniu.php';
?>
<h1>Pagina nu a fost găsită</h1>
<p>Ne pare rău, dar pagina pe care o cauți nu există.</p>
<a href="<?= BASE_URL ?>">Înapoi la pagina principală</a>
<?php require_once 'app/views/templates/footer.php'; ?>
