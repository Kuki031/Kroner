<?php

require_once("fileLoader.php");
loadFile("header.php");

if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
    header("Location: index.php");
    exit;
}

$userId = $_SESSION['loggin_details']['id'] ?? '';

if (!$userId) {
    header("Location: index.php");
    exit;
}

try {
    
    $pdo = connectToDatabase();
    $sqlQuery ="SELECT
                p.id AS prod_id,
                p.name, 
                p.price,
                s.quantity_added,
                SUM(s.quantity_added * p.price) AS total_price_per_product
                FROM 
                    shopping_cart AS s
                JOIN 
                    products AS p
                ON 
                    p.id = s.product_id
                WHERE 
                    s.user_id = :userId
                GROUP BY 
                    p.id, p.name, p.price, s.quantity_added
                ORDER BY 
                    total_price_per_product DESC
                ";
    
    $stmt = $pdo->prepare($sqlQuery);
    $stmt->execute([":userId" => $userId]);

    $result = $stmt->fetchAll();
    $totalPrice = 0;
    $totalQuantity = 0;

    for($i = 0 ; $i < sizeof($result) ; $i++)
    {
        $totalPrice += $result[$i]['total_price_per_product'];
        $totalQuantity += $result[$i]['quantity_added'];
    }

} catch (Exception $e) {
    $_SESSION['flash'] = $e->getMessage();
    $_SESSION['err'] = true;
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moja košarica</title>
</head>
<body>
<?php loadFile("flashCheck.php"); ?>
    <?php if(sizeof($result) <= 0): ?>
        <div class="cart-wrap">
            <div class="table-container">
            <h2 class="table-container__head">Vaša košarica je prazna.</h2>
            </div>
        </div>
        <?php else: ?>
            <div class="cart-wrap">
        <div class="table-container">
            <h2 class="table-container__head">Moja košarica</h2>
            <table class="table-container__table">
                <thead>
                    <tr>
                        <th>Ime proizvoda</th>
                        <th>Cijena proizvoda</th>
                        <th>Dodana količina</th>
                        <th>Ukupna cijena</th>
                        <th>Radnje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($result as $product): ?>
                        <tr>
                            <td><?= $product['name']?></td>
                            <td><?= $product['price']?> €</td>
                            <td><?= $product['quantity_added']?> kom.</td>
                            <td><?= $product['total_price_per_product']?> €</td>
                            <td>
                                <form action="removeFromCart.php" method="post">
                                    <input type="hidden" name="product_id" value="<?= $product['prod_id']?>">
                                    <input type="hidden" name="quantity_added" value="<?= $product['quantity_added']?>">
                                    <input class="edit-form__input edit-form__input--button" type="submit" value="Ukloni iz košarice" name="remove_from_cart">
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td>Ukupno:</td>
                        <td>-</td>
                        <td class="total-d"><?= $totalQuantity?> kom.</td>
                        <td class="total-d"><?= $totalPrice?> €</td>
                        <td>
                            <form action="#" method="#">
                            <input class="edit-form__input edit-form__input--button checkout" type="submit" value="Završi kupnju" name="checkout">
                            </form>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
        <?php endif; ?>
</body>
</html>