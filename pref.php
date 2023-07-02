<?php
include "functions.php";
if (!isset($_SESSION["USER"])) {
    header('Location: ./login.php');
}
$pagetitle = "Preferences";

$pref_title = "General";

$sth = $dbh->prepare("SELECT oldest_track_year, playlist_length, mood_weight_percentage FROM user_pref_gen WHERE user_id = ?");
$sth->execute(array($_SESSION['USER_ID']));
$gen_pref = $sth->fetch();

if (isset($_GET['pref'])) {
    if ($_GET['pref'] == "genres") {
        $pref_title = "Genres";
        $sth = $dbh->prepare("SELECT genres.name, genres.genre_id as pref_id FROM user_pref_genre INNER JOIN genres on user_pref_genre.genre_id = genres.genre_id WHERE user_id = ?");
        $sth->execute(array($_SESSION['USER_ID']));
        $preferences = $sth->fetchAll();

        $sth = $dbh->prepare("SELECT genres.name as pref_name, genres.genre_id as pref_id FROM genres");
        $sth->execute(array());
        $pref_type_list = $sth->fetchAll();
    } else if ($_GET['pref'] == "moods") {
        $pref_title = "Moods";
        $sth = $dbh->prepare("SELECT moods.name, moods.mood_id as pref_id FROM user_pref_mood INNER JOIN moods on user_pref_mood.mood_id = moods.mood_id WHERE user_id = ?");
        $sth->execute(array($_SESSION['USER_ID']));
        $preferences = $sth->fetchAll();

        $sth = $dbh->prepare("SELECT moods.name as pref_name, moods.mood_id as pref_id FROM moods");
        $sth->execute(array());
        $pref_type_list = $sth->fetchAll();
    }
}

include "header.php";
if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'reg_succ':
            $msg = "Account created successfully!";
            break;
        default:
            $msg = "Oboi u fucked up mate";
            break;
    }
    echo "<div class=\"state-box hidden\">
            <span>$msg</span>
          </div>";
}
?>

<h1><?= $pagetitle ?></h1>
<?php if(isset($_GET['status'])){ ?>
<div class="tutorial-container">
    <span>Choose criteria to generate a meteor-playlist</span>
    <br>
    <span style="margin: 1rem; font-size: 1.2rem">or</span>
    <br>
    <span><a class="navLink" href="./generateMeteor.php">Generate without settings</a></span>
</div>
<?php } ?>
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
                            <span><i class="fa-solid <?php if ($pref_title == "Moods") {
                                                            echo "fa-masks-theater";
                                                        } else {
                                                            echo "fa-music";
                                                        } ?>"></i><span><?= $pref->name ?></span></span>
                            <button type="button" onClick="delPref(<?= $pref->pref_id ?>, <?php if ($pref_title == "Moods") {
                                                                                                echo "'mood'";
                                                                                            } else {
                                                                                                echo "'genre'";
                                                                                            } ?>, this)">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </li>
                <?php }
                } else {
                    echo '<li class="pref-list-item"><span class="no-pref-msg"> No ' . $pref_title . ' preferences found </span></li>';
                }
                ?>
        </ul>
        <div id="select" class="pref-add-wrapper disabled">
            <div class="pref-add-select">
                <div class="cust-select" style="width: 100%" onclick="toggleDropDown('select')">
                    <span selected_id="-1" type="<?php if ($pref_title == "Moods") {
                                                        echo "mood";
                                                    } else {
                                                        echo "genre";
                                                    } ?>" id="selectedPref">Select <?= $pref_title ?></span>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <div class="pref-options-wrapper">
                    <div class="pref-search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input onkeyup="checkInp(this)" class="pref-search-input" type="text" placeholder="Search <?= $pref_title ?>">
                    </div>
                    <div class="pref-options" data-simplebar data-simplebar-auto-hide="false">
                        <ul id="pref-options">
                            <?php foreach ($pref_type_list as $pref_type) { ?>

                                <li prefID="<?= $pref_type->pref_id ?>" onclick=updateSelect(this) class="pref-option pref-list-item"><span><i class="fa-solid <?php if ($pref_title == "Moods") {
                                                                                                                                                                    echo "fa-masks-theater";
                                                                                                                                                                } else {
                                                                                                                                                                    echo "fa-music";
                                                                                                                                                                } ?>"></i><?= $pref_type->pref_name ?></span></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div id="pref-submit" class="button" style="line-height: 2;"><span>save</span></div>
        </div>
        <span class="pref-count" id="pref-count"><?= count($preferences) ?> / 5</span>
        <?php
                if (($pref_title == "Genres" || $pref_title == "Moods")) {
        ?>
            <div class="pref-add <?php if(count($preferences) >= 5) {echo "disabled"; } ?>">
                <button onclick="openPrefAdd(this)">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
    </div>
        <?php }
            } else {
        ?>
        <li class="gen-pref-li">
            <span>Oldest Songs:</span>
            <div id="select-year" class="pref-add-wrapper">
                <div class="pref-add-select">
                    <div class="cust-select" style="width: 100%" onclick="toggleDropDown('select-year')">
                        <span id="selectedPref"><?= $gen_pref->oldest_track_year ?></span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="pref-options-wrapper">
                        <div class="pref-options" data-simplebar data-simplebar-auto-hide="false">
                            <ul id="pref-options">
                                <?php
                                $earliest = 1980;
                                foreach (range(date('Y'), $earliest) as $x) {
                                    echo '<li onclick="updateGenSelect(this, \'select-year\')" class="pref-option pref-list-item"><span>' . $x . '</span></li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <li class="gen-pref-li">
            <span>Playlist Length:</span>
            <div id="select-length" class="pref-add-wrapper">
                <div class="pref-add-select">
                    <div class="cust-select" style="width: 100%" onclick="toggleDropDown('select-length')">
                        <span id="selectedPref"><?= $gen_pref->playlist_length ?></span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="pref-options-wrapper">
                        <div class="pref-options" data-simplebar data-simplebar-auto-hide="false">
                            <ul id="pref-options">
                                <?php
                                $least = 1;
                                foreach (range($least, 5) as $x) {
                                    echo '<li onclick="updateGenSelect(this, \'select-length\')" class="pref-option pref-list-item"><span>' . $x . '</span></li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <li class="gen-pref-li">
            <div class="gen-weight">
                <span>Genre vs Mood</span>
                <input type="range" min="0" max="100" value="<?= $gen_pref->mood_weight_percentage ?>" step="5" oninput="updateWeightVal(this.value)" onchange="updatePref(this.value, 'percentage')">
                <div class="weight-values">
                    <div class="weight-value">
                        <span>Genre</span>
                        <span id="genre-weight-val"><?php echo(100 - $gen_pref->mood_weight_percentage); ?>%</span>
                    </div>
                    <div class="weight-value">
                        <span>Mood</span>
                        <span id="mood-weight-val"><?= $gen_pref->mood_weight_percentage ?>%</span>
                    </div>
                </div>
            </div>
        </li>
    </div>
<?php } ?>
</div>
<div id="genBtn" class="genBtn">

</div>

<?php
include "footer.php";
?>