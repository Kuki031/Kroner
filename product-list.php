<?php

require_once("fileLoader.php");
loadFile("header.php");
loadFile("paging.php");


if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
    header("Location: index.php");
    exit;
}

$pdo = connectToDatabase();

$pagingRes = page($pdo, "products");
$offset = $pagingRes['offset'];
$items_per_page = $pagingRes['items_per_page'];
$total_pages = $pagingRes['total_pages'];
$page = $pagingRes['page'];


$sqlQuery = 
"SELECT p.id, p.name, p.price, p.quantity_available, p.image, p.manufacturer_id, m.name 
AS man_name 
FROM products AS p 
INNER JOIN manufacturer AS m 
ON p.manufacturer_id = m.id 
LIMIT $offset, $items_per_page";

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
    <script src="./js/productCardControl.js" defer></script>
</head>
<body>
<?php loadFile("flashCheck.php"); ?>
    <div class="product-wrap">
    <?php echo renderView($page, $total_pages) ?>


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
                    <?php if ($product['quantity_available'] > 0): ?>
                        <span class="card-content__available"><?= $product['quantity_available']?> kom.</span>
                        <?php else: ?>
                            <span class="card-content__available">❌</span>
                        <?php endif; ?>
                    </p>
                </div>
                <?php if($product['quantity_available'] > 0): ?>
                    <form class="card-form" action="addToCart.php" method="post">
                    <div class="card-content__actions">
                    <input type="hidden" name="p_id" value="<?= $product['id']?>">
                    <label class="card-label" for="quantity">Odaberi količinu:</label>
                    <input
                        class="card-input"
                        type="number"
                        name="quantity"
                        id="quantity"
                        min="0"
                        value="0"
                    />
                    <input type="submit" class="edit-form__input--button cart-button disabled-cart" value="Dodaj u košaricu🛒" disabled>
                    </input>
                    </div>
                </form>
                    <?php else: ?>
                        <form class="card-form" action="#" method="#">
                            <div class="card-content__actions">
                                <label class="card-label" for="quantity">Odaberi količinu:</label>
                                <input
                                    class="card-input sold-out"
                                    disabled
                                    readonly
                                />
                                <input type="submit" class="edit-form__input--button cart-button sold-out" value="Rasprodano" disabled readonly>
                                </input>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php echo renderView($page, $total_pages) ?>

</div>
</body>
</html>