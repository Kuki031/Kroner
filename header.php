<?php
declare(strict_types= 1);
require_once("fileLoader.php");
session_start();

function checkNumberOfItems()
{
    loadFile("database/database.php");
    try {
        $pdo = connectToDatabase();
        $userId = $_SESSION['loggin_details']['id'];
        $sqlQuery = "SELECT COUNT(*) AS item_count FROM shopping_cart WHERE user_id = :id;";
        $stmt = $pdo->prepare($sqlQuery);
        $stmt->execute([":id" => $userId]);
    
        $res = $stmt->fetch();
        return $res['item_count'];
        
    } catch (Exception $e) {
        $_SESSION['flash'] = $e->getMessage();
        $_SESSION['err'] = true;
        header("Location: profile.php");
        exit;
    }
}

?>

<head>
    <link rel="stylesheet" href="./css/styles.css">
    <script src="js/closeFlash.js" defer></script>
    <script src="js/fileReader.js" defer></script>
</head>
<body>
    <header class="header">
        <nav class="nav">
            <div class="list-container">
                <a href="index.php" class="nav__link"><img class="logo" src="./img/logo.png" alt="Logo"></a>
            </div>
            <?php if(!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']): ?>
                <div class="action__buttons">
                <a href="login-form.php" class="nav__link">Prijava</a>
                <a href="register-form.php" class="nav__link">Registracija</a>
            </div>
            <?php else : ?>
                 <div class="list-container">
                    <div>
                        <a href="product-list.php?page=1" class="nav__link">Popis proizvoda</a>
                        <a href="cart-items.php" class="nav__link">Moja košarica (<span class="item-count"><?php echo checkNumberOfItems();?></span>)</a>
                    </div>
                    <div>
                        <a href="profile.php" class="nav__link">Moj profil (<span style="<?= $_SESSION['loggin_details']['role'] === 'guest' ? "color:var(--color-guest);" : "color:var(--color-admin);" ?>"><?= $_SESSION['loggin_details']['username']?></span>)</a>
                        <a href="logout.php" class="nav__link nav__link--logout">Odjava</a>
                    </div>
                 </div>
   
            <?php endif; ?>
        </nav>
    </header>
</body>
