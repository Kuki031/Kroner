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

} catch (Exception $e) {
    $_SESSION['flash'] = "Nešto nije u redu: " . $e->getMessage();
    $_SESSION['err'] = true;

    header("Location: create-product-form.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kreiraj proizvod</title>
</head>
<body>
    <div class="form-holder-wrap">
        <div class="form-holder-wrap__holder">
            <form action="create-product.php" method="post" enctype="multipart/form-data">
                <div>
                    <label for="name">Ime:</label>
                    <input type="text" name="name" id="name" placeholder="Ime proizvoda" required autocomplete="off">
                </div>

                <div>
                    <label for="name">Cijena:</label>
                    <input type="text" name="price" id="price" placeholder="Cijena proizvoda" required autocomplete="off">
                </div>

                <div>
                    <label for="name">Dostupna količina:</label>
                    <input type="text" name="quantity_available" id="quantity_available" placeholder="Dostupna količina proizvoda" required autocomplete="off">
                </div>

                <div class="profile__picture preview" style="background-image: url('assets/fallback-product.jpg')"></div>
                    <label for="file" class="edit-form__label">Odaberi sliku</label>
                    <input class="edit-form__input edit-form__input--file" type="file" name="file" id="file">
                
                <div>
                    <select name="manufacturer" id="manufacturer">
                        <option value="">Odaberi proizvođača</option>
                        <?php foreach($result as $manufacturer): ?>
                            <option value="<?= $manufacturer['name']?>"><?= $manufacturer['name']?></option>
                            <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <input type="submit" value="Kreiraj proizvod">
                </div>
                </div>
            </form>
        </div>
</body>
</html>
