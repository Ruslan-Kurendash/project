<?php
// Перевірка чи користувач має право доступу до сторінки
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    // Якщо користувач не авторизований як адміністратор, перенаправити його на іншу сторінку
    header("Location: login.php"); // Замініть "login.php" на URL вашої сторінки для входу
    exit;
}

// Після перевірки прав доступу, продовжуємо виконання коду
include 'db.php';

// Отримати дані з форми
$productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$productName = $_POST['productName'];
$productDescription = $_POST['productDescription'];
$productPrice = $_POST['productPrice'];
$productImageUrl = $_POST['productImageUrl'];
$category = $_POST['category'];

// Використання підготовлених запитів для оновлення товару
$sql = "UPDATE products SET name = :name, description = :description, price = :price, image_url = :image_url, category_id = :category_id WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([
    ':name' => $productName,
    ':description' => $productDescription,
    ':price' => $productPrice,
    ':image_url' => $productImageUrl,
    ':category_id' => $category,
    ':id' => $productId
]);

// Перенаправлення назад на сторінку редагування або на сторінку зі списком товарів
header("Location: catalog.php");
exit();
?>
