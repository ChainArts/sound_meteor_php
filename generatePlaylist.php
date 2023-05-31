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
        }
    }
} catch (Exception $e) {
    header("Location: meteor.php?status=gen_fail");
}
?>