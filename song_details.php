<?php
include "functions.php";
if (!isset($_SESSION["USER"])){
    header('Location: schlingel.php');
}
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
    <div class="track-title">
        <span><?= $trk->band ?> - <?= $trk->name ?></span>
    </div>
    <div class="track-genre">
        <span><?= $trk->genre ?></span>
    </div>
    <span>Album: <?= $trk->album ?></span>
    
    <span style="padding-top: 1rem;">Length: <?= convert($trk->runtime) ?> min</span>
    <div class="button" style="padding-top: 2rem;">
        <a href="<?= $trk->link ?>"><span><?= $trk->service ?></span></a>
    </div>
    <form action="track_delete.php" method="post" class="side-by-side" style="margin-top: 2rem">
            <div class="button">
                <input type="hidden" value="<?php echo htmlspecialchars($_GET['id']); ?>" name="user_id">
                <input type="submit" name="delete" value="Delete" class="delete">
            </div>
            <div class="button">
                <a href="song_edit"><span>Edit</span></a>
        </div>
    </form>
    
</div>
<?php
include "footer.php";
?>