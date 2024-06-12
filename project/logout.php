<?php
session_start();
session_destroy(); // Завершити сесію
header("Location: catalog.php"); // Перенаправлення на головну сторінку після виходу
exit();
?>
