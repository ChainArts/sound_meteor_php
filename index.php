<?php
include "functions.php";
$pagetitle = "Home";

include "header.php";
?>

<h1><?= $pagetitle ?></h1>

<div class="generate-button">
    <div class="button">
        <a href="generatePlaylist.php"><span>generate</span></a>
    </div>
</div>

<?php
include "footer.php";
?>