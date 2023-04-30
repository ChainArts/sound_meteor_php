<?php

include "functions.php";
if (!isset($_SESSION["USER"])){
    header('Location: schlingel.php');
}
$pagetitle = "Edit Genre";

include "header.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {
        $sth = $dbh->prepare("UPDATE genre SET name=?, popularity=? WHERE genre_id = ?");
        $sth->execute(array(
            $_POST['name'],
            $_POST['popularity'],
            $_GET['id']
        ));

        if (!$sth->rowCount() > 0) {
            header("Location: genres.php?status=edit_fail");
        } else {
            header("Location: genres.php?status=edit_success");
        }
    } catch (Exception $e) {
        header("Location: genres.php?status=edit_fail");
    }
} else if (isset($_GET['id'])) {
    try {
        $sth = $dbh->prepare("SELECT * FROM genres WHERE genre_id = ?");
        $sth->execute(array($_GET['id']));
        $genre = $sth->fetch();

        if (!$genre) {
            header("Location: genres.php?status=display_fail");
        }

        try {
            $sth = $dbh->prepare("SELECT mood_id, name FROM moods");
            $sth->execute(array());
            $moods = $sth->fetchAll();

            $sth = $dbh->prepare("SELECT moods.mood_id as mood_id FROM moods INNER JOIN genre_is_mood on moods.mood_id = genre_is_mood.mood_id WHERE genre_is_mood.genre_id = ?");
            $sth->execute(array($_GET['id']));
            $selected_mood = $sth->fetch();

            if (!$moods) {
                header("Location: genres.php?status=display_fail");
            }
        } catch (Exception $e) {
            header("Location: genres.php?status=add_fail");
        }
    } catch (Exception $e) {
        header("Location: genres.php?status=display_fail");
    }
} else {
    header("Location: genres.php?status=display_fail");
}
?>
<form class="form-wrapper" method="post" id="form" autocomplete="off">
    <div class="form-title">
        <span><?= $pagetitle ?></span>
        <a href="genres.php"><span>&lt; Back</span></a>
    </div>
    <div class="input-wrapper">
        <span class="input-label">Name</span>
        <input class="input-box" type="text" name="name" autofocus placeholder="Enter Name" value="<?= html_entity_decode($genre->name) ?>">
        <i class="focus-input fa-solid fa-user"></i>
    </div>
    <div class="input-wrapper">
        <span class="input-label">Popularity</span>
        <input class="input-box" type="text" name="popularity" placeholder="Enter Popularity" value="<?= html_entity_decode($genre->popularity) ?>">
        <i class="fa-solid fa-lock focus-input"></i>
    </div>
    <div class="input-wrapper">
        <span class="input-label">Mood</span>
        <select class="input-box" name="mood_id" id="mood_id">
            <?php
            foreach ($moods as $mood) {
                if ($mood->mood_id == $selected_mood->mood_id) {
                    echo ("<option value=$mood->mood_id selected='selected'>" . htmlspecialchars($mood->name) . "</option>");
                } else {
                    echo ("<option value=$mood->mood_id>" . htmlspecialchars($mood->name) . "</option>");
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
<form action="genre_delete.php" method="post" id="del">
    <input type="hidden" value="<?php echo htmlspecialchars($_GET['id']); ?>" name="genre_id">
</form>

<?php
include "footer.php";
?>