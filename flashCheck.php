<?php if(isset($_SESSION['flash'], $_SESSION['err']) && $_SESSION['err']): ?>
    <?php loadFile("flashMessage.php"); ?>
    <?php elseif(isset($_SESSION['flash'], $_SESSION['success']) && $_SESSION['success']): ?>
        <?php loadFile("flashMessage.php"); ?>
    <?php endif; ?>