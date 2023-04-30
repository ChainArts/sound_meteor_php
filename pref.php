<?php
include "functions.php";
if (!isset($_SESSION["USER"])) {
    header('Location: schlingel.php');
}
$pagetitle = "Preferences";

include "header.php";

if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'del_success':
            $msg = "Preference was deleted successfully";
            break;
        case 'add_success':
            $msg = "Preference was added successfully";
            break;
        case 'edit_success':
            $msg = "Preference was edited successfully";
            break;
        case 'del_fail':
            $msg = "Preference was not deleted!";
            break;
        case 'add_fail':
            $msg = "Preference was not added!";
            break;
        case 'edit_fail':
            $msg = "Preference could not be edited";
            break;
        case 'display_fail':
            $msg = "Preference could not be displayed";
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
<div class="button" style="margin-bottom: 2rem">
    <a href="pref_new.php"><span>Add Preference</span></a>
</div>
<div class="table-wrapper">
    <table>
        <tr>
            <th>User</th>
            <th>Genre</th>
            <th>Mood</th>
        </tr>

        <?php
        try {
            $sth = $dbh->prepare("SELECT users.user_id, users.username, genres.genre_id, moods.mood_id, genres.name as genre, moods.name as mood from users INNER JOIN user_pref_genre ON users.user_id = user_pref_genre.user_id INNER JOIN genres on user_pref_genre.genre_id = genres.genre_id INNER JOIN user_pref_mood ON users.user_id = user_pref_mood.user_id INNER JOIN moods on user_pref_mood.mood_id = moods.mood_id ORDER BY users.user_id");
            $sth->execute(array());
            $prefs = $sth->fetchAll();

            if (empty($prefs)) {
                echo "<tr><td colspan = '3'>No Preferences found.</td></tr>";
            } else {
                foreach ($prefs as $pref) {
                    echo "
                        <tr onclick=\"window.location='pref_edit.php?user_id=" . $pref->user_id . "&genre_id=" . $pref->genre_id . "&mood_id=" . $pref->mood_id ."' \">
                            <td>" . htmlspecialchars($pref->username) . "</td>
                            <td>" . htmlspecialchars($pref->genre) . "</td>
                            <td>" . htmlspecialchars($pref->mood) . "</td>
                        </tr>";
                }
            }
        } catch (Exception $e) {
            //echo $e->getMessage();
        }
        ?>
    </table>
    </div>


<?php
include "footer.php";
?>