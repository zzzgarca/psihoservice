<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title><?php echo $data['title']; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
</head>
<body>
    <?php require_once __DIR__ . '/../meniu.php'; ?>
    <h1><?php echo $data['content']; ?></h1>
</body>
</html>

