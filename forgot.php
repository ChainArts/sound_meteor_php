<?php
include "functions.php";
$pagetitle = "Reset Password";

$resetErr = $passErr = $mailErr = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['username']) || empty($_POST["password"]) || empty($_POST["email"])) {
        if (empty($_POST["password"])) {
            $passErr = "Enter a password!";
        }
        if (empty($_POST["email"])) {
            $mailErr = "Enter an email address!";
        }
    } else {
        try {
            $sth = $dbh->prepare("SELECT email, FROM users WHERE email = ? OR username = ?");
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

                $sth = $dbh->prepare("INSERT INTO users (user_id, email, username, password) VALUES (default, ?, ?, ?) RETURNING user_id");
                $sth->execute(array($_POST['email'], $_POST['username'], password_hash($_POST['password'], PASSWORD_BCRYPT)));
                $new_id = $sth->fetch();
                
                $sth = $dbh->prepare("INSERT INTO user_pref_gen(user_id, oldest_track_year, playlist_length) VALUES (?, default, default)");
                $sth->execute(array($new_id->user_id));

                $dbh->commit();
                header('location: login.php?status=reg_succ');
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
else if($_SERVER['REQUEST_METHOD'] == 'GET'){
    if(isset($_GET['token'])){
        
    }
}
include "header.php";
?>

<form class="form-wrapper" method="post" id="form" autocomplete="off">
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
    <span style="width: 100%; text-align: center; margin-top: -1.5rem"><a class="navLink" href="/login">Already have an account?</a></span>
</form>

<?php
include "footer.php";
?>