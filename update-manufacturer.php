<?php

require_once("fileLoader.php");
loadFile("database/database.php");
loadFile("fileHandler.php");

session_start();


if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in'] || $_SESSION['loggin_details']['role'] !== 'administrator')
{
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $_POST['_method'] !== 'patch') {
    header("Location: index.php");
    exit;
}

$id = htmlspecialchars($_POST['id']) ?? '';
$name = htmlspecialchars($_POST['name']) ?? '';
$img = $_FILES['file'] ?? '';

try {
    $pdo = connectToDatabase();
    $sqlQuery = "UPDATE manufacturer SET name = :name WHERE id = :id;";
    $stmt = $pdo->prepare($sqlQuery);
    $stmt->execute([":name" => $name, ":id" => $id]);

    if (isset($img)) {
        handlePictureUploads( $pdo, "manufacturer", "image", $id);
    }

    $_SESSION['flash'] = "Proizvođač uspješno ažuriran.";
    $_SESSION['success'] = true;
    header("Location: manufacturers-list.php");
    exit;

} catch (Exception $e) {
    $_SESSION['flash'] = $e->getMessage();
    $_SESSION['err'] = true;
    header("Location: manufacturers-list.php");
    exit;
}
