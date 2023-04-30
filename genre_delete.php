<?php
    include "functions.php";
    if (!isset($_SESSION["USER"])) {
        header('Location: schlingel.php?');
    }

    try {
        $sth = $dbh->prepare("DELETE FROM genres WHERE genre_id = ?");
        $sth->execute(array($_POST['genre_id']));
        header("Location: genres.php?status=del_success");
    } 
    catch (Exception $e) {
        header("Location: genres.php?status=del_fail");
    }
?>