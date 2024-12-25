<?php

session_start();
require_once("fileLoader.php");
loadFile("database/database.php");

if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['checkout'])) {

    $pdo = connectToDatabase();

    try {

        $userId = $_SESSION['loggin_details']['id'];
        $sqlQuery = "DELETE FROM shopping_cart WHERE user_id = :userId;";
        $stmt = $pdo->prepare($sqlQuery);
        $res = $stmt->execute([":userId" => $userId]);

        if ($res) {
            $_SESSION['flash'] = "Kupnja izvršena uspješno.";
            $_SESSION['success'] = true;
            header("Location: cart-items.php");
            exit;
        }

    } catch (Exception $e) {

        $_SESSION['flash'] = $e->getMessage();
        $_SESSION['err'] = true;
        header("Location: cart-items.php");
        exit;
    }
}