<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Contul Tău</title>
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    <header>
        <?php include 'meniu.php'; ?>
    </header>
    <main>
        <h1>Contul Tău</h1>
        <form action="process_login.php" method="post">
            Email: <input type="email" name="email" required><br>
            Parola: <input type="password" name="password" required><br>
            <input type="submit" value="Login">
        </form>
        <p>Nu ai un cont? <a href="register.html">Înregistrează-te aici.</a></p>
    </main>
    <footer>
        <!-- Footer-ul tău aici -->
    </footer>
</body>
</html>
