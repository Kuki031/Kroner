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
$price = htmlspecialchars($_POST['price']) ?? '';
$quantityAvailable = htmlspecialchars($_POST['quantity_available']) ?? '';
$img = $_FILES['file'] ?? '';
$manufacturer = htmlspecialchars($_POST['manufacturer']) ?? '';


try {
    $pdo = connectToDatabase();

    if (!$name || $price < 0 || $quantityAvailable < 0 || !$manufacturer)
    {
        throw new Exception("Nisu uneseni svi podaci.");
    }

    if (!is_numeric($price) || !is_numeric($quantityAvailable))
    {
        throw new Exception("Cijena ili količina nisu brojčane vrijendosti.");
    }

    $getManufacturer = getAssociatedManufacturer($manufacturer, $pdo);

    
    $sqlQuery = "UPDATE products SET name = :name, price = :price, quantity_available = :quantity_available, manufacturer_id = :manufacturer_id WHERE id = :id;";
    $stmt = $pdo->prepare($sqlQuery);
    $stmt->execute([":name" => $name, ":price" => $price, ":quantity_available" => $quantityAvailable, ":manufacturer_id" => $getManufacturer['id'], ":id" => $id]);

    if (isset($img)) {
        handlePictureUploads( $pdo, "products", "image", $id);
    }

    $_SESSION['flash'] = "Proizvod uspješno ažuriran.";
    $_SESSION['success'] = true;
    header("Location: product-list-admin.php");
    exit;

} catch (Exception $e) {
    $_SESSION['flash'] = $e->getMessage();
    $_SESSION['err'] = true;
    header("Location: product-list-admin.php");
    exit;
}

function getAssociatedManufacturer($name, $pdo)
{
    $sqlQuery = "SELECT * FROM manufacturer WHERE name = :name;";
    $stmt = $pdo->prepare($sqlQuery);
    $stmt->execute([":name" => htmlspecialchars($name)]);

    $result = $stmt->fetch();
    
    return $result;
}