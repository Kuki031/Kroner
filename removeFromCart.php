<?php

require_once("fileLoader.php");
loadFile("database/database.php");
session_start();

if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$productId = htmlspecialchars($_POST['product_id'] ?? '');
$quantityAdded = htmlspecialchars($_POST['quantity_added'] ?? '');
$userId = $_SESSION['loggin_details']['id'];

if (!$productId || !$quantityAdded) {
    header("Location: index.php");
    exit;
}

$pdo = connectToDatabase();
updateCart($userId, $productId, $quantityAdded, $pdo);

function updateCart($userId, $productId, $qty, $pdo)
{

    try {

        $pdo->beginTransaction();

            $deleteFromCart = "DELETE FROM shopping_cart WHERE user_id = :userId AND product_id = :productId;";
            $stmt = $pdo->prepare($deleteFromCart);
            $stmt->execute([":userId" => $userId, ":productId" => $productId]);

            $updateProductQuery = "UPDATE products SET quantity_available = (quantity_available + :qty) WHERE id = :product_id;";
            $stmt2 = $pdo->prepare($updateProductQuery);
            $stmt2->execute([":qty" => $qty, ":product_id" => $productId]);

        $pdo->commit();

        $_SESSION['flash'] = "Proizvod uspješno maknut iz košarice.";
        $_SESSION['success'] = true;

        header("Location: cart-items.php");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();

        $_SESSION['flash'] = "Nešto nije u redu: " . $e->getMessage();
        $_SESSION['err'] = true;

        header("Location: cart-items.php");
        exit;
    }
}