<?php

session_start();
require_once("database/database.php");

if(!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in'] || $_SESSION['loggin_details']['role'] !== 'administrator') {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    header("Location: index.php");
    exit;
}

try {
    $pdo = connectToDatabase();
    $name = trim(htmlspecialchars($_POST['name'])) ?? '';

    if (!$name)
    {
        header("Location: index.php");
        exit;
    }

    $sqlQuery = "INSERT INTO manufacturer (name) VALUES (:name);";
    $stmt = $pdo->prepare($sqlQuery);
    $stmt->execute([":name" => $name]);

    $result = $pdo->lastInsertId();

    loadFile("fileHandler.php");
    if (isset($_FILES['file'])) {
        handlePictureUploads( $pdo, "manufacturer", "image", $result);
    }


    $_SESSION['flash'] = "Proizvođač uspješno kreiran.";
    $_SESSION['success'] = true;
    header("Location: manufacturers-list.php");
    exit;


} catch (Exception $e) {
    $_SESSION['flash'] = $e->getMessage();
    $_SESSION['err'] = true;
    header("Location: manufacturers-list.php");
    exit;
}