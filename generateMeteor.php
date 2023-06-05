<?php
include "functions.php";
$pagetitle = "Generate Meteor";

try {
    $sth = $dbh->prepare("SELECT genres.genre_id, name FROM genres INNER JOIN user_pref_genre ON genres.genre_id = user_pref_genre.genre_id WHERE user_id = ? ORDER BY RANDOM() LIMIT 1");
    $sth->execute(array($_SESSION['USER_ID']));
    $randPrefGenre = $sth->fetch();


    if (empty($randPrefGenre)) {
        $sth = $dbh->prepare("SELECT genre_id, name FROM genres ORDER BY RANDOM() LIMIT 1");
        $sth->execute(array());
        $randPrefGenre = $sth->fetch();
    }
} catch (Exception $e) {
    header("Location: meteor.php?status=gen_fail");
    echo $e->getMessage();
}

include "header.php";
?>
<div class="hero">
    <div id="particles-js"></div>
    <div class="hero-canvas">
        <div class="hero-planet">
            <img class="mouse" value="2" rot="0" src="./media/planet.svg" alt="Planet">
        </div>
        <div class="hero-comet" style="--gap: 0">
            <img class="mouse" value="-3" rot="-5" src="./media/comet.svg" alt="Comet">
        </div>
        <div class="hero-comet" style="--gap: 1">
            <img class="mouse" value="-4" rot="-10" src="./media/comet.svg" alt="Comet">
        </div>
        <div class="hero-comet" style="--gap: 2">
            <img class="mouse" value="-6" rot="-15" src="./media/comet.svg" alt="Comet">
        </div>
    </div>
</div>
<div class="new-songs" style="position:relative; z-index: 1">
</div>
<div class="spinner" style="display: flex;">
    <img src="./media/meteor.svg" alt="Spinner">
    <span>Loading...</span>
</div>

<?php
echo '<script> loadNewAlbums(' . $_SESSION['year'] . ' , \'' . $randPrefGenre->name . '\' , ' . $randPrefGenre->genre_id . ') </script>';
include "footer.php";
?>