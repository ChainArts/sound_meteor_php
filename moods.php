<?php
include "functions.php";
if(!isset($_SESSION["USER"]) || $_SESSION["USER"] != "admin"){
    header('Location: schlingel.php');
}
$pagetitle = "Moods";

include "header.php";

if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'del_success':
            $msg = "Mood was deleted successfully";
            break;
        case 'add_success':
            $msg = "Mood was added successfully";
            break;
        case 'edit_success':
            $msg = "Mood was edited successfully";
            break;
        case 'del_fail':
            $msg = "Mood was not deleted!";
            break;
        case 'add_fail':
            $msg = "Mood was not added!";
            break;
        case 'edit_fail':
            $msg = "Mood could not be edited";
            break;
        case 'display_fail':
            $msg = "Mood could not be displayed";
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
    <a href="mood_new.php"><span>Add Mood</span></a>
</div>
<div class="table-wrapper">
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Emotion</th>
        </tr>

        <?php
        try {
            $sth = $dbh->prepare("SELECT * FROM moods ORDER BY mood_id");
            $sth->execute(array());
            $moods = $sth->fetchAll();

            if (empty($moods)) {
                echo "<tr><td colspan = '3'>No moods found.</td></tr>";
            } else {
                foreach ($moods as $mood) {
                    echo "
                        <tr onclick=\"window.location='mood_details.php?id=" . $mood->mood_id . "' \">
                            <td>" . $mood->mood_id . "</td>
                            <td>" . $mood->name . "</td>
                            <td>" . $mood->emotion . "</td>
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