<?php
$user = 'root';
$password = '';
$db = 'camera_store';
$host = 'localhost';
$port = 3306;

$dsn = "mysql:host=$host;port=$port;dbname=$db";
try {
    $conn = new PDO($dsn, $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Помилка підключення: " . $e->getMessage();
}
?>
