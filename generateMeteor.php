<?php
include "functions.php";
if (!isset($_SESSION["USER"])) {
    header('Location: ./login.php');
}
$pagetitle = "Generate Meteor";
$type = 'General';
try {
    $randPrefGenre = null;
    $randNum = mt_rand(1, 100);

    if($randNum <=100 - $_SESSION['MOOD_WEIGHT']){

        $type = 'Genre';

        //random genre based on genre preferences
        $sth = $dbh->prepare("SELECT genres.genre_id, name FROM genres INNER JOIN user_pref_genre ON genres.genre_id = user_pref_genre.genre_id WHERE user_id = ? ORDER BY RANDOM() LIMIT 1");
        $sth->execute(array($_SESSION['USER_ID']));
        $randPrefGenre = $sth->fetch();
    }
    else{
        //random genre based on mood preferences

        $type = 'Mood';
        $dbh->beginTransaction();
        
        $sth = $dbh->prepare("SELECT moods.mood_id FROM moods INNER JOIN user_pref_mood ON moods.mood_id = user_pref_mood.mood_id WHERE user_id = ? ORDER BY RANDOM() LIMIT 1");
        $sth->execute(array($_SESSION['USER_ID']));
        $randMood = $sth->fetch();

        if(!empty($randMood)){

            $sth = $dbh->prepare("SELECT genres.genre_id, genres.name as name FROM genre_mood_relations INNER JOIN genres ON genre_mood_relations.genre_id = genres.genre_id WHERE mood_id = ? ORDER BY RANDOM() LIMIT 1");
            $sth->execute(array($randMood->mood_id));
            $randPrefGenre = $sth->fetch();
        }
        
        $dbh->commit();
    }

    if (empty($randPrefGenre)) {
        $sth = $dbh->prepare("SELECT genre_id, name FROM genres ORDER BY RANDOM() LIMIT 1");
        $sth->execute(array());
        $randPrefGenre = $sth->fetch();
    }
} catch (Exception $e) {
    header("Location: ./meteor.php?status=gen_fail");
}

include "header.php";
?>
<div class="hero">
    <div id="particles-js"></div>
    <div class="hero-canvas">
        <div class="hero-planet">
            <img class="mouse" data-value="2" data-rot="0" src="./media/planet.svg" alt="Planet">
        </div>
        <div class="hero-comet" style="--gap: 0">
            <img class="mouse" data-value="-3" data-rot="-5" src="./media/comet.svg" alt="Comet">
        </div>
        <div class="hero-comet" style="--gap: 1">
            <img class="mouse" data-value="-4" data-rot="-10" src="./media/comet.svg" alt="Comet">
        </div>
        <div class="hero-comet" style="--gap: 2">
            <img class="mouse" data-value="-6" data-rot="-15" src="./media/comet.svg" alt="Comet">
        </div>
    </div>
</div>
<div class="new-songs" style="position:relative; z-index: 1">
</div>
<div class="spinner" style="display: flex;">
    <img src="./media/meteor.svg" alt="Spinner">
    <span>Generating <?=$type?>-Meteor...</span>
</div>

<?php
echo '<script> loadNewAlbums(' . $_SESSION['year'] . ' , \'' . $randPrefGenre->name . '\' , ' . $randPrefGenre->genre_id . ') </script>';
include "footer.php";
?>