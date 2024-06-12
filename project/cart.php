<?php
session_start();

// Функція для видалення товару з кошика
if (isset($_POST['remove_from_cart'])) {
    $productId = $_POST['product_id'];
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Перевірка, чи користувач увійшов у систему
$isUserLoggedIn = isset($_SESSION['user_id']);

// Функція для оновлення кількості товару в кошику
if (isset($_POST['update_quantity'])) {
    $productId = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    if (isset($_SESSION['cart'][$productId]) && $quantity > 0) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
    }
}
?>

<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="images/logo.jpg">
    <link rel="stylesheet" type="text/css" href="css/common.css">
    <link rel="stylesheet" type="text/css" href="css/header.css">
    <link rel="stylesheet" type="text/css" href="css/catalog.css">
    <link rel="stylesheet" type="text/css" href="css/cart.css">
    <title>Кошик</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<header>
    <div class="left-box">
        <nav>
            <a href="index.html">
                <div class="logo-container">
                    <img src="images/logo.jpg" alt="Logo" />
                </div>
            </a>
            <div class="nav-links">
                <a class="nav-link" href="index.html">Головна</a>
                <a class="nav-link" href="contacts.html">Контакти</a>
                <a class="nav-link" href="aboutus.html">Про магазин</a>
                <a class="nav-link" href="author.html">Про мене</a>
            </div>
        </nav>
    </div>
    <div class="right-box">
    <?php if ($isAdmin): ?>
            <a href="admin_panel.php" class="btn btn-warning">Admin</a>
        <?php endif; ?>
        <?php if ($isUserLoggedIn): ?>
            <span>Привіт, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="logout.php" class="btn btn-primary">Вихід</a>
        <?php else: ?>
            <a href="login.php" class="btn btn-primary">Вхід</a>
        <?php endif; ?>
</header>

<main>
    <div class="container mt-4">
        <h2 style="color: red; margin-top: 60px;">Кошик</h2>
        <div class="cart-container">
            <?php if (!empty($_SESSION['cart'])): ?>
                <?php foreach ($_SESSION['cart'] as $productId => $item): ?>
                    <div class="cart-item">
    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="Product Image" class="cart-item-image">
    <div class="cart-item-details">
        <div class="cart-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
        <div class="cart-item-price"><?php echo htmlspecialchars(number_format($item['price'], 2)); ?> грн</div>
        <div class="cart-item-quantity-container">
            <form action="cart.php" method="post" class="quantity-form">
                <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                <input type="hidden" name="quantity" value="<?php echo $item['quantity'] - 1; ?>">
                <button type="submit" name="update_quantity" class="quantity-button">-</button>
            </form>
            <input type="text" name="quantity_display" value="<?php echo htmlspecialchars($item['quantity']); ?>" class="quantity-input" readonly>
            <form action="cart.php" method="post" class="quantity-form">
                <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                <input type="hidden" name="quantity" value="<?php echo $item['quantity'] + 1; ?>">
                <button type="submit" name="update_quantity" class="quantity-button">+</button>
            </form>
        </div>
    </div>
    <form action="cart.php" method="post">
        <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
        <button type="submit" name="remove_from_cart" class="remove-button">Видалити</button>
    </form>
</div>
                <?php endforeach; ?>
                <div class="checkout-container">
                    <a href="order.php" class="btn btn-success">Оформити замовлення</a>
                </div>
            <?php else: ?>
                <p>Ваш кошик порожній.</p>
            <?php endif; ?>
        </div>
    </div>
</main>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
