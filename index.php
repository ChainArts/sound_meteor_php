<?php
include "functions.php";
$pagetitle = "Home";

include "header.php";
?>
<div class="hero">
    <div class="hero-canvas">
        <div class="hero-planet">
            <img class="mouse" value="2" rot="0" src="./media/planet.svg" alt="Planet">
        </div>
        <div class="hero-comet" style="--gap: 0">
            <img class="mouse" value="-4" rot="-5" src="./media/comet.svg" alt="Comet">
        </div>
        <div class="hero-comet" style="--gap: 1">
            <img class="mouse" value="-5" rot="-10" src="./media/comet.svg" alt="Comet" >
        </div>
        <div class="hero-comet"  style="--gap: 2">
            <img class="mouse" value="-8" rot="-15" src="./media/comet.svg" alt="Comet">
        </div>
    </div>
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