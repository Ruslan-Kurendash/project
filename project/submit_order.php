<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $customer_name = $_POST['customer_name'];
    $customer_surname = $_POST['customer_surname'];
    $delivery_address = $_POST['delivery_address'];

    try {
        $conn->beginTransaction();

        // Додавання замовлення
        $stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_surname, delivery_address) VALUES (?, ?, ?)");
        $stmt->execute([$customer_name, $customer_surname, $delivery_address]);
        $order_id = $conn->lastInsertId();

        // Додавання товару до замовлення
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id) VALUES (?, ?)");
        $stmt->execute([$order_id, $product_id]);

        $conn->commit();
        echo "Замовлення успішно оформлене!";
    } catch(PDOException $e) {
        $conn->rollBack();
        echo "Помилка замовлення: " . $e->getMessage();
    }
}
?>
