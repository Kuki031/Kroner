<?php

require_once('fileLoader.php');
loadFile("header.php");


if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moj profil</title>
</head>
<body>
    <?php loadFile("flashCheck.php"); ?>
    <div class="profile-wrapper">
        <div class="profile-details">
            <div class="profile-details__component">
                <h2 class="profile__header">Moje informacije</h2>
                <form class="edit-profile-form" action="edit-profile.php" method="post" enctype="multipart/form-data">

                    <input class="edit-form__input" type="hidden" name="_method" value="PATCH">
                    <input class="edit-form__input" type="hidden" name="id" value="<?= $_SESSION['loggin_details']['id']?>">

                    <div class="picture__holder">
                    <div class="profile__picture preview" style="background-image: url('<?= htmlspecialchars($_SESSION['loggin_details']['profile_picture'] ?? "assets/fallback.png") ?>');"></div>
                        <label for="file" class="edit-form__label">Odaberi sliku</label>
                        <input class="edit-form__input edit-form__input--file" type="file" name="file" id="file">
                    </div>
                    <div>
                        <label for="username">Korisničko ime:</label>
                        <input class="edit-form__input" type="text" name="username" id="username" value="<?= $_SESSION['loggin_details']['username']?>" autocomplete="off">
                    </div>
                    <div>
                        <label for="email">E-mail:</label>
                        <input class="edit-form__input" type="email" name="email" id="email" value="<?= $_SESSION['loggin_details']['email']?>" autocomplete="off">
                    </div>
                    <div>
                        <label for="role">Uloga:</label>
                        <input class="edit-form__input edit-form__input--role" style="<?= $_SESSION['loggin_details']['role'] === 'guest' ? "color:var(--color-guest;)" : "color:var(--color-admin);" ?>" id="role" value="<?= $_SESSION['loggin_details']['role'] === 'guest' ? "Gost" : "Administrator" ?>" disabled readonly>
                    </div>
                    <div>
                    <input class="edit-form__input edit-form__input--button" type="submit" value="Spremi promjene" name="submit">
                    </div>
                </form>
            </div>
            <div class="profile-details__component">
                <h2 class="profile__header">Lozinka</h2>
                <form class="edit-profile-form" action="change-password.php" method="post">
                    <input class="edit-form__input" type="hidden" name="_method" value="PATCH">
                    <input class="edit-form__input" type="hidden" name="id" value="<?= $_SESSION['loggin_details']['id']?>">
                    <div>
                        <label for="current_password">Trenutna lozinka:</label>
                        <input class="edit-form__input" type="password" name="current_password" id="current_password" autocomplete="off">
                    </div>
                    <div>
                        <label for="new_password">Nova lozinka:</label>
                        <input class="edit-form__input" type="password" name="new_password" id="new_password" required autocomplete="off">
                    </div>
                    <div>
                        <label for="repeat_password">Ponovi lozinku:</label>
                        <input class="edit-form__input" type="password" name="repeat_password" required autocomplete="off" id="repeat_password">
                    </div>

                    <input class="edit-form__input edit-form__input--button" type="submit" value="Ažuriraj lozinku" name="submit">
                </form>
            </div>
        </div>
    </div>
</body>
</html>