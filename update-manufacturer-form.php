<?php

require_once("fileLoader.php");
loadFile("header.php");

if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in'] || $_SESSION['loggin_details']['role'] !== 'administrator')
{
    header("Location: index.php");
    exit;
}

$manufacturerId = htmlspecialchars($_GET['id']) ?? '';
if (!$manufacturerId) {
    header("Location: index.php");
    exit;
}

try {
    $pdo = connectToDatabase();

    $sqlQuery = "SELECT * FROM manufacturer WHERE id = :id;";
    $stmt = $pdo->prepare($sqlQuery);
    $stmt->execute([":id" => $manufacturerId]);

    $manufacturer = $stmt->fetch();

} catch (Exception $e) {
    $_SESSION['flash'] = "Nešto nije u redu: " . $e->getMessage();
    $_SESSION['err'] = true;

    header("Location: cart-items.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ažuriraj proizvođača</title>
</head>
<body>
<div class="form-holder-wrap">
        <div class="form-holder-wrap__holder">
            <form action="update-manufacturer.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_method" value="patch">
                <input type="hidden" name="id" value="<?= $manufacturer['id']?>">
                <div>
                    <label for="name">Ime:</label>
                    <input type="text" name="name" id="name" placeholder="Ime proizvođača" value="<?= $manufacturer['name']?>" required autocomplete="off">
                </div>
                <div class="profile__picture preview" style="background-image: url('<?= $manufacturer['image'] ?? 'assets/manufacturer-fallback.jpg'?>')"></div>
                    <label for="file" class="edit-form__label">Odaberi sliku</label>
                    <input class="edit-form__input edit-form__input--file" type="file" name="file" id="file">
                    
                <div>
                    <input type="submit" value="Ažuriraj proizvođača">
                </div>
                </div>
            </form>
        </div>
</body>
</html>