<?php
include "functions.php";
$pagetitle = "Reset Password";

$resetErr = $passErr = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST["password"])) {
        if (empty($_POST["password"])) {
            $passErr = "Enter a password!";
        }
    } else {
        try {
            $dbh->beginTransaction();
            $sth = $dbh->prepare("UPDATE users SET password = ? WHERE email = ? and user_id = ?");
            $sth->execute(array(password_hash($_POST['password'], PASSWORD_BCRYPT), $_POST['email'], $_POST['uid']));
            
            $sth = $dbh->prepare("DELETE FROM reset_password WHERE user_id=? and email = ? and res_code = ?");
            $sth->execute(array($_POST['uid'], $_POST['mail'], $_POST['token']));

            $dbh->commit();

        } catch (Exception $e) {
            $dbh->rollBack();
            $resetErr = "No connection to database";
        }
        header("Location: ./login.php?status=res_succ");
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['token']) && isset($_GET['uid']) && isset($_GET['mail'])) {
        try {
            $sth = $dbh->prepare("SELECT * FROM reset_password WHERE user_id=? and email = ? and res_code = ?");
            $sth->execute(array($_GET['uid'], $_GET['mail'], $_GET['token']));
            $isValidToken = $sth->fetch();

            if (empty($isValidToken)) {
                header('location: forgot.php?status=invalid_token');
            } else {
                $dbh->beginTransaction();
                $sth = $dbh->prepare("DELETE FROM reset_password WHERE user_id=? and email = ? and res_code = ?");
                $sth->execute(array($_GET['uid'], $_GET['mail'], $_GET['token']));
                $isValidToken = $sth->fetch();
                $dbh->commit();
            }
        } catch (Exception $e) {
            $dbh->rollBack();
            $resetErr = "Password could not be reset!";
        }

        include "header.php";
?>

        <form class="form-wrapper" method="post" id="form" autocomplete="off">
        <span class="form-title" style="font-size: 2rem; font-weight: 700; margin-bottom: -2.1rem">Reset Password</span>
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
                <input id="register" type="submit" name="setnewpass" value="SET NEW PASSWORD" class="button">
            </div>
            <span style="width: 100%; text-align: center; margin-top: -1.5rem"><a class="navLink" href="./login.php">Cancel</a></span>
            <input type="hidden" name="email" value="<?= $_GET['mail'] ?>">
            <input type="hidden" name="uid" value="<?= $_GET['uid'] ?>">
            <input type="hidden" name="token" value="<?= $_GET['token'] ?>">
        </form>

<?php
        include "footer.php";
    } else {
        header('location: ./forgot.php');
    }
}
?>