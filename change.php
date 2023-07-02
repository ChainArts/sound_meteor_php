<?php
include "functions.php";
$pagetitle = "Change Password";

$changeErr = $passErr = $oldPassErr = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['newpassword'])) {
        $passErr = "Enter a password!";
        if (empty($_POST["oldpassword"])) {
            $oldPassErr = "Enter a password!";
        }
    } else if (empty($_POST["oldpassword"])) {
        $oldPassErr = "Enter a password!";
    } else {

        try {

            if (password_verify($_POST['oldpassword'], $_SESSION['PASS'])) {
                $sth = $dbh->prepare("UPDATE users SET password = ? WHERE username = ?");
                $sth->execute(array(password_hash($_POST['newpassword'], PASSWORD_DEFAULT), $_SESSION['USER']));
                $_SESSION['PASS'] = password_hash($_POST['newpassword'], PASSWORD_DEFAULT);
                header('location: ./index.php?status=change_succ');
            } else {
                $oldPassErr = "Password incorrect!";
            }
        } catch (Exception $e) {
            $changeErr = "Something went wrong!";
        }
    }
}

include "header.php";
?>

<form class="form-wrapper" method="post" id="form" autocomplete="off">
    <span class="form-title" style="font-size: 2rem; font-weight: 700; margin-bottom: -2.1rem">Change Password</span>
    <div class="input-wrapper">
        <span class="input-label">Old Password<span class="error"><?= $oldPassErr ?></span></span>
        <input id="oldpass" class="input-box" type="password" name="oldpassword" placeholder="Enter old password" required>
        <i class="fa-solid fa-lock focus-input"></i>
    </div>
    <div class="input-wrapper">
        <span class="input-label">New Password<span class="error"><?= $passErr ?></span></span>
        <input id="pass" class="input-box" type="password" name="newpassword" placeholder="Enter new password" minlength="8" required>
        <i class="fa-solid fa-lock focus-input"></i>
    </div>
    <div class="input-wrapper">
        <span class="input-label">Confirm New Password<span id="confErr" class="error"><?= $changeErr ?></span></span>
        <input id="confPass" class="input-box" type="password" placeholder="Confirm new password" onkeyup="checkPassword()" minlength="8">
        <i class="fa-solid fa-lock focus-input"></i>
    </div>
    <div style="display: flex; justify-content: center">
        <input id="register" type="submit" name="setnewpass" value="CHANGE PASSWORD" class="button">
    </div>
    <span style="width: 100%; text-align: center; margin-top: -1.5rem"><a class="navLink" href="./index.php">Cancel</a></span>
</form>

<?php
include "footer.php";
?>