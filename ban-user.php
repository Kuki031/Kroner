<?php

require_once("fileLoader.php");
loadFile("database/database.php");
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

$id = htmlspecialchars($_POST['user_id']) ?? '';

if (!$id) {
    header("Location: index.php");
    exit;
}


try {
    $pdo = connectToDatabase();
    
    if (isset($_POST['ban_user']))
    {
        $sqlQuery = "UPDATE users SET is_banned = 1 WHERE id = :id;";
        $stmt = $pdo->prepare($sqlQuery);
        $stmt->execute([":id" => $id]);

        $_SESSION['flash'] = "Korisnik uspješno izbačen.";
        $_SESSION['success'] = true;
        header("Location: users-list.php");
        exit;
    } else {
        $sqlQuery = "UPDATE users SET is_banned = 0 WHERE id = :id;";
        $stmt = $pdo->prepare($sqlQuery);
        $stmt->execute([":id" => $id]);

        $_SESSION['flash'] = "Korisnik uspješno vračen.";
        $_SESSION['success'] = true;
        header("Location: users-list.php");
        exit;
    }

} catch (Exception $e) {
    $_SESSION['flash'] = $e->getMessage();
    $_SESSION['err'] = true;
    header("Location: users-list.php");
    exit;
}