<?php
include "functions.php";
if(!isset($_SESSION["USER"])){
    header('Location: schlingel.php');
}
$pagetitle = "Genres";

include "header.php";

if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'del_success':
            $msg = "Genre was deleted successfully";
            break;
        case 'add_success':
            $msg = "Genre was added successfully";
            break;
        case 'edit_success':
            $msg = "Genre was edited successfully";
            break;
        case 'del_fail':
            $msg = "Genre was not deleted!";
            break;
        case 'add_fail':
            $msg = "Genre was not added!";
            break;
        case 'edit_fail':
            $msg = "Genre could not be edited";
            break;
        case 'display_fail':
            $msg = "Genre could not be displayed";
            break;
        default:
            $msg = "Oboi u fucked up mate";
            break;
    }
    echo "<div class=\"state-box hidden\">
            <span>" . htmlspecialchars($msg) . "</span>
          </div>";
}
?>
<h1><?= $pagetitle ?></h1>
<div class="button" style="margin-bottom: 2rem">
    <a href="genre_new.php"><span>Add Genre</span></a>
</div>
<div class="table-wrapper">
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Popularity</th>
        </tr>

        <?php
        try {
            $sth = $dbh->prepare("SELECT * FROM genres ORDER BY genre_id");
            $sth->execute(array());
            $genres = $sth->fetchAll();

            if (empty($genres)) {
                echo "<tr><td colspan = '3'>No genres found.</td></tr>";
            } else {
                foreach ($genres as $genre) {
                    echo "
                        <tr onclick=\"window.location='genre_edit.php?id=" . htmlspecialchars($genre->genre_id) . "' \">
                            <td>" . htmlspecialchars($genre->genre_id) . "</td>
                            <td>" . htmlspecialchars($genre->name) . "</td>
                            <td>" . htmlspecialchars($genre->popularity) . "%</td>
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