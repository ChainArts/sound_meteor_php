<?php
    include "functions.php";
    if (!isset($_SESSION["USER"])) {
        header('Location: schlingel.php');
    }

    try {
        $sth = $dbh->prepare("DELETE FROM user_pref_genre WHERE genre_id = ? AND user_id = ?");
        $sth->execute(array($_POST['genre_id'], $_POST['user_id']));

        $sth = $dbh->prepare("DELETE FROM user_pref_mood WHERE mood_id = ? AND user_id = ?");
        $sth->execute(array($_POST['mood_id'], $_POST['user_id']));
        
        header("Location: pref.php?status=del_success");
    } 
    catch (Exception $e) {
        header("Location: pref.php?status=del_fail");
    }
?>