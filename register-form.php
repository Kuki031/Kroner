<?php

require_once("fileLoader.php");
loadFile("header.php");
if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']) {
    header("Location: index.php");
    exit;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registracija</title>
</head>
<body>
    <?php loadFile("flashCheck.php"); ?>
    <div class="form-login-container">
        <div class="container__wrap">
        <h2 class="messages__header">Registracija u aplikaciju</h2>
        <form class="login-form" action="register.php" method="post">
            <input class="login-form__input" type="text" name="username" placeholder="Korisničko ime" required autocomplete="off">
            <input class="login-form__input" type="email" name="email" placeholder="E-mail adresa" required autocomplete="off">
            <input class="login-form__input" type="password" name="password" required autocomplete="off" placeholder="Lozinka">
            <input class="login-form__input" type="password" name="password-repeat" required autocomplete="off" placeholder="Ponovi lozinku">
            <input class="login-form__input login-form__input--submit" type="submit" value="Registriraj se" name="submit">
        </form>
        </div>
    </div>
</body>
</html>