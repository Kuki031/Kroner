<?php

require_once("fileLoader.php");
loadFile("header.php");


if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in'] || $_SESSION['loggin_details']['role'] !== 'administrator')
{
    header("Location: index.php");
    exit;
}

try {

    $pdo = connectToDatabase();
    $sqlQuery = "SELECT id, name FROM manufacturer;";
    $stmt = $pdo->prepare($sqlQuery);

    $stmt->execute();
    $result = $stmt->fetchAll();

    $productId = htmlspecialchars($_GET['id']) ?? '';

    if (!$productId)
    {
        throw new Exception("ID proizvoda neispravan.");
    }

    $sqlQuery2 = "SELECT p.id, p.name, p.price, p.quantity_available, p.image, p.manufacturer_id, m.name AS man_name 
    FROM products 
    AS p 
    JOIN manufacturer 
    AS m 
    ON p.manufacturer_id = m.id 
    WHERE p.id = :id;";
    $stmt2 = $pdo->prepare($sqlQuery2);
    $stmt2->execute([":id" => $productId]);

    $product = $stmt2->fetch();

} catch (Exception $e) {
    $_SESSION['flash'] = "Nešto nije u redu: " . $e->getMessage();
    $_SESSION['err'] = true;

    header("Location: edit-product-form.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ažuriraj proizvod</title>
</head>
<body>
    <div class="form-holder-wrap">
        <div class="form-holder-wrap__holder">
            <form action="edit-product.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_method" value="patch">
                <input type="hidden" name="id" value="<?= $productId?>">
                <div>
                    <label for="name">Ime:</label>
                    <input type="text" name="name" id="name" placeholder="Ime proizvoda" value="<?= $product['name']?>" required autocomplete="off">
                </div>

                <div>
                    <label for="name">Cijena:</label>
                    <input type="text" name="price" id="price" placeholder="Cijena proizvoda" value="<?= $product['price']?>" required autocomplete="off">
                </div>

                <div>
                    <label for="name">Dostupna količina:</label>
                    <input type="text" name="quantity_available" id="quantity_available" placeholder="Dostupna količina proizvoda" value="<?= $product['quantity_available']?>" required autocomplete="off">
                </div>

                <div class="profile__picture preview" style="background-image: url('<?= htmlspecialchars($product['image'] ?? "assets/fallback-product.jpg") ?>')"></div>
                    <label for="file" class="edit-form__label">Odaberi sliku</label>
                    <input class="edit-form__input edit-form__input--file" type="file" name="file" id="file">
                <div>
                    <input class="edit-form__input" type="text" style="color:#fff" readonly disabled value="<?= $product['man_name']?>">
                </div>
                <div>
                    <select name="manufacturer" id="manufacturer">
                        <option value="<?= $product['man_name']?>">Odaberi proizvođača</option>
                        <?php foreach($result as $manufacturer): ?>
                            <option value="<?= $manufacturer['name']?>"><?= $manufacturer['name']?></option>
                            <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <input type="submit" value="Ažuriraj proizvod">
                </div>
                </div>
            </form>
        </div>
</body>
</html>
