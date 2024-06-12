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

// Отримати дані товара за його ідентифікатором
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($productId <= 0) {
    die('Некоректний ідентифікатор товару');
}

// Використання підготовлених запитів для отримання даних про товар
$stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute([':id' => $productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) {
    die('Товар не знайдено');
}

// Отримати список категорій
$categories = $conn->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагування товару</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Редагування товару</h2>

        <form action="update_product.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($productId); ?>">
            <div class="form-group">
                <label for="productName">Назва товару:</label>
                <input type="text" class="form-control" id="productName" name="productName" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="productDescription">Опис товару:</label>
                <textarea class="form-control" id="productDescription" name="productDescription" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="productPrice">Ціна:</label>
                <input type="number" class="form-control" id="productPrice" name="productPrice" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>
            <div class="form-group">
                <label for="productImageUrl">URL зображення:</label>
                <input type="text" class="form-control" id="productImageUrl" name="productImageUrl" value="<?php echo htmlspecialchars($product['image_url']); ?>">
            </div>
            <div class="form-group">
                <label for="productImage">Зображення товару:</label>
                <input type="file" id="productImage" name="productImage[]" multiple accept="image/*">
                <small class="form-text text-muted">Оберіть одне або кілька зображень для товару. Можна вибрати декілька файлів, натиснувши і утримуючи клавішу Ctrl (або Cmd на Mac).</small>
            </div>
            <div class="form-group">
                <label for="category">Категорія:</label>
                <select class="form-control" id="category" name="category" required>
                    <?php
                    foreach ($categories as $category) {
                        $selected = ($product['category_id'] == $category['id']) ? 'selected' : '';
                        echo "<option value=\"{$category['id']}\" $selected>" . htmlspecialchars($category['name']) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Зберегти зміни</button>
        </form>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
