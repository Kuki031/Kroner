<?php

require_once("fileLoader.php");
loadFile("header.php");


if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in'] || $_SESSION['loggin_details']['role'] !== 'administrator')
{
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kreiraj proizvođača</title>
</head>
<body>
    <div class="form-holder-wrap">
        <div class="form-holder-wrap__holder">
            <form action="create-manufacturer.php" method="post" enctype="multipart/form-data">
                <div>
                    <label for="name">Ime:</label>
                    <input type="text" name="name" id="name" placeholder="Ime proizvođača" required autocomplete="off">
                </div>
                <div class="profile__picture preview" style="background-image: url('assets/manufacturer-fallback.jpg')"></div>
                    <label for="file" class="edit-form__label">Odaberi sliku</label>
                    <input class="edit-form__input edit-form__input--file" type="file" name="file" id="file">
                    
                <div>
                    <input type="submit" value="Kreiraj proizvođača">
                </div>
                </div>
            </form>
        </div>
</body>
</html>
