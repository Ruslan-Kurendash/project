<?php
session_start();
include 'db.php';

if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Отримати інформацію про товар з бази даних
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->bindParam(':id', $productId);
    $stmt->execute();
    $product = $stmt->fetch();

    if ($product) {
        // Додати товар у кошик
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += 1;
        } else {
            $_SESSION['cart'][$productId] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'image_url' => $product['image_url'],
                'quantity' => 1
            ];
        }

        // Встановити сесійне повідомлення про успішне додавання
        $_SESSION['success_message'] = 'Товар був доданий у кошик!';
    }

    // Перенаправити назад до каталогу
    header('Location: catalog.php');
    exit();
} else {
    // Перенаправити назад до каталогу у випадку помилки
    header('Location: catalog.php');
    exit();
}
?>
