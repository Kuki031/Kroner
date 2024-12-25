<?php

require_once("fileLoader.php");
loadFile("header.php");

if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in'] || $_SESSION['loggin_details']['role'] !== 'administrator') {
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upravljačka ploča</title>
</head>
<body>
    <div class="dashboard-wrap">
            <div>
                <h2 class="dashboard__header">Upravljačka ploča</h2>
            </div>
        <div class="dashboard-options">
            <div>
                <a href="manufacturers-list.php" class="dashboard__links">Popis proizvođača</a>
                <a href="#" class="dashboard__links">Kreiraj novog proizvođača</a>
            </div>
            <div>
                <a href="#" class="dashboard__links">Popis proizvoda</a>
                <a href="#" class="dashboard__links">Kreiraj novi proizvod</a>
            </div>
            <div>
                <a href="#" class="dashboard__links">Popis korisnika</a>
            </div>
        </div>
    </div>
</body>
</html>