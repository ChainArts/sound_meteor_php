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

try{
    $sth = $dbh->prepare("SELECT name, creator_id, isshared FROM playlists WHERE playlist_id = ?");
    $sth->execute(array($list_id));
    $list = $sth->fetch();

    $sth = $dbh->prepare("SELECT * FROM saved_in_playlist INNER JOIN tracks ON saved_in_playlist.track_id = tracks.track_id WHERE playlist_id = ?");
    $sth->execute(array($list_id));
    $tracks = $sth->fetchAll();
}
catch(Exception $e){
    echo $e->getMessage();
}
?>
<div class="meteor-container">
    <span class="meteor-title"><?=$list->name?> - Meteor</span>
</div>




<?php
include "footer.php";
?>