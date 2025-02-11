<?php
include_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../templates/meniu.php';
?>

<h1><?= isset($title) ? $title : 'Serviciile Noastre' ?></h1>
<p><?= isset($content) ? $content : 'Aici sunt prezentate serviciile noastre.' ?></p>


<?php if (!empty($services)) : ?>
    <ul>
        <?php foreach ($services as $service) : ?>
            <li><?= htmlspecialchars($service['Tip']) ?></li>
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <p>Momentan nu sunt disponibile servicii.</p>
<?php endif; ?>


<?php include_once __DIR__ . '/../templates/footer.php'; ?>
</body>
</html>
