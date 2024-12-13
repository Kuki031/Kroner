<?php

declare(strict_types= 1);
require_once("fileLoader.php");
loadFile("header.php");

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Početna stranica</title>
</head>
<body>
    <?php loadFile("flashCheck.php"); ?>
    <?php loadFile("video-bg.php") ?>
        <?php if(!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']): ?>
        <?php loadFile("welcome-start.php")?>

        <?php else: ?>

            <div class="information-container">
                <div class="information">
                <h2 class="messages__header">Dobrodošli u Kroner e-trgovinu!</h2>
                <div>
                    <p class="messages__parf messages__parf--welcome">Ako želite pregledati proizvode u ponudi, kliknite na poveznicu "Popis proizvoda" u zaglavlju mrežne stranice.</p>
                    <p class="messages__parf messages__parf--welcome">Svoju košaricu možete također pregledati klikom na poveznicu "Moja košarica" u zaglavlju.</p>
                    <p class="messages__parf messages__parf--welcome">Svoj profil možete ažurirati klikom na poveznicu "Moj profil" u zaglavlju.</p>
                </div>
            </div>
        <?php endif; ?>
</body>
</html>
