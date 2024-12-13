<?php

session_start();
require_once("database/database.php");

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

    $username = trim($_POST['username']) ?? '';
    $email = trim($_POST['email']) ?? '';

    $sqlQuery = "UPDATE users SET username = :username, email = :email WHERE id = :id;";
    $params = [
        ":username" => htmlspecialchars($username),
        ":email" => htmlspecialchars($email),
        ":id" => htmlspecialchars($id)
    ];

    $stmt = $pdo->prepare($sqlQuery);
    $stmt->execute($params);

    loadFile("fileHandler.php");
    if (isset($_FILES['file'])) {
        handlePictureUploads( $pdo, "users", "profile_picture", $id);
    }


    refreshSession($pdo, $id);
} catch (Exception $e) {
    $_SESSION['flash'] = $e->getMessage();
    $_SESSION['err'] = true;
    header("Location: profile.php");
    exit;
}

function refreshSession($pdo, $id)
{
    $sqlQuery = "SELECT u.id, u.username, u.email, u.password, u.profile_picture, u.role_id, name FROM users AS u INNER JOIN roles AS r ON u.role_id = r.id WHERE u.id = :id";
    $stmt = $pdo->prepare($sqlQuery);
    $params = [":id" => htmlspecialchars($id)];
    $stmt->execute($params);
    $res = $stmt->fetch();

    $_SESSION['loggin_details'] = [
        "id" => $res['id'],
        "username" => $res['username'],
        "email" => $res['email'],
        "profile_picture" => $res['profile_picture'],
        "role" => $res['name']
    ];

    $_SESSION['flash'] = "Profil uspješno ažuriran.";
    $_SESSION['success'] = true;
    header("Location: profile.php");
    exit;
}