<?php
include "functions.php";
$pagetitle = "Comets";

include "header.php";
?>

<h1><?= $pagetitle ?></h1>

<section class="comets-container">
    <?php 
        for ($i = 0; $i <= 10; $i++){
    ?>
    <div class="comet-card">
        <div class="comet-header">
            <div class="play-btn"><i class="fa-solid fa-play"></i></div>
            <span class="comet-title">Drum and Bass Meteor</span>
            <div class="comet-creator-img">
                <img src="./media/logo.jpg">
            </div>
        </div>
        <span class="comet-creator">By <span>ChainArts</span></span>
        <div class="comet-content">
            <div class="comet-track">
                <span>Track 1</span>
            </div>
            <div class="comet-track">
                <span>Track 2</span>
            </div>
            <div class="comet-track">
                <span>Track 3</span>
            </div>
        </div>
    </div>
    <?php }?>
</section>

<?php
include "footer.php";
?>