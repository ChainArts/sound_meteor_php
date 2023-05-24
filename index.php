<?php
include "functions.php";
$pagetitle = "Home";

include "header.php";
?>

<h1><?= $pagetitle ?></h1>
<div class="new-songs">
</div>

<div class="generate-button">
    <div class="button" onclick=loadNewSongs();>
        <a><span>generate</span></a>
    </div>
</div>

<?php
include "footer.php";
?>