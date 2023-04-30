<?php
    include "functions.php";

    try {
        $sth = $dbh->prepare("DELETE FROM moods WHERE mood_id = ?");
        $sth->execute(array($_POST['mood_id']));
        header("Location: moods.php?status=del_success");
    } 
    catch (Exception $e) {
        header("Location: moods.php?status=del_fail");
    }
?>