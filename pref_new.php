<?php
include "functions.php";
if (!isset($_SESSION["USER"])) {
    header('Location: schlingel.php');
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $sth = $dbh->prepare("INSERT INTO user_pref_genre (user_id, genre_id)VALUES (?, ?)");
        $sth->execute(
            array(
                $_POST['user_id'],
                $_POST['genre_id']
            ));
        try{

            $sth = $dbh->prepare("INSERT INTO user_pref_mood (user_id, mood_id)VALUES (?, ?)");
            $sth->execute(
            array(
                $_POST['user_id'],
                $_POST['mood_id']
            ));

        }
        catch(Exception $e){
            header("Location: pref.php?status=add_fail");
        }
        header("Location: pref.php?status=add_success");
    } catch (Exception $e) {
        header("Location: pref.php?status=add_fail");
    }
}
else
{
    try {
        $sth = $dbh->prepare("SELECT mood_id, name FROM moods");
        $sth->execute(array());
        $moods = $sth->fetchAll();

        $sth = $dbh->prepare("SELECT genre_id, name FROM genres");
        $sth->execute(array());
        $genres = $sth->fetchAll();

        $sth = $dbh->prepare("SELECT user_id, username FROM users");
        $sth->execute(array());
        $users = $sth->fetchAll();

        if(!$users){
            header("Location: pref.php?status=display_fail");
        }

    } catch (Exception $e) {
        header("Location: pref.php?status=add_fail");
    }

}
$pagetitle = "Add Preference";

include "header.php";
?>
<form class="form-wrapper" method="post" id="form" autocomplete="off">
    <div class="form-title">
        <span><?= $pagetitle ?></span>
        <a href="pref.php"><span>&lt; Back</span></a>
    </div>
    <div class="input-wrapper">
        <span class="input-label">User</span>
        <select class="input-box" name="user_id" id="user_id">
            <?php 
            foreach($users as $user)
            {
                echo("<option value=$user->user_id>" . htmlspecialchars($user->username) . "</option>");
            }
            
            ?>

        </select>
        <i class="fa-solid fa-user focus-input"></i>
    </div>
    <div class="input-wrapper">
        <span class="input-label">Genre</span>
        <select class="input-box" name="genre_id" id="genre_id">
            <?php 
            foreach($genres as $genre)
            {
                echo("<option value=$genre->genre_id>" . htmlspecialchars($genre->name) . "</option>");
            }
            
            ?>

        </select>
        <i class="fa-solid fa-music focus-input"></i>
    </div>
    <div class="input-wrapper">
        <span class="input-label">Mood</span>
        <select class="input-box" name="mood_id" id="mood_id">
            <?php 
            foreach($moods as $mood)
            {
                echo("<option value=$mood->mood_id>" . htmlspecialchars($mood->name) . "</option>");
            }
            
            ?>

        </select>
        <i class="fa-solid fa-masks-theater focus-input"></i>
    </div>
    <div class="button">
        <input type="submit" name="Add Preference" value="Add" class="btn">
    </div>
</form>
<?php
include "footer.php";
?>