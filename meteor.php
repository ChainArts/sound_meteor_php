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
    if(isset($_GET['genPlaylist']) && isset($_GET['style']) && isset($_GET['sid'])){
        try{
            $dbh->beginTransaction();
  
        $sth = $dbh->prepare("INSERT INTO playlists (name, creator_id, genre_id) VALUES (?, ?, ?) RETURNING playlist_id");
        $sth->execute(array($_GET['style'], $_SESSION['USER_ID'], $_GET['sid']));
        $newPlaylist = $sth->fetch();

        $sth = $dbh->prepare("SELECT tracks.track_id FROM tracks INNER JOIN track_is_genre ON tracks.track_id = track_is_genre.track_id WHERE genre_id = ? ORDER BY RANDOM() LIMIT 3");
        $sth->execute(array($_GET['sid']));
        $track_ids = $sth->fetchAll();

        $sth = $dbh->prepare("INSERT INTO track_in_playlist (playlist_id, track_id) VALUES (?, ?)");
        foreach ($track_ids as $track_id) {
            $sth->execute(array($newPlaylist->playlist_id, $track_id->track_id));
        }
        $dbh->commit();
        header("Location: ". $_SERVER['PHP_SELF'] . "?id=". $newPlaylist->playlist_id);
        exit();
    }catch(PDOException $e){
        $dbh->rollBack();
        header("Location: ". $_SERVER['PHP_SELF'] . "?status=gen_fail");
    }
        }
    else if (isset($_GET['id'])) {

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
                        <img src="<?= $track->albumcoverlink ?>" alt="Cover Image">
                    </div>
                    <span class="song-title"><?= $track->title ?></span>
                    <div class="song-links">
                        <a title="Search on Youtube" target="_blank" href="<?= $track->ytlink ?>"><i class="fa-brands fa-youtube"></i></a>
                        <a title="Search on SoundCloud" target="_blank" href="<?= $track->sclink ?>"><i class="fa-brands fa-soundcloud"></i></a>
                        <a title="Show on Discogs" target="_blank" href="<?= $track->discogs ?>"><i class="fa-solid fa-record-vinyl"></i></a>
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
?>
<a class="chevron-down" href="#meteor-list">
    <i class="fa-solid fa-chevron-down"></i>
</a>
<div class="meteor-list" id="meteor-list">
    <div class="meteor-col">
        <span class="meteor-col-title">My Meteors</span>
        <div class="meteor-scroll" data-simplebar data-simplebar-auto-hide="false">
            <div class="meteor-scroll-cont">
                <?php
                try {
                    $sth = $dbh->prepare("SELECT * from playlists WHERE creator_id=? ORDER BY playlist_id DESC");
                    $sth->execute([$_SESSION['USER_ID']]);
                    $comets = $sth->fetchAll();
                } catch (Exception $e) {
                    echo $e->getMessage();
                }

                foreach ($comets as $comet) {
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
                            <span class="comet-creator">By <span><?= htmlspecialchars($_SESSION['USER']) ?></span></span>
                            <div class="comet-content">
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
            </div>
        </div>
    </div>
    <div class="meteor-col">
        <span class="meteor-col-title">Saved Meteors</span>
        <div class="meteor-scroll" data-simplebar data-simplebar-auto-hide="false">
            <div class="meteor-scroll-cont">
                <?php
                try {
                    $sth = $dbh->prepare("SELECT * from playlists NATURAL JOIN savedplaylists WHERE user_id=?");
                    $sth->execute([$_SESSION['USER_ID']]);
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
                            <span class="comet-creator">By <span><?= htmlspecialchars($usr->username) ?></span></span>
                            <div class="comet-content">
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
            </div>
        </div>
    </div>
</div>


<?php
include "footer.php";
?>