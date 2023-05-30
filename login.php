<?php
include "functions.php";
$pagetitle = "Login";

$loginErr = $passErr = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['username'])) {
        $loginErr = "Enter a username!";
        if (empty($_POST["password"])) {
            $passErr = "Enter a password!";
        }
    } else if (empty($_POST["password"])) {
        $passErr = "Enter a password!";
    } else {

        try {
            $sth = $dbh->prepare("SELECT * FROM users WHERE username = ?");
            $sth->execute(array($_POST['username']));
            $usr = $sth->fetch();

            if (empty($usr)) {
                $loginErr = "Username or Password wrong";
            } else {
                if (password_verify($_POST['password'], $usr->password)) {
                    $_SESSION['ID'] = session_id();
                    $_SESSION['USER_ID'] = $usr->user_id;
                    $_SESSION['USER'] = htmlspecialchars($usr->username);
                    $_SESSION['PASS'] = $usr->password;
                    $_SESSION['EMAIL'] = $usr->email;
                    header('location: index.php');
                } else {
                    $passErr = "Password incorrect!";
                }
            }
        } catch (Exception $e) {
            $loginErr = "Username or Password wrong";
        }
    }
}

include "header.php";
?>

<form class="form-wrapper" method="post" id="form" autocomplete="off">
    <div class="login-logo">
        <div class="logo"><a href="index.php"><img src="./media/SoundMeteor.svg" alt="Logo"></a></div>
    </div>
    <div class="input-wrapper">
        <span class="input-label">Username<span class="error"><?= $loginErr ?></span></span>
        <input class="input-box" type="text" name="username" autofocus placeholder="Enter Username" value="<?php if (isset($_POST['username'])) echo html_entity_decode($_POST['username']); ?>">
        <i class="focus-input fa-solid fa-user"></i>
    </div>
    <div class="input-wrapper">
        <span class="input-label">Password<span class="error"><?= $passErr ?></span></span>
        <input class="input-box" type="password" name="password" placeholder="Enter password">
        <i class="fa-solid fa-lock focus-input"></i>
    </div>
    <div style="display: flex; justify-content: center">
        <input type="submit" name="login" value="LOGIN" class="button">
    </div>
</form>

<?php
include "footer.php";
?>