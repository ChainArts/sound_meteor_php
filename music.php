<?php
include "functions.php";
$pagetitle = "Music";

include "header.php";

if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'del_success':
            $msg = "Song was deleted successfully";
            break;
        case 'add_success':
            $msg = "Song was added successfully";
            break;
        case 'edit_success':
            $msg = "Song was edited successfully";
            break;
        case 'del_fail':
            $msg = "Song was not deleted!";
            break;
        case 'add_fail':
            $msg = "Song was not added!";
            break;
        case 'edit_fail':
            $msg = "Song could not be edited";
            break;
        case 'display_fail':
            $msg = "Song could not be displayed";
            break;
        default:
            echo "Oboi u fucked up mate";
            break;
    }
    echo "<div class=\"state-box hidden\">
            <span>$msg</span>
          </div>";
}
?>
<h1><?= $pagetitle ?></h1>
<div class="table-wrapper">
    <table>
        <tr>
            <th>ID</th>
            <th>Band</th>
            <th>Name</th>
            <th>Album</th>
            <th>Runtime</th>
        </tr>

        <?php
        try {
            $sth = $dbh->prepare("SELECT * FROM tracks ORDER BY track_id");
            $sth->execute(array());
            $tracks = $sth->fetchAll();

            if (empty($tracks)) {
                echo "<tr><td colspan = '6'>No songs found.</td></tr>";
            } else {
                foreach ($tracks as $track) {
                    echo "
                        <tr onclick=\"window.location='song_details.php?id=" . $track->track_id . "' \">
                            <td>" . $track->track_id . "</td>
                            <td>" . $track->band . "</td>
                            <td>" . $track->name . "</td>
                            <td>" . $track->album . "</td>
                            <td>" . convert($track->runtime) . "</td>
                        </tr>";
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        ?>
    </table>
</div>
<div class="button">
    <a href="song_new.php"><span>Add Song</span></a>
</div>


<?php
include "footer.php";
?>