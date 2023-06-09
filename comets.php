<?php
include "functions.php";
if (!isset($_SESSION["USER"])) {
    header('Location: ./login.php');
}
$pagetitle = "Comets";

include "header.php";
?>

<h1><?= $pagetitle ?></h1>

<section class="comets-container">
    <?php
    try {
        $sth = $dbh->prepare("SELECT * from playlists WHERE isshared = true ORDER BY playlist_id DESC");
        $sth->execute();
        $comets = $sth->fetchAll();
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    foreach ($comets as $comet) {
        try {

            $sth = $dbh->prepare("SELECT username from users WHERE user_id = ?");
            $sth->execute([$comet->creator_id]);
            $usr = $sth->fetch();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    ?>
        <a href="./meteor.php?id=<?= $comet->playlist_id ?>">
            <div class="comet-card">
                <div class="comet-header">
                    <div class="play-btn"><i class="fa-solid fa-play"></i></div>
                    <span class="comet-title"><?= htmlspecialchars($comet->name) ?> Meteor</span>
                    <div class="comet-creator-img">
                        <img src="./media/logo.jpg">
                    </div>
                </div>
                <div class="comet-ico"><img src="./media/comet.svg" alt="Comet"></div>
                <span class="comet-creator">By <span><?php if(isset($usr->username)) echo htmlspecialchars($usr->username); else echo 'Deleted' ?></span></span>
                <div class="comet-content" data-simplebar data-simplebar-auto-hide="false">
                    <?php
                    try {
                        $sth = $dbh->prepare("SELECT title FROM track_in_playlist INNER JOIN tracks ON track_in_playlist.track_id = tracks.track_id WHERE playlist_id = ?");
                        $sth->execute([$comet->playlist_id]);
                        $tracks = $sth->fetchAll();

                        foreach ($tracks as $track) {

                    ?>
                            <div class="comet-track">
                                <span><?= $track->title ?></span>
                            </div>
                    <?php
                        }
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }

                    ?>
                </div>
            </div>
        </a>
    <?php } ?>
</section>

<?php
include "footer.php";
?>