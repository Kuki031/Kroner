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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

try {

    $pdo = connectToDatabase();

    $name = trim(htmlspecialchars($_POST['name'])) ?? '';
    $price = htmlspecialchars($_POST['price']) ?? '';
    $quantityAvailable = htmlspecialchars($_POST['quantity_available']) ?? '';
    $img = $_FILES['file'] ?? '';
    $manufacturer = trim(htmlspecialchars($_POST['manufacturer'])) ?? '';


    if (!$name || !$price || !$quantityAvailable || !$manufacturer)
    {
        throw new Exception("Nisu uneseni svi podaci.");
    }

    if (!is_numeric($price) || !is_numeric($quantityAvailable))
    {
        throw new Exception("Cijena ili količina nisu brojčane vrijednosti.");
    }

    $getManufacturer = getAssociatedManufacturer($manufacturer, $pdo);

    $sqlQuery = "INSERT INTO products (name, price, quantity_available, manufacturer_id) VALUES (:name, :price, :quantity_available, :manufacturer_id);";
    $params = [
        ":name" => $name,
        ":price" => $price,
        ":quantity_available" => $quantityAvailable,
        ":manufacturer_id" => $getManufacturer['id']
    ];

    $stmt = $pdo->prepare($sqlQuery);
    $res = $stmt->execute($params);
    $lastInsert = $pdo->lastInsertId();

    if ($img) {
        handlePictureUploads( $pdo, "products", "image", $lastInsert);
    }

    if ($res)
    {
        $_SESSION['flash'] = "Proizvod uspješno kreiran.";
        $_SESSION['success'] = true;
        header("Location: product-list-admin.php");
        exit;
    }


} catch (Exception $e) {
    $_SESSION['flash'] = $e->getMessage();
    $_SESSION['err'] = true;
    header("Location: create-product-form.php");
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