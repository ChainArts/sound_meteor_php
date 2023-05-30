<?php
include "functions.php";
if (!isset($_SESSION["USER"])) {
    header('Location: schlingel.php');
}
$pagetitle = "Meteor";

include "header.php";

if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'gen_fail':
            $msg = "Meteor could not be generated";
            break;
        default:
            echo "Oboi u fucked up mate";
            break;
    }
    echo "<div class=\"state-box hidden\">
            <span>$msg</span>
          </div>";
}
?>
<h1><?= $pagetitle ?></h1>




<?php
include "footer.php";
?>