<?php 
include_once __DIR__ . '/../templates/header.php'; 
?>

    <?php require_once __DIR__ . '/../templates/meniu.php'; ?>
    <h1><?php echo $data['title']; ?></h1>
    <h3><?php echo $data['content']; ?></h3>

    <form action="/logare/authenticate" method="post">
        <label for="username">Nume utilizator:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">Parolă:</label>
        <input type="password" id="password" name="password" required>
        
        <button type="submit">Logare</button>
    </form>


<?php include_once __DIR__ . '/../templates/footer.php'; ?>
</body>
</html>
