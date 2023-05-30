<?php
$list_id = 1;
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
    $sth = $dbh->prepare("SELECT * FROM playlists WHERE playlist_id = ?");
    $sth->execute(array($list_id));
    $list = $sth->fetch();

    $sth = $dbh->prepare("SELECT username FROM users WHERE user_id = ?");
    $sth->execute(array($list->creator_id));
    $creator = $sth->fetch();

    $sth = $dbh->prepare("SELECT * FROM saved_in_playlist INNER JOIN tracks ON saved_in_playlist.track_id = tracks.track_id WHERE playlist_id = ?");
    $sth->execute(array($list_id));
    $tracks = $sth->fetchAll();
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
<div class="meteor-container">
    <span class="meteor-title"><?= $list->name ?> - Meteor</span>
    <div class="meteor-img"><img src="./media/SoundMeteor.svg" alt="Logo"></div>
    <?php
    foreach ($tracks as $track) {
        $title = $track->band . " - " . $track->name;
    ?>
        <div class="new-song-wrapper">
            <div class="song-cover">
                <img src="" alt="Cover Image">
            </div>
            <span class="song-title"><?= $title ?></span>
            <div class="song-links">
                <a title="Search on Youtube" target="_blank" href="https://youtube.com"><i class="fa-brands fa-youtube"></i></a>
                <a title="Search on SoundCloud" target="_blank" href="https://soundcloud.com"><i class="fa-brands fa-soundcloud"></i></a>
                <a title="Show on Discogs" target="_blank" href="https://discogs.com"><i class="fa-solid fa-record-vinyl"></i></a>
            </div>
        </div>
    <?php
    }
    ?>
    <div class="meteor-details">
        <span><?= $creator->username ?></span>
        <span>Views: <?=$list->view_amount?></span>
        <span>Shared: <?php if($list->isshared) echo "true"; else echo "false"; ?></span>
    </div>
</div>




<?php
include "footer.php";
?>