<?php
include "functions.php";
$pagetitle = "Song Details";

include "header.php";

if (isset($_GET['id'])) {
    try {
        $sth = $dbh->prepare("SELECT tracks.*, genres.name as genre FROM tracks INNER JOIN genres ON tracks.genre_id = genres.genre_id WHERE tracks.track_id = ?");
        $sth->execute(array($_GET['id']));
        $trk = $sth->fetch();

        if (!$trk) {
            header("Location: music.php?status=display_fail");
        }
    } catch (Exception $e) {
        header("Location: music.php?status=display_fail");
    }
} else {
    header("Location: music.php?status=display_fail");
}
?>
<div class="detail-wrapper">
    <div class="track_title">
        <span><?= $trk->band ?> - <?= $trk->name ?></span>
    </div>
    <span><?= $trk->album ?></span>
    <span><?= $trk->genre ?></span>
    <span><?= convert($trk->runtime) ?></span>
    <a href="<?= $trk->link ?>"><span><?= $trk->service ?></span></a>
    
</div>
<?php
include "footer.php";
?>