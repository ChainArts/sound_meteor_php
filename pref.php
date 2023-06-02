<?php
include "functions.php";
$pagetitle = "Preferences";

$pref_title = "General";
$sth = $dbh->prepare("SELECT oldest_track_year FROM users WHERE user_id = ?");
$sth->execute(array($_SESSION['USER_ID']));
$year = $sth->fetch();

if (isset($_GET['pref'])) {
    if ($_GET['pref'] == "genres") {
        $pref_title = "Genres";
        $sth = $dbh->prepare("SELECT genres.name FROM user_pref_genre INNER JOIN genres on user_pref_genre.genre_id = genres.genre_id WHERE user_id = ?");
        $sth->execute(array($_SESSION['USER_ID']));
        $preferences = $sth->fetchAll();
    } else if ($_GET['pref'] == "moods") {
        $pref_title = "Moods";
        $sth = $dbh->prepare("SELECT moods.name FROM user_pref_mood INNER JOIN moods on user_pref_mood.mood_id = moods.mood_id WHERE user_id = ?");
        $sth->execute(array($_SESSION['USER_ID']));
        $preferences = $sth->fetchAll();
    }
}

include "header.php";
?>

<h1><?= $pagetitle ?></h1>
<div class="pref-container">
    <div class="pref-menu">
        <ul>
            <li>
                <a <?php if ($pref_title == "General") {
                        echo "class=\"pref-active\"";
                    } ?> href="?pref=general"><i class="fa-solid fa-gear"></i><span>General&ensp;&gt;</span></a>
            </li>
            <li>
                <a <?php if ($pref_title == "Genres") {
                        echo "class=\"pref-active\"";
                    } ?> href="?pref=genres"><i class="fa-solid fa-music"></i><span>Genres&ensp;&gt;</span></a>
            </li>
            <li>
                <a <?php if ($pref_title == "Moods") {
                        echo "class=\"pref-active\"";
                    } ?> href="?pref=moods"><i class="fa-solid fa-masks-theater"></i><span>Moods&ensp;&gt;</span></a>
            </li>
        </ul>
    </div>
    <div class="pref-select">
        <div class="pref-title"><?= $pref_title ?></div>
        <ul class="pref-list">
            <?php
            if ($pref_title == "Genres" || $pref_title == "Moods") {
                if (!empty($preferences)) {
                    foreach ($preferences as $pref) {
                            
            ?>
            <li class="pref-list-item">
                <span><i class="fa-solid fa-music"></i><span><?= $pref->name?></span></span>
                <button>
                    <i class="fa-solid fa-trash"></i>
                </button>
            </li>
            <?php }
                } else {
                    echo '<li><span> No '. $pref_title .' preferences found </span></li>';
                }
             ?>
        </ul>
        <span class="pref-count"><?=count($preferences)?> / 5</span>
        <?php
        if (($pref_title == "Genres" || $pref_title == "Moods") && count($preferences) < 5) {
            ?>
            <div class="pref-add">
                <button>
                    <i class="fa-solid fa-plus"></i>
                </button>
        </div>
        <?php }}?>
    </div>
</div>

<?php
include "footer.php";
?>