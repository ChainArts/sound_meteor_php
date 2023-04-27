<?php
include "functions.php";
$pagetitle = "Login";

$loginErr = $passErr = "";
if (isset($_POST)) {
    if (empty($_POST['username'])) {
        $loginErr = "Enter a username!";
        if (empty($_POST['password'])) {
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
            
            if (password_verify($_POST['password'], $usr->$password)) {
                $_SESSION['ID'] = session_id();
                $_SESSION['USER'] = $usr->$username;
                $_SESSION['PASS'] = $usr->$password;
            }
        } catch (Exception $e) {
            $loginErr = "Username or Password wrong";
        }
    }
}

include "header.php";
?>

<form method="post" class="new-room-form" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    <div class="usr_input">
        <span>Username</span>
        <input type="text" name="username" autocomplete="off" class="form" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>">
    </div>
    <div class="pwd_input">
        <span>Password<span class="error"><?php echo $passErr ?></span>
            <input type="password" name="pass" autocomplete="off" class="form">
    </div>
    <span class="error"><?php echo $loginErr ?></span>
    <input type="submit" name="login" value="LOGIN" class="btn">

</form>

<?php
include "footer.php";
?>