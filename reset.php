<?php
include "functions.php";
$pagetitle = "Reset Password";

$resetErr = $passErr = $mailErr = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST["password"])) {
        if (empty($_POST["password"])) {
            $passErr = "Enter a password!";
        }
    } else {
        try {
            $sth = $dbh->prepare("SELECT email, FROM users WHERE email = ?");
            $sth->execute(array($_POST['email']));
            $isUserTaken = $sth->fetch();
        } catch (Exception $e) {
            $resetErr = "No connection to database";
        }
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {


    if (isset($_GET['token']) && isset($_GET['uid']) && isset($_GET['mail'])) {
        try {
            $sth = $dbh->prepare("SELECT * FROM reset_password WHERE user_id=? and email = ? and res_code = ?");
            $sth->execute(array($_GET['uid'], $_GET['mail'], $_GET['uid']));
            $isValidToken = $sth->fetch();

            if (empty($isValidToken)) {
                header('location: forgot.php?status=invalid_token');
            } else {
                $dbh->beginTransaction();
                $sth = $dbh->prepare("SELECT * FROM reset_password WHERE user_id=? and email = ? and res_code = ?");
                $sth->execute(array($_GET['uid'], $_GET['mail'], $_GET['uid']));
                $isValidToken = $sth->fetch();
            }
        } catch (Exception $e) {
            $dbh->rollBack();
            $resetErr = "Password could not be reset!";
        }

        include "header.php";
?>

        <form class="form-wrapper" method="post" id="form" autocomplete="off">
            <div class="input-wrapper">
                <span class="input-label">Password<span class="error"><?= $passErr ?></span></span>
                <input id="pass" class="input-box" type="password" name="password" placeholder="Enter password" minlength="8" required>
                <i class="fa-solid fa-lock focus-input"></i>
            </div>
            <div class="input-wrapper">
                <span class="input-label">Confirm Password<span id="confErr" class="error"></span></span>
                <input id="confPass" class="input-box" type="password" placeholder="Confirm password" onkeyup="checkPassword()" minlength="8">
                <i class="fa-solid fa-lock focus-input"></i>
            </div>
            <div style="display: flex; justify-content: center">
                <input id="setnewpass" type="submit" name="setnewpass" value="SET NEW PASSWORD" class="button">
            </div>
            <span style="width: 100%; text-align: center; margin-top: -1.5rem"><a class="navLink" href="/login">Cancel</a></span>
        </form>

<?php
        include "footer.php";
    } else {
        header('location: forgot.php');
    }
}
?>