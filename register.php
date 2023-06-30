<?php
include "functions.php";
$pagetitle = "Register";

if (isset($_SESSION["USER"])) {
    header('Location: ./');
}

$registerErr = $passErr = $mailErr = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['username']) || empty($_POST["password"]) || empty($_POST["email"])) {
        if (empty($_POST['username'])) {
            $registerErr = "Enter a username!";
        }
        if (empty($_POST["password"])) {
            $passErr = "Enter a password!";
        }
        if (empty($_POST["email"])) {
            $mailErr = "Enter an email address!";
        }
    } else {
        try {
            $sth = $dbh->prepare("SELECT email, username FROM users WHERE email = ? OR username = ?");
            $sth->execute(array($_POST['email'], $_POST['username']));
            $isUserTaken = $sth->fetch();

            if ($isUserTaken) {
                if($isUserTaken->email == $_POST['email']){
                    $mailErr = "Email already registered!";
                }
                if($isUserTaken->username == $_POST['username']){
                    $registerErr = "Username already taken!";
                }       
            } else {
                try{
                $dbh->beginTransaction();

                $sth = $dbh->prepare("INSERT INTO users (user_id, email, username, password) VALUES (default, ?, ?, ?) RETURNING *");
                $sth->execute(array($_POST['email'], $_POST['username'], password_hash($_POST['password'], PASSWORD_BCRYPT)));
                $new_usr = $sth->fetch();
                
                $sth = $dbh->prepare("INSERT INTO user_pref_gen(user_id, oldest_track_year, playlist_length) VALUES (?, default, default) RETURNING *");
                $sth->execute(array($new_usr->user_id));
                $new_pref = $sth->fetch();

                $_SESSION['ID'] = session_id();
                $_SESSION['USER_ID'] = $new_usr->user_id;
                $_SESSION['USER'] = htmlspecialchars($new_usr->username);
                $_SESSION['PASS'] = $new_usr->password;
                $_SESSION['EMAIL'] = $new_usr->email;

                $_SESSION['list_len'] = $new_pref->playlist_length;
                $_SESSION['year'] = $new_pref->oldest_track_year;

                $dbh->commit();
                header('location: ./index.php?status=reg_succ');
                }
                catch(Exception $e){
                    $registerErr = "Something went wrong";
                    $dbh->rollBack();
                }
            }
        } catch (Exception $e) {
            $registerErr = "No connection to database";
        }
    }
}
include "header.php";
?>

<form class="form-wrapper" method="post" id="form" autocomplete="off">
    <span class="form-title" style="font-size: 2rem; font-weight: 700; margin-bottom: -2.1rem">Register</span>
    <div class="input-wrapper">
        <span class="input-label">Username<span class="error"><?= $registerErr ?></span></span>
        <input class="input-box" type="text" name="username" autofocus placeholder="Enter Username" value="<?php if (isset($_POST['username'])) echo html_entity_decode($_POST['username']); ?>" required minlength="3">
        <i class="focus-input fa-solid fa-user"></i>
    </div>
    <div class="input-wrapper">
        <span class="input-label">Email<span class="error"><?= $mailErr ?></span></span>
        <input class="input-box" type="email" name="email" placeholder="Enter email" value="<?php if (isset($_POST['email'])) echo html_entity_decode($_POST['email']); ?>" required>
        <i class="fa-solid fa-envelope focus-input"></i>
    </div>
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
        <input id="register" type="submit" name="register" value="CREATE ACCOUNT" class="button">
    </div>
    <span style="width: 100%; text-align: center; margin-top: -1.5rem"><a class="navLink" href="./login.php">Already have an account?</a></span>
</form>

<?php
include "footer.php";
?>