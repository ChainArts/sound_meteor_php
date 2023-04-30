<?php
include "functions.php";
$pagetitle = "Edit Mood";

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
        $sth = $dbh->prepare("SELECT * FROM moods WHERE mood_id = ?");
        $sth->execute(array($_GET['id']));
        $mood = $sth->fetch();

        if (!$mood) {
            header("Location: moods.php?status=display_fail");
        }
    } catch (Exception $e) {
        header("Location: moods.php?status=display_fail");
    }
} else {
    header("Location: moods.php?status=display_fail");
}
?>
<form class="form-wrapper" method="post" id="form" autocomplete="off" style="width: 45rem !important;">
    <div class="form-title">
        <span><?= $pagetitle ?></span>
        <a href="moods.php"><span>&lt; Back</span></a> 
    </div>
        <div class="input-wrapper">
            <span class="input-label">Name</span>
            <input class="input-box" type="text" name="name" autofocus placeholder="Enter Name" value="<?= $mood->name ?>">
            <i class="focus-input fa-solid fa-user"></i>
        </div>
        <div class="input-wrapper">
            <span class="input-label">Emotion</span>
            <input class="input-box" type="text" name="emotion" placeholder="Enter Emotion" value="<?= $mood->emotion ?>">
            <i class="fa-solid fa-lock focus-input"></i>
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