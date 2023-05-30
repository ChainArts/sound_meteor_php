<?php
include "functions.php";

try {
    $sth = $dbh->prepare("SELECT * FROM tracks  WHERE genre_id = 1 Order by random() limit 3");
    $sth->execute(array());
    $tracks = $sth->fetchAll();

    if (empty($tracks)) {
        header("Location: meteor.php?status=gen_fail");
    } else {
        foreach ($tracks as $track) {
            echo "
                <tr onclick=\"window.location='song_details.php?id=" . htmlspecialchars($track->track_id) . "' \">
                    <td>" . htmlspecialchars($track->track_id) . "</td>
                    <td>" . htmlspecialchars($track->band) . "</td>
                    <td>" . htmlspecialchars($track->name) . "</td>
                    <td>" . htmlspecialchars($track->album) . "</td>
                    <td>" . htmlspecialchars(convert($track->runtime)) . "</td>
                </tr>";
        }
    }
} catch (Exception $e) {
    header("Location: meteor.php?status=gen_fail");
}
?>