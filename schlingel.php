<?php
include "functions.php";
$pagetitle = "Schlingel!";

include "header.php";
?>
<h1><?= $pagetitle ?></h1>
<?php 

if(isset($_GET['msg'])){
    echo("<span class=\"schlingel\">Please log into an Admin Account!</span>");
}
else{
    echo("<span class=\"schlingel\">Please login!</span>
    <div class=\"button\">
        <a href=\"login.php\"><span>Login</span></a>
    </div>");
}

?>
<?php
include "footer.php";
?>