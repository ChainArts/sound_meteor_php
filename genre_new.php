<?php
include "functions.php";
if (!isset($_SESSION["USER"])) {
    header('Location: schlingel.php');
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $sth = $dbh->prepare("INSERT INTO genres (name, popularity)VALUES (?, ?) RETURNING genre_id");
        $sth->execute(
            array(
                $_POST['name'],
                $_POST['popularity']
            )
        );
        $new_genre_id = $sth->fetch();
        try{

            $sth = $dbh->prepare("INSERT INTO genre_is_mood (genre_id, mood_id)VALUES (?, ?)");
            $sth->execute(
            array(
                $new_genre_id->genre_id,
                $_POST['mood_id']
            )
            );

        }
        catch(Exception $e){
            header("Location: genres.php?status=gen_mood_fail");
        }
        header("Location: genres.php?status=add_success");
    } catch (Exception $e) {
        header("Location: genres.php?status=add_fail");
    }
}
else
{
    try {
        $sth = $dbh->prepare("SELECT mood_id, name FROM moods");
        $sth->execute(array());
        $moods = $sth->fetchAll();

    } catch (Exception $e) {
        header("Location: genres.php?status=add_fail");
    }

}
$pagetitle = "Add Genre";

include "header.php";
?>
<form class="form-wrapper" method="post" id="form" autocomplete="off">
    <div class="form-title">
        <span><?= $pagetitle ?></span>
        <a href="genres.php"><span>&lt; Back</span></a>
    </div>
    <div class="input-wrapper">
        <span class="input-label">Name</span>
        <input class="input-box" type="text" name="name" autofocus placeholder="Enter Genre">
        <i class="focus-input fa-solid fa-user"></i>
    </div>
    <div class="input-wrapper">
        <span class="input-label">Popularity</span>
        <input class="input-box" type="text" name="popularity" placeholder="Enter Popularity">
        <i class="fa-solid fa-lock focus-input"></i>
    </div>
    <div class="input-wrapper">
        <span class="input-label">Mood</span>
        <select class="input-box" name="mood_id" id="mood_id">
            <?php 
            foreach($moods as $mood)
            {
                echo("<option value=$mood->mood_id>$mood->name</option>");
            }
            
            ?>

        </select>
        <i class="fa-solid fa-lock focus-input"></i>
    </div>
    <div class="button">
        <input type="submit" name="Add Genre" value="Add" class="btn">
    </div>
</form>
<?php
include "footer.php";
?>