<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: admin_login.php');
    exit();
}

include 'db.php';

try {
    // Виведення замовлень
    $orders = $conn->query("SELECT * FROM orders");
} catch (PDOException $e) {
    echo "Помилка бази даних: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель адміністратора</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Панель адміністратора</h2>
        <a href="logout.php" class="btn btn-danger">Вийти</a>
    </div>

    <h3>Замовлення</h3>
    <?php foreach ($orders as $order): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($order['customer_name'] . " " . $order['customer_surname']); ?></h5>
                <p class="card-text">Адреса: <?php echo htmlspecialchars($order['delivery_address']); ?></p>
                <p class="card-text">Дата замовлення: <?php echo htmlspecialchars($order['order_date']); ?></p>
            </div>
        </div>
    <?php endforeach; ?>

    <h3>Додати новий товар</h3>
    <form action="add_product.php" method="post">
        <div class="form-group">
            <label for="productName">Назва товару:</label>
            <input type="text" class="form-control" id="productName" name="productName" required>
        </div>
        <div class="form-group">
            <label for="productDescription">Опис товару:</label>
            <textarea class="form-control" id="productDescription" name="productDescription" required></textarea>
        </div>
        <div class="form-group">
            <label for="productPrice">Ціна:</label>
            <input type="number" class="form-control" id="productPrice" name="productPrice" required>
        </div>
        <div class="form-group">
            <label for="productImageUrl">URL зображення:</label>
            <input type="text" class="form-control" id="productImageUrl" name="productImageUrl">
        </div>
        <div class="form-group">
            <label for="category">Категорія:</label>
            <select class="form-control" id="category" name="category" required>
                <?php
                // Отримання списку категорій з бази даних
                $categories = $conn->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

                // Виведення опцій для вибору категорії
                foreach ($categories as $category) {
                    echo "<option value=\"{$category['id']}\">" . htmlspecialchars($category['name']) . "</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Додати товар</button>
    </form>
</div>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
