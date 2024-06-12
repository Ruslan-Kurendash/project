<!DOCTYPE html>
<html lang="uk">
<head>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2>Підтвердження замовлення</h2>
    <form action="submit_order.php" method="post">
        <input type="hidden" name="product_id" value="<?php echo $_GET['product_id']; ?>">
        <div class="form-group">
            <label for="customer_name">Ім'я:</label>
            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
        </div>
        <div class="form-group">
            <label for="customer_surname">Прізвище:</label>
            <input type="text" class="form-control" id="customer_surname" name="customer_surname" required>
        </div>
        <div class="form-group">
            <label for="delivery_address">Адреса доставки:</label>
            <textarea class="form-control" id="delivery_address" name="delivery_address" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Підтвердити замовлення</button>
    </form>
</div>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
