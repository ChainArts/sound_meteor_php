<?php
include "functions.php";
$pagetitle = "Home";

include "header.php";

if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'reg_succ':
            $msg = "Account created successfully!";
            break;
        case 'change_succ':
            $msg = "Password changed successfully!";
            break;
        default:
            $msg = "Oboi u fucked up mate";
            break;
    }
    echo "<div class=\"state-box hidden\">
            <span>$msg</span>
          </div>";
}
?>
<div class="hero">
    <div id="particles-js"></div>
    <div class="hero-canvas">
        <div class="hero-planet">
            <img class="mouse" data-value="2" data-rot="0" src="./media/planet.svg" alt="Planet">
        </div>
        <div class="hero-comet" style="--gap: 0">
            <img class="mouse" data-value="-3" data-rot="-5" src="./media/comet.svg" alt="Comet">
        </div>
        <div class="hero-comet" style="--gap: 1">
            <img class="mouse" data-value="-4" data-rot="-10" src="./media/comet.svg" alt="Comet">
        </div>
        <div class="hero-comet" style="--gap: 2">
            <img class="mouse" data-value="-6" data-rot="-15" src="./media/comet.svg" alt="Comet">
        </div>
    </div>
    <h1 class="hero-title">SOUND &emsp;<br> &emsp; METEOR</h1>
    <div class="vert-slogan  mobile-hide">
        <span>Explore the extraterrestrial</span>
    </div>
</div>
<div class="new-songs">
</div>

<div class="generate-button" style="font-size: 1.5rem;">
    <div class="button" >
        <a <?php if(isset($_SESSION['ID'])){echo "href=\"./generateMeteor.php\"";}else{echo "href=\"./login.php\"";}?>><span>generate</span></a>
    </div>
</div>

<?php
include "footer.php";
?>