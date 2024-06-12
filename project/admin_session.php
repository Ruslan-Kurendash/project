<?php
session_start();
$_SESSION['admin'] = true;
header("Location: catalog.php"); // Перенаправлення на каталог товарів після входу
?>
