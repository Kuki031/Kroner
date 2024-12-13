<?php

session_start();
require_once("fileLoader.php");
loadFile("database/database.php");

if(!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== "POST" || $_POST['_method'] !== "PATCH") {
    header("Location: index.php");
    exit;
}

$id = $_SESSION['loggin_details']['id'];
if (!$id) {
    header("Location: index.php");
    exit;
}

$pdo = connectToDatabase();

try {

    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $repeatNewPassword = $_POST['repeat_password'] ?? '';

    $sqlQuery = "SELECT password FROM users WHERE id = :id;";
    $params = [
        ":id" => htmlspecialchars($id)
    ];
    $stmt = $pdo->prepare($sqlQuery);
    $stmt->execute($params);
    $result = $stmt->fetch();


    if (!$result || !comparePasswords($currentPassword, $result["password"])) {
        throw new Exception("Netočna trenutna lozinka.", 400);
    }

    if ($newPassword !== $repeatNewPassword) {
        throw new Exception("Nova lozinka i ponovljena nova lozinka se ne podudaraju.", 400);
    }

    changePassword($newPassword, $pdo, $id);
    header("Location: profile.php");
    exit;

} catch (Exception $e) {
    $_SESSION['flash'] = $e->getMessage();
    $_SESSION['err'] = true;
    header("Location: profile.php");
    exit;
}


function comparePasswords($oldPassword, $currentPassword) {
    return password_verify($oldPassword, $currentPassword);
}

function changePassword($newPassword, $pdo, $id) {
    $sqlQuery = "UPDATE users SET password = :password WHERE id = :id;";
    $params = [":password" => password_hash($newPassword, PASSWORD_DEFAULT), ":id" => htmlspecialchars($id)];
    $stmt = $pdo->prepare($sqlQuery);
    $stmt->execute($params);
    
    $_SESSION['flash'] = "Lozinka uspješno promjenjena.";
    $_SESSION['success'] = true;
}