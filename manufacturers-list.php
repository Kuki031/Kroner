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

    $sqlQuery = "SELECT * FROM manufacturer LIMIT $offset, $items_per_page;";
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
    <title>Popis proizvođača</title>
</head>
<body>
<?php loadFile("flashCheck.php"); ?>
<div class="cart-wrap">
        <div class="table-container">
            <div>
            <h2 class="table-container__head">Popis proizvođača</h2>
            </div>
            <div>
            <a href="create-manufacturer-form.php" class="dashboard__links dashboard__links--smaller dashboard__links--new">Kreiraj novog proizvođača</a>
            </div>
            <table class="table-container__table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ime</th>
                        <th>Slika</th>
                        <th>Radnje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($result as $manufacturer): ?>
                        <tr>
                            <td><?= $manufacturer['id']?></td>
                            <td><?= $manufacturer['name']?></td>
                            <td>
                                <div class="profile__picture--smaller preview" style="background-image: url('<?= htmlspecialchars($manufacturer['image'] ?? "assets/manufacturer-fallback.jpg") ?>');"></div>
                            </td>
                            <td>
                            <div class="table-div-holder">
                                <a href="update-manufacturer-form.php?id=<?= $manufacturer['id']?>" class="dashboard__links dashboard__links--smaller">Ažuriraj</a>
                                <form action="delete-manufacturer.php" method="POST">
                                    <input type="hidden" name="_method" value="delete">
                                    <input type="hidden" name="manufacturer_id" value="<?= $manufacturer['id']?>">
                                    <input class="edit-form__input edit-form__input--button dashboard__links--del" type="submit" value="Izbriši" name="delete_manufacturer">
                                </form>
                            </div>
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