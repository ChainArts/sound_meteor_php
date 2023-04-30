<?php
include "functions.php";
$pagetitle = "Edit Preference";

include "header.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {
        $sth = $dbh->prepare("UPDATE user_pref_genre SET genre_id=?, WHERE user_id = ?");
        $sth->execute(array(
            $_POST['genre_id'],
            $_POST['user_id']
        ));

        if (!$sth->rowCount() > 0) {
            header("Location: pref.php?status=edit_fail");
        } else {
            $sth = $dbh->prepare("UPDATE user_pref_mood SET mood_id=?, WHERE user_id = ?");
            $sth->execute(array(
            $_POST['mood_id'],
            $_POST['user_id']
        ));
            if (!$sth->rowCount() > 0) {
                header("Location: pref.php?status=edit_fail");
            }
            else{
                header("Location: pref.php?status=edit_success");
            }
        }
    } catch (Exception $e) {
        header("Location: pref.php?status=edit_fail");
    }
} else if (isset($_GET['user_id'])) {
    try {
            $sth = $dbh->prepare("SELECT username FROM users WHERE user_id = ?");
            $sth->execute(array($_GET['user_id']));
            $user = $sth->fetch();

            $sth = $dbh->prepare("SELECT genre_id, name FROM genres");
            $sth->execute(array());
            $genres = $sth->fetchAll();

            $sth = $dbh->prepare("SELECT mood_id, name FROM moods");
            $sth->execute(array());
            $moods = $sth->fetchAll();

        if (!$genres || !$moods) {
            header("Location: pref.php?status=display_fail");
        }
    } catch (Exception $e) {
        header("Location: pref.php?status=display_fail");
    }
} else {
    header("Location: pref.php?status=display_fail");
}
?>
<form class="form-wrapper" method="post" id="form" autocomplete="off">
    <div class="form-title">
        <span><?= $pagetitle ?></span>
        <a href="pref.php"><span>&lt; Back</span></a>
    </div>
    <div class="input-wrapper">
        <span class="input-label">User</span>
        <input class="input-box" type="text" name="username" placeholder="Enter Username" value="<?= $user->username ?>" disabled>
        <i class="fa-solid fa-user focus-input"></i>
    </div>
    <div class="input-wrapper">
        <span class="input-label">Genre</span>
        <select class="input-box" name="mood_id" id="mood_id">
            <?php
            foreach ($genres as $genre) {
                if ($genre->genre_id == htmlspecialchars($_GET['genre_id'])) {
                    echo ("<option value=$genre->genre_id selected='selected'>$genre->name</option>");
                } else {
                    echo ("<option value=$genre->genre_id>$genre->name</option>");
                }
            }
            ?>

        </select>
        <i class="fa-solid fa-lock focus-input"></i>
    </div>
    <div class="input-wrapper">
        <span class="input-label">Mood</span>
        <select class="input-box" name="mood_id" id="mood_id">
            <?php
            foreach ($moods as $mood) {
                if ($mood->mood_id == htmlspecialchars($_GET['mood_id'])) {
                    echo ("<option value=$mood->mood_id selected='selected'>$mood->name</option>");
                } else {
                    echo ("<option value=$mood->mood_id>$mood->name</option>");
                }
            }
            ?>

        </select>
        <i class="fa-solid fa-lock focus-input"></i>
    </div>
    <div class="side-by-side" style="justify-content: center">
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
<form action="pref_delete.php" method="post" id="del">
    <input type="hidden" value="<?php echo htmlspecialchars($_GET['user_id']); ?>" name="user_id">
    <input type="hidden" value="<?php echo htmlspecialchars($_GET['genre_id']); ?>" name="genre_id">
    <input type="hidden" value="<?php echo htmlspecialchars($_GET['mood_id']); ?>" name="mood_id">
</form>

<?php
include "footer.php";
?>