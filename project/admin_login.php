<?php
session_start();

$adminPassword = 'admin'; // Встановіть ваш секретний пароль

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['adminPassword'] === $adminPassword) {
        $_SESSION['admin'] = true;
        header('Location: catalog.php'); // Перенаправити на каталог або будь-яку іншу сторінку
        exit();
    } else {
        $error = "Неправильний пароль";
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вхід для адміністратора</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Вхід для адміністратора</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form action="admin_login.php" method="post">
        <div class="form-group">
            <label for="adminPassword">Пароль:</label>
            <input type="password" class="form-control" id="adminPassword" name="adminPassword" required>
        </div>
        <button type="submit" class="btn btn-primary">Увійти</button>
    </form>
</div>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
