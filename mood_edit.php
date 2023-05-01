<?php
include "functions.php";
if (!isset($_SESSION["USER"])) {
    header('Location: schlingel.php');
}
$pagetitle = "Edit Mood";

include "header.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {
        $sth = $dbh->prepare("UPDATE moods SET name=?, emotion=? WHERE mood_id = ?");
        $sth->execute(array(
            $_POST['name'],
            $_POST['emotion'],
            $_GET['id']
        ));

        if (!$sth->rowCount() > 0) {
            header("Location: moods.php?status=edit_fail");
        } else {
            header("Location: moods.php?status=edit_success");
        }
    } catch (Exception $e) {
        header("Location: moods.php?status=edit_fail");
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
<form class="form-wrapper" method="post" id="form" autocomplete="off">
    <div class="form-title">
        <span><?= $pagetitle ?></span>
        <a href="moods.php"><span>&lt; Back</span></a>
    </div>
    <div class="input-wrapper">
        <span class="input-label">Name</span>
        <input class="input-box" type="text" name="name" autofocus placeholder="Enter Name" value="<?= html_entity_decode($mood->name) ?>" disabled>
        <i class="focus-input fa-solid fa-pen-nib"></i>
    </div>
    <div class="input-wrapper">
        <span class="input-label">Emotion</span>
        <input class="input-box" type="text" name="emotion" placeholder="Enter Emotion" value="<?= html_entity_decode($mood->emotion) ?>" disabled>
        <i class="fa-solid fa-masks-theater focus-input"></i>
    </div>
    <div class="edit-form">
        <div class="button" onclick="activateForm();">
            <span>Edit</span>
        </div>
    </div>
    <div class="side-by-side hiddenform" style="justify-content: center">
        <div class="button">
            <input type="submit" name="save" value="Save" class="btn" form="form">
        </div>
        <div class="button">
            <input type="submit" name="delete" value="Delete" class="delete" form="del">
        </div>
    </div>
</form>
</div>
</form>
<form action="mood_delete.php" method="post" id="del">
    <input type="hidden" value="<?php echo htmlspecialchars($_GET['id']); ?>" name="mood_id">
</form>

<?php
include "footer.php";
?>