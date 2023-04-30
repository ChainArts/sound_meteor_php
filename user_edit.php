<?php
include "functions.php";
$pagetitle = "Edit User";

include "header.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($_POST['password'] == "") {
        try {
            $sth = $dbh->prepare("UPDATE users SET username=?, f_name=?, l_name=?, email=? WHERE user_id = ?");
            $sth->execute(array(
                $_POST['username'],
                $_POST['f_name'],
                $_POST['l_name'],
                $_POST['email'],
                $_GET['id']
            ));

            if (!$sth->rowCount() > 0) {
                header("Location: usrmngmt.php?status=edit_fail");
            } else {
                header("Location: usrmngmt.php?status=edit_success");
            }
        } catch (Exception $e) {
            header("Location: usrmngmt.php?status=edit_fail");
        }
    } else {
        try {
            $sth = $dbh->prepare("UPDATE users SET username=?, password=?, f_name=?, l_name=?, email=? WHERE user_id = ?");
            $sth->execute(array(
                $_POST['username'],
                password_hash($_POST['password'], PASSWORD_BCRYPT, ["cost" => 10]),
                $_POST['f_name'],
                $_POST['l_name'],
                $_POST['email'],
                $_GET['id']
            ));

            if (!$sth->rowCount() > 0) {
                header("Location: usrmngmt.php?status=edit_fail");
            } else {
                header("Location: usrmngmt.php?status=edit_success");
            }
        } catch (Exception $e) {
            header("Location: usrmngmt.php?status=edit_fail");
        }
    }
} else if (isset($_GET['id'])) {
    try {
        $sth = $dbh->prepare("SELECT * FROM users WHERE user_id = ?");
        $sth->execute(array($_GET['id']));
        $usr = $sth->fetch();

        if (!$usr) {
            header("Location: usrmngmt.php?status=display_fail");
        }
    } catch (Exception $e) {
        header("Location: usrmngmt.php?status=display_fail");
    }
} else {
    header("Location: usrmngmt.php?status=display_fail");
}
?>
<form class="form-wrapper" method="post" id="form" autocomplete="off" style="width: 45rem !important;" action="">
    <div class="form-title">
        <span><?= $pagetitle ?></span>
        <a href="usrmngmt.php"><span>&lt; Back</span></a> 
    </div>
    <div class="side-by-side">
        <div class="input-wrapper">
            <span class="input-label">Username</span>
            <input class="input-box" type="text" name="username" autofocus placeholder="Enter Username" value="<?= $usr->username ?>">
            <i class="focus-input fa-solid fa-user"></i>
        </div>
        <div class="input-wrapper">
            <span class="input-label">Passwort</span>
            <input class="input-box" type="password" name="password" placeholder="(unchanged)">
            <i class="fa-solid fa-lock focus-input"></i>
        </div>
    </div>
    <div class="side-by-side">
        <div class="input-wrapper">
            <span class="input-label">First Name</span>
            <input class="input-box" type="text" name="f_name" placeholder="Enter First Name" value="<?= $usr->f_name ?>">
            <i class="fa-solid fa-pen-nib focus-input"></i>
        </div>
        <div class="input-wrapper">
            <span class="input-label">Last Name</span>
            <input class="input-box" type="text" name="l_name" placeholder="Enter Last Name" value="<?= $usr->l_name ?>">
            <i class="fa-solid fa-pen-nib focus-input"></i>
        </div>
    </div>
    <div class="input-wrapper">
        <span class="input-label">Email</span>
        <input class="input-box" type="email" name="email" placeholder="Enter Email" value="<?= $usr->email ?>">
        <i class="fa-solid fa-envelope focus-input"></i>
    </div>
    <div class="side-by-side" style="justify-content: center">
        <div class="button">
            <input type="submit" name="save" value="Save" class="btn">
        </div>
        <form action="user_delete.php" method="post">
            <div class="button">
                <input type="hidden" value="<?php echo htmlspecialchars($_GET['id']); ?>" name="user_id">
                <input type="submit" name="delete" value="Delete" class="delete">
            </div>
        </form>
    </div>
</form>

<?php
include "footer.php";
?>