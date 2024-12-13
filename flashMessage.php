<?php

$clazz = "";

if (isset($_SESSION['err'])) {
    $clazz = "flash__err";
} else {
    $clazz = "flash__success";
}

?>

<div class="flash-wrap">
    <div class="<?= $clazz?>">
        <p><?= $_SESSION['flash']?></p>
        <button class="flash__close">
            X
        </button>
    </div>
</div>
    <?php unset($_SESSION['flash'], $_SESSION['err'], $_SESSION['success']); ?>
