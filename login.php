<?php

require_once("fileLoader.php");

if (($_SERVER['REQUEST_METHOD'] !== "POST") || !isset($_POST['submit'])) {
    header("Location: index.php");
    exit;
}
    loadFile("database/database.php");
    $pdo = connectToDatabase();

    try {
        $usernameOrEmail = trim($_POST['username-email']) ?? '';
        $password = $_POST['password'] ?? '';

        $query = "SELECT u.id, u.username, u.email, u.password, u.profile_picture, u.role_id, name FROM users AS u INNER JOIN roles AS r ON u.role_id = r.id WHERE u.username = :username OR u.email = :email;";
        
        
        $params = [
            ":username" => htmlspecialchars($usernameOrEmail),
            ":email" => htmlspecialchars($usernameOrEmail)
        ];
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetch();

        if (!$result || !password_verify($password, $result['password'])) {
            throw new Exception("Netočna lozinka ili korisničko ime ili e-mail.");
        }

        session_start();
        $_SESSION['is_logged_in'] = true;
        $_SESSION['loggin_details'] = [
            "id" => $result['id'],
            "username" => $result['username'],
            "email" => $result['email'],
            "profile_picture" => $result['profile_picture'],
            "role" => $result['name']
        ];
        header("Location: index.php");
        exit;
    
    } catch (Exception $e) {
        session_start();
        
        $_SESSION['flash'] = $e->getMessage();
        $_SESSION['err'] = true;
        header("Location: login-form.php");
        exit;
    }