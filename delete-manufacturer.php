<?php

require_once("fileLoader.php");
loadFile("database/database.php");
session_start();

if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in'] || $_SESSION['loggin_details']['role'] !== 'administrator')
{
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $_POST['_method'] !== 'delete') {
    header("Location: index.php");
    exit;
}

$id = htmlspecialchars($_POST['manufacturer_id']) ?? '';

if (!$id) {
    header("Location: index.php");
    exit;
}


try {
    $pdo = connectToDatabase();
    $sqlQuery = "DELETE FROM manufacturer WHERE id = :id;";
    $stmt = $pdo->prepare($sqlQuery);
    $stmt->execute([":id" => $id]);

    $_SESSION['flash'] = "Proizvođač uspješno izbrisan.";
    $_SESSION['success'] = true;
    header("Location: manufacturers-list.php");
    exit;

} catch (Exception $e) {
    $_SESSION['flash'] = $e->getMessage();
    $_SESSION['err'] = true;
    header("Location: manufacturers-list.php");
    exit;
}