<?php

require_once("fileLoader.php");
session_Start();

if ((!$_SERVER['REQUEST_METHOD'] === "POST") || !isset($_POST['submit'])) {
    header("Location: index.php");
    exit;
}
    
    loadFile("database/database.php");

    $pdo = connectToDatabase();
    $username = trim($_POST['username']) ?? '';
    $email = trim($_POST['email']) ?? '';
    $password = $_POST['password'];
    $passwordRepeat = $_POST['password-repeat'];

    try {

        $checkForDuplicateQuery = "SELECT * FROM users WHERE username=:username OR email=:email;";
        $params = [
            ":username" => htmlspecialchars($username),
            ":email" => htmlspecialchars($email)
        ];

        $stmtDuplicate = $pdo->prepare($checkForDuplicateQuery);
        $stmtDuplicate->execute($params);
        $result = $stmtDuplicate->fetch();

        if ($result) {
            throw new Exception("Korisničko ime ili e-mail već postoje.", 400);
        }

        if ($password !== $passwordRepeat) {
            throw new Exception("Lozinke se ne podudaraju.", 400);
        }

        
        $role = fetchAssociatedRole($pdo, "guest")['id'];
        register($pdo, $username, $email, $password, $role);

    } catch (Exception $e) {
        $_SESSION['flash'] = $e->getMessage();
        $_SESSION['err'] = true;
        header("Location: register-form.php");
        exit;
    }


function register($pdo, ...$info) {
    [$username, $email, $password, $role] = $info;
    $query = "INSERT INTO users (username, email, password, role_id) VALUES (:username, :email, :password, :role_id);";
    $params = [
        ":username" => htmlspecialchars($username),
        ":email" => htmlspecialchars($email),
        ":password" => password_hash($password, PASSWORD_DEFAULT),
        ":role_id" => htmlspecialchars($role)
    ];

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    $_SESSION['flash'] = "Registracija uspješna.";
    $_SESSION['success'] = true;
    header("Location: index.php");
    exit;
}

function fetchAssociatedRole($pdo, $name) {
    $query = "SELECT * FROM roles WHERE name = :name;";
    $params = [":name" => htmlspecialchars($name)];
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    $result = $stmt->fetch();
    return $result;
}