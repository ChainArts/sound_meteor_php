<?php
include "functions.php";
$pagetitle = "Home";

include "header.php";
?>
<div class="hero">
    <h1 class="hero-title">SOUND &emsp;<br> &emsp; METEOR</h1>
</div>
<div class="new-songs">
</div>

<div class="generate-button">
    <div class="button" onclick=loadNewAlbums();>
        <a><span>generate</span></a>
    </div>
</div>

<?php
include "footer.php";
?>