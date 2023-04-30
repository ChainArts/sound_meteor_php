<?php
include "functions.php";
if (!isset($_SESSION["USER"])) {
    header('Location: schlingel.php');
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $sth = $dbh->prepare("INSERT INTO moods (name, emotion)VALUES (?, ?)");
        $sth->execute(
            array(
                $_POST['name'],
                $_POST['emotion']
            )
        );
        header("Location: moods.php?status=add_success");
    } catch (Exception $e) {
        header("Location: moods.php?status=add_fail");
    }
}
$pagetitle = "Add Mood";

include "header.php";
?>
<form class="form-wrapper" method="post" id="form" autocomplete="off">
    <div class="form-title">
        <span><?= $pagetitle ?></span>
        <a href="moods.php"><span>&lt; Back</span></a>
    </div>
    <div class="input-wrapper">
        <span class="input-label">Name</span>
        <input class="input-box" type="text" name="name" autofocus placeholder="Enter Mood">
        <i class="focus-input fa-solid fa-user"></i>
    </div>
    <div class="input-wrapper">
        <span class="input-label">Emotion</span>
        <input class="input-box" type="text" name="emotion" placeholder="Enter Emotion">
        <i class="fa-solid fa-lock focus-input"></i>
    </div>
    <div class="button">
        <input type="submit" name="Add Mood" value="Add" class="btn">
    </div>
</form>
<?php
include "footer.php";
?>