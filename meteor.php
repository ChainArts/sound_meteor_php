<?php
include "functions.php";
if (!isset($_SESSION["USER"])) {
    header('Location: schlingel.php');
}
$pagetitle = "Meteor";

include "header.php";

if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'gen_fail':
            $msg = "Meteor could not be generated";
            break;
        default:
            echo "Oboi u fucked up mate";
            break;
    }
    echo "<div class=\"state-box hidden\">
            <span>$msg</span>
          </div>";
}
try {
    if (isset($_GET['id'])) {

        $sth = $dbh->prepare("SELECT * FROM playlists WHERE playlist_id = ?");
        $sth->execute(array($_GET['id']));
        $list = $sth->fetch();

        $sth = $dbh->prepare("SELECT username FROM users WHERE user_id = ?");
        $sth->execute(array($list->creator_id));
        $creator = $sth->fetch();
    } else {
        $sth = $dbh->prepare("SELECT * FROM playlists WHERE creator_id = ?");
        $sth->execute(array($_SESSION['USER_ID']));
        $list = $sth->fetch();
    }

    if (!empty($list)) {

        $sth = $dbh->prepare("SELECT * FROM track_in_playlist INNER JOIN tracks ON track_in_playlist.track_id = tracks.track_id WHERE playlist_id = ?");
        (isset($_GET['id']))
            ?
            $sth->execute(array($_GET['id']))
            :
            $sth->execute(array($list->playlist_id));
        $tracks = $sth->fetchAll();
?>
        <div class="meteor-container">
            <span class="meteor-title"><?= $list->name ?> - Meteor</span>
            <div class="meteor-img"><img src="./media/SoundMeteor.svg" alt="Logo"></div>
            <?php
            foreach ($tracks as $track) {
            ?>
                <div class="new-song-wrapper">
                    <div class="song-cover">
                        <img src="<?= $track->albumcoverlink?>" alt="Cover Image">
                    </div>
                    <span class="song-title"><?= $track->title ?></span>
                    <div class="song-links">
                        <a title="Search on Youtube" target="_blank" href="<?= $track->ytlink?>"><i class="fa-brands fa-youtube"></i></a>
                        <a title="Search on SoundCloud" target="_blank" href="<?= $track->sclink?>"><i class="fa-brands fa-soundcloud"></i></a>
                        <a title="Show on Discogs" target="_blank" href="<?= $track->discogs?>"><i class="fa-solid fa-record-vinyl"></i></a>
                    </div>
                </div>
            <?php
            }
            ?>
            <div class="meteor-details">
                <span><?= (!empty($creator)) ? $creator->username : $_SESSION['USER'] ?></span>
                <span>Views: <?= $list->view_amount ?></span>
                <span>Shared: <?php if ($list->isshared) echo "true";
                                else echo "false"; ?></span>
            </div>
        </div>
    <?php
    } else {
    ?>
        <div class="meteor-container">
            <span class="meteor-title">No Meteors Found!</span>
        </div>

<?php
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
include "footer.php";
?>