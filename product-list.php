<?php

require_once("fileLoader.php");
loadFile("header.php");
loadFile("database/database.php");

if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
    header("Location: index.php");
    exit;
}

$pdo = connectToDatabase();

$sqlQuery = 
"SELECT p.id, p.name, p.price, p.quantity_available, p.image, p.manufacturer_id, p.out_of_stock, m.name 
AS man_name 
FROM products 
AS p 
INNER JOIN manufacturer 
AS m 
ON p.manufacturer_id = m.id;";

$stmt = $pdo->prepare($sqlQuery);
$stmt->execute();
$products = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popis proizvoda</title>
</head>
<body>
    <div class="product-list">
        <?php foreach($products as $product): ?>
            <div class="card">
                <div class="card-content">
                <h2 class="card-content__header"><?= $product['name']?></h2>
                <div
                    class="card-content__img"
                    style="background-image: url(<?= $product['image'] ?? 'assets/fallback-product.jpg' ?>)"
                ></div>
                <div class="card-content__info">
                    <p>
                    Proizvođač:
                    <span class="card-content__mf"><?= $product['man_name']?></span>
                    </p>
                    <p>
                    Cijena:
                    <span class="card-content__price"><?= $product['price']?> €</span>
                    </p>
                    <p>
                    Raspoloživo:
                    <span class="card-content__available"><?= $product['quantity_available']?> kom.</span>
                    </p>
                </div>
                <form class="card-form" action="#" method="post">
                    <div class="card-content__actions">
                    <label class="card-label" for="quantity">Odaberi količinu:</label>
                    <input
                        class="card-input"
                        type="number"
                        name="quantity"
                        id="quantity"
                        min="0"
                        value="0"
                    />
                    <button class="edit-form__input--button cart-button">
                        Dodaj u košaricu🛒
                    </button>
                    </div>
                </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>