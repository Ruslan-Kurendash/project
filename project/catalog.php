<?php
session_start();

include 'db.php';

// Перевірка, чи є користувач адміністратором
$isAdmin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;

// Перевірка, чи користувач увійшов у систему
$isUserLoggedIn = isset($_SESSION['user_id']);

// Отримання категорій
$categories = [];
try {
    $stmt = $conn->query("SELECT * FROM categories");
    while ($row = $stmt->fetch()) {
        $categories[] = $row;
    }
} catch (PDOException $e) {
    echo "Помилка вибірки категорій: " . $e->getMessage();
}

// Отримати вибрану категорію
$selected_category = isset($_GET['category']) ? $_GET['category'] : '';

// Отримання пошукового запиту
$searchQuery = isset($_GET['query']) ? trim($_GET['query']) : '';

// Побудувати SQL запит для вибору товарів
$sql = "SELECT * FROM products";
$params = [];

if ($selected_category) {
    $sql .= " WHERE category_id = :category_id";
    $params[':category_id'] = $selected_category;
}

if ($searchQuery) {
    if ($selected_category) {
        $sql .= " AND (name LIKE :query OR description LIKE :query)";
    } else {
        $sql .= " WHERE name LIKE :query OR description LIKE :query";
    }
    $params[':query'] = '%' . $searchQuery . '%';
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
    <title>Каталог товарів</title>
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
<div class="container mt-4">
        <h2 style="color: red; margin-top: 60px;">Каталог товарів</h2>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <!-- Форма пошуку -->
        <form action="catalog.php" method="get" class="mb-4">
            <div class="form-group">
                <label for="searchQuery">Пошук товарів:</label>
                <input type="text" class="form-control" id="searchQuery" name="query" placeholder="Введіть назву товару або ключове слово" value="<?php echo htmlspecialchars($searchQuery); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Шукати</button>
        </form>
        
        <div class="filter-container">
            <form action="catalog.php" method="get">
                <label for="category">Виберіть категорію:</label>
                <select name="category" id="category" class="form-control" style="display: inline-block; width: auto;">
                    <option value="">Усі категорії</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" <?php if ($selected_category == $category['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary">Фільтрувати</button>
            </form>
        </div>
        <div class="row">
            <?php
            try {
                // Підготувати і виконати запит для вибірки товарів
                $stmt = $conn->prepare($sql);
                $stmt->execute($params);

                // Відобразити товари
                while ($row = $stmt->fetch()) {
                    echo "<div class='col-sm-6 col-md-4 col-lg-3 mb-4'>";
                    echo "<div class='card product-card h-100'>";
                    echo "<a href='view_product.php?id=" . $row['id'] . "' class='product-link'>";
                    echo "<img class='card-img-top product-image' src='" . htmlspecialchars($row['image_url']) . "' alt='Product Image'>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>" . htmlspecialchars($row['name']) . "</h5>";
                    echo "<p class='card-text'>" . htmlspecialchars($row['description']) . "</p>";
                    echo "<div class='product-price'>" . htmlspecialchars(number_format($row['price'], 2)) . " грн</div>";
                    echo "</a>"; // Закриваємо тег <a> після інформації про продукт
                    echo "<form action='catalog.php' method='post'>";
                    echo "<input type='hidden' name='product_id' value='" . $row['id'] . "'>";
                    echo "<a href='add_to_cart.php?id=" . $row['id'] . "' class='btn btn-primary details-button' onclick='event.stopPropagation();'>Додати в кошик</a>";
                    echo "</form>";
                    if ($isAdmin) {
                        echo "<a href='edit_product.php?id=" . $row['id'] . "' class='btn btn-warning edit-button'>Редагувати</a>";
                    }
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            } catch (PDOException $e) {
                echo "Помилка вибірки товарів: " . $e->getMessage();
            }
            ?>
        </div>
    </div>
</main>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
