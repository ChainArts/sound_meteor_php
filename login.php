<?php
include "functions.php";
$pagetitle = "Login";

$loginErr = $passErr = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['username'])) {
        $loginErr = "Enter a username!";
        if (empty($_POST['pass'])) {
            $passErr = "Enter a password!";
        }
    } elseif (empty($_POST['password'])) {
        $passErr = "Enter a password!";
        if (empty($_POST['username'])) {
            $loginErr = "Enter a username!";
        }
    } else {
        try {
            $sth = $dbh->prepare("SELECT * FROM users WHERE username = ?");
            $sth->execute(array($_POST['username']));
            $usr = $sth->fetch();

            if(empty($usr)){
                $loginErr = "Username or Password wrong";
            }
            else{
                if (password_verify($_POST['password'], $usr->password)) {
                    $_SESSION['ID'] = session_id();
                    $_SESSION['USER'] = $usr->username;
                    $_SESSION['PASS'] = $usr->password;
                    header('location: index.php');
                }
                else{
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
<form class="login-wrapper" method="post" id="form" autocomplete="off">
    <div class="input-wrapper">
        <span class="input-label">Username<span class="error"><?php echo $loginErr ?></span></span>
        <input class="input-box" type="text" name="username" autofocus placeholder="Enter Username" value="<?php if (isset($_POST['username'])) echo $_POST['username'];?>" />
        <i class="focus-input fa-solid fa-user"></i>
    </div>
    <div class="input-wrapper">
        <span class="input-label">Passwort <span class="error"><?php echo $passErr ?></span></span>
        <input class="input-box" type="password" name="password" placeholder="Enter password">
        <i class="fa-solid fa-lock focus-input"></i>
    </div>
    <div class="button">
        <input type="submit" name="login" value="LOGIN" class="btn">
    </div>
</form>

<script src="script/script.js"></script>

<?php
include "footer.php";
?>