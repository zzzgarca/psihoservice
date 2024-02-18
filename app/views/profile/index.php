<?php
// În app/views/profile/index.php
include_once __DIR__ . '/../templates/header.php'; // Ajustează calea dacă este necesar
?>

    <?php require_once __DIR__ . '/../templates/meniu.php'; ?>
    
    <h1>Salut, <?php echo htmlspecialchars($data['name']); ?>!</h1>
    <p>Rolul cu care te-ai logat este: <?php echo htmlspecialchars($data['role']); ?></p>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>
</body>
</html>
