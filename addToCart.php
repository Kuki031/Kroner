<?php

require_once("fileLoader.php");
loadFile("database/database.php");

session_start();

if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
    header("Location: index.php");
    exit;
}

$addedQuantity = htmlspecialchars($_POST['quantity']) ?? '';
$productId = htmlspecialchars($_POST['p_id']) ?? '';

if (!$addedQuantity || !$productId) {
    header("Location: product-list.php");
    exit;
}

$pdo = connectToDatabase();
$checkIfAvailable = checkAvailability($pdo, $productId, $addedQuantity);
$result = checkCart($pdo, $_SESSION['loggin_details']['id'], $productId);


if (!$checkIfAvailable['is_available'])
{
    $_SESSION['flash'] = "Proizvod je rasprodan.";
    $_SESSION['err'] = true;

    header("Location: product-list.php");
    exit;
}

if (!$checkIfAvailable['is_greater'])
{
    $_SESSION['flash'] = "Odabrana količina je veća od raspoložive.";
    $_SESSION['err'] = true;

    header("Location: product-list.php");
    exit;
}

if ($result) {
    updateCart($_SESSION['loggin_details']['id'], $productId, $addedQuantity, $pdo);
} else {
    addToCart($_SESSION['loggin_details']['id'], $productId, $addedQuantity, $pdo);
}




function checkCart($pdo, $userId, $productId)
{
    $sqlQuery = "SELECT * FROM shopping_cart WHERE user_id = :user_id AND product_id = :product_id;";
    $stmt = $pdo->prepare($sqlQuery);
    $stmt->execute([":user_id" => $userId, ":product_id" => $productId]);
    $result = $stmt->fetch();

    return $result;
}


function checkAvailability($pdo, $productId, $qty)
{
    $checkIfAvailableQuery = "SELECT quantity_available FROM products WHERE id = :product_id;";
    $stmt = $pdo->prepare($checkIfAvailableQuery);
    $stmt->execute([":product_id" => $productId]);

    $result = $stmt->fetch();

    return [
        "is_available" => $result['quantity_available'] <= 0 ? false : true,
        "is_greater" => $qty > $result['quantity_available'] ? false : true
    ];
}


function updateCart($userId, $productId, $qty, $pdo)
{

    try {

        $pdo->beginTransaction();

            $updateCartQuery = "UPDATE shopping_cart SET quantity_added = (quantity_added + :qty) WHERE user_id = :user_id AND product_id = :product_id;";
            $stmt = $pdo->prepare($updateCartQuery);
            $stmt->execute([":qty" => $qty, ":user_id" => $userId, ":product_id" => $productId]);

            $updateProductQuery = "UPDATE products SET quantity_available = (quantity_available - :qty) WHERE id = :product_id;";
            $stmt2 = $pdo->prepare($updateProductQuery);
            $stmt2->execute([":qty" => $qty, ":product_id" => $productId]);

        $pdo->commit();

        $_SESSION['flash'] = "Proizvod uspješno dodan u košaricu.";
        $_SESSION['success'] = true;

        header("Location: product-list.php");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();

        $_SESSION['flash'] = "Nešto nije u redu: " . $e->getMessage();
        $_SESSION['err'] = true;

        header("Location: product-list.php");
        exit;
    }
}



function addToCart($userId, $productId, $qty, $pdo)
{

    $checkIfAvailableQuery = "SELECT quantity_available FROM products WHERE id = :product_id;";
    $stmt = $pdo->prepare($checkIfAvailableQuery);
    $stmt->execute([":product_id" => $productId]);

    $result = $stmt->fetch();

    if ($result['quantity_available'] <= 0) {
        $_SESSION['flash'] = "Proizvod je trenutno rasprodan.";
        $_SESSION['err'] = true;

        header("Location: product-list.php");
        exit;
    }

    try {

        $pdo->beginTransaction();

            $insertQuery = "INSERT INTO shopping_cart (user_id, product_id, quantity_added) 
                            VALUES (:user_id, :product_id, :quantity_added)";
            $stmt1 = $pdo->prepare($insertQuery);
            $stmt1->execute([
                ":user_id" => $userId,
                ":product_id" => $productId,
                ":quantity_added" => $qty,
            ]);

            $updateQuery = "UPDATE products 
                            SET quantity_available = (quantity_available - :qtyToReduce) 
                            WHERE id = :p_id";
            $stmt2 = $pdo->prepare($updateQuery);
            $stmt2->execute([
                ":qtyToReduce" => $qty,
                ":p_id" => $productId,
            ]);

        $pdo->commit();

        $_SESSION['flash'] = "Proizvod uspješno dodan u košaricu.";
        $_SESSION['success'] = true;

        header("Location: product-list.php");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();

        $_SESSION['flash'] = "Nešto nije u redu: " . $e->getMessage();
        $_SESSION['err'] = true;

        header("Location: product-list.php");
        exit;
    }
}