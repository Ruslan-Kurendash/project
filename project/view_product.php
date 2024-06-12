<?php
include 'db.php';

session_start();

// Отримати product_id з URL
$product_id = isset($_GET['id']) ? $_GET['id'] : die('Product ID is required.');

// Перевірка, чи користувач увійшов у систему
$isUserLoggedIn = isset($_SESSION['user_id']);

try {
    // Підготувати і виконати запит для вибірки деталей продукту
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch();

    if (!$product) {
        die('Product not found.');
    }
} catch (PDOException $e) {
    die("Помилка вибірки: " . $e->getMessage());
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
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            position: fixed;
            width: 100%;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .left-box nav {
            display: flex;
            align-items: center;
        }

        .logo-container img {
            max-height: 70px;
            margin-right: 20px;
        }

        .nav-links {
            display: flex;
            gap: 20px;
        }

        .nav-link {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
        }

        .nav-link:hover {
            text-decoration: underline;
        }

        .right-box {
            text-align: right;
            font-size: 16px;
            font-weight: 400;
        }

        .admin-login {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
        }

        .admin-login:hover {
            text-decoration: underline;
        }

        .product-container {
            margin-top: 100px;
            padding: 20px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .product-header {
            display: flex;
            align-items: center;
        }

        .product-image {
            max-width: 300px;
            height: auto;
            margin-right: 20px;
            border-radius: 10px;
        }

        .product-details {
            flex: 1;
        }

        .product-name {
            font-size: 2em;
            font-weight: bold;
            color: #333;
        }

        .product-price {
            font-size: 1.5em;
            color: #e60000;
            margin-top: 10px;
        }

        .buy-button {
            margin-top: 20px;
        }

        .product-delivery,
        .product-description {
            color: #000 !important; /* Додаємо важливий селектор */
            margin-top: 20px;
        }

        .product-delivery h4,
        .product-description h4 {
            color: #000 !important; /* Додаємо важливий селектор */
        }

        .back-to-catalog {
            margin-top: 20px;
        }
    </style>
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
        <a href="cart.php" class="btn btn-primary cart-button">Кошик</a>
        </div>
    </header>

    <main>
        <div class="container product-container">
            <div class="product-header">
                <img class="product-image" src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Product Image">
                <div class="product-details">
                    <h1 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h1>
                    <p class="product-price">Ціна: <?php echo htmlspecialchars($product['price']); ?> грн</p>
                    <a href="order.php?product_id=<?php echo $product_id; ?>" class="btn btn-success buy-button">Купити</a>
                    <div class="product-delivery">
                        <h4>Види доставок:</h4>
                        <ul>
                            <li>Нова Пошта</li>
                            <li>Укрпошта</li>
                            <li>Кур'єрська доставка</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="product-description">
                <h4>Опис товару:</h4>
                <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            </div>
            <a href="catalog.php" class="btn btn-primary back-to-catalog">Повернутися до каталогу</a>
        </div>
    </main>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>

</html>
