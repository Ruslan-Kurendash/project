<?php
include 'db.php'; // Підключаємося до бази даних

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['productName'];
    $description = $_POST['productDescription'];
    $price = $_POST['productPrice'];
    $imageUrl = $_POST['productImageUrl'];

    try {
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, image_url) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $imageUrl]);
        echo "<p>Товар додано! <a href='admin_panel.php'>Повернутися до панелі адміністратора</a></p>";
    } catch(PDOException $e) {
        echo "Помилка при додаванні товару: " . $e->getMessage();
    }
} else {
    echo "<p>Невірний запит. <a href='admin_panel.php'>Повернутися до панелі адміністратора</a></p>";
}
?>
