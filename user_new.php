<?php
include "functions.php";
if (!isset($_SESSION["USER"])) {
    if($_SESSION["USER"] != "admin")
    {
        header('Location: schlingel.php?msg=no-access');
    }
    header('Location: schlingel.php');
}
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    try {
        $sth = $dbh->prepare("INSERT INTO users (email, f_name, l_name, username, password)VALUES (?, ?, ?, ?, ?)");
        $sth->execute(
            array(
                $_POST['email'],
                $_POST['f_name'],
                $_POST['l_name'],
                $_POST['username'],
                password_hash($_POST['password'], PASSWORD_BCRYPT, ["cost" => 10])
            )
        );
        header("Location: usrmngmt.php?status=add_success");
    } 
    catch (Exception $e) {
        header("Location: usrmngmt.php?status=add_fail");
    }
}
$pagetitle = "Add User";

include "header.php";
?>
<form class="form-wrapper" method="post" id="form" autocomplete="off" style="width: 45rem !important;" action="">
    <div class="form-title">
        <span><?= $pagetitle ?></span>
        <a href="usrmngmt.php"><span>&lt; Back</span></a> 
    </div>
    <div class="side-by-side">
        <div class="input-wrapper">
            <span class="input-label">Username</span>
            <input class="input-box" type="text" name="username" autofocus placeholder="Enter Username"/>
            <i class="focus-input fa-solid fa-user"></i>
        </div>
        <div class="input-wrapper">
            <span class="input-label">Passwort</span>
            <input class="input-box" type="password" name="password" placeholder="Enter password">
            <i class="fa-solid fa-lock focus-input"></i>
        </div>
    </div>
    <div class="side-by-side">
    <div class="input-wrapper">
        <span class="input-label">First Name</span>
        <input class="input-box" type="text" name="f_name" placeholder="Enter First Name">
        <i class="fa-solid fa-pen-nib focus-input"></i>
    </div>
    <div class="input-wrapper">
        <span class="input-label">Last Name</span>
        <input class="input-box" type="text" name="l_name" placeholder="Enter Last Name">
        <i class="fa-solid fa-pen-nib focus-input"></i>
    </div>
    </div>
    <div class="input-wrapper">
        <span class="input-label">Email</span>
        <input class="input-box" type="email" name="email" placeholder="Enter Email">
        <i class="fa-solid fa-envelope focus-input"></i>
    </div>
    <div class="button">
        <input type="submit" name="Add User" value="Add" class="btn">
    </div>
</form>
<?php
include "footer.php";
?>