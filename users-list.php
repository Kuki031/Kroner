<?php

require_once("fileLoader.php");
loadFile("header.php");
loadFile("paging.php");

if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in'] || $_SESSION['loggin_details']['role'] !== 'administrator')
{
    header("Location: index.php");
    exit;
}

try {

    $pdo = connectToDatabase();

    $pagingRes = page($pdo, "products");
    $offset = $pagingRes['offset'];
    $items_per_page = $pagingRes['items_per_page'];
    $total_pages = $pagingRes['total_pages'];
    $page = $pagingRes['page'];

    $sqlQuery = 
    "SELECT u.id, u.username, u.email, u.profile_picture, u.is_banned, r.name AS role_name
    FROM users 
    AS u
    JOIN roles AS r
    ON r.id = u.role_id
    ORDER BY u.username ASC
    LIMIT $offset, $items_per_page";
    $stmt = $pdo->prepare($sqlQuery);
    $stmt->execute();

    $result = $stmt->fetchAll();

} catch (Exception $e) {
    $_SESSION['flash'] = $e->getMessage();
    $_SESSION['err'] = true;
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popis korisnika - Administrator</title>
</head>
<body>
<?php loadFile("flashCheck.php"); ?>
<div class="cart-wrap">
        <div class="table-container">
            <div>
            <h2 class="table-container__head">Popis korisnika</h2>
            </div>
            <table class="table-container__table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Korisničko ime</th>
                        <th>E-mail</th>
                        <th>Slika</th>
                        <th>Uloga</th>
                        <th>Radnje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($result as $user): ?>
                        <tr>
                            <td><?= $user['id']?></td>
                            <td><?= $user['username']?></td>
                            <td><?= $user['email']?></td>
                            <td>
                                <div class="profile__picture--smaller preview" style="background-image: url('<?= htmlspecialchars($user['profile_picture'] ?? "assets/fallback.png") ?>');"></div>
                            </td>
                            <td style="color: <?= $user['role_name'] === 'guest' ? 'var(--color-guest)' : 'var(--color-admin)'?>"><?= $user['role_name'] === 'guest' ? 'Gost' : 'Administrator'?></td>
                            <td>
                            <?php if($_SESSION['loggin_details']['username'] !== $user['username']): ?>
                                <div class="table-div-holder">
                                <?php if(!$user['is_banned']): ?>
                                    <form action="ban-user.php" method="post">
                                    <input type="hidden" name="_method" value="patch">
                                    <input type="hidden" name="user_id" value="<?= $user['id']?>">
                                    <input class="edit-form__input edit-form__input--button dashboard__links--del" type="submit" value="Izbaci" name="ban_user">
                                </form>
                                <?php else: ?>
                                    <form action="ban-user.php" method="post">
                                    <input type="hidden" name="_method" value="patch">
                                    <input type="hidden" name="user_id" value="<?= $user['id']?>">
                                    <input class="edit-form__input edit-form__input--button" type="submit" value="Vrati" name="unban_user">
                                </form>
                                    <?php endif; ?>
                            </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                </tbody>
            </table>
            <?php echo renderView($page, $total_pages) ?>
        </div>
    </div>
</body>
</html>