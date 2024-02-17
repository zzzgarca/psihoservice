<?php 
include_once __DIR__ . '/../templates/header.php'; // Ajustează calea dacă este necesar
?>

    <?php require_once __DIR__ . '/../templates/meniu.php'; ?>
    <h1><?php echo $data['title']; ?></h1>
    <h3><?php echo $data['content']; ?></h3>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>
</body>
</html>
