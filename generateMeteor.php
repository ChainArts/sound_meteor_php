<?php
include "functions.php";
$pagetitle = "Generate Meteor";


/*try {
    $sth = $dbh->prepare("SELECT * FROM tracks  WHERE genre_id = 1 Order by random() limit 3");
    $sth->execute(array());
    $tracks = $sth->fetchAll();

    if (empty($tracks)) {
        header("Location: meteor.php?status=gen_fail");
    } else {
        foreach ($tracks as $track) {
        }
    }
} catch (Exception $e) {
    header("Location: meteor.php?status=gen_fail");
}*/

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
include "footer.php";
echo '<script> loadNewAlbums('. $_SESSION['year'] .' , \'Rock\') </script>';
?>