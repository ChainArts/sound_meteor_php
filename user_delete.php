<?php
    include "functions.php";

    try {
        $sth = $dbh->prepare("DELETE FROM users WHERE user_id = ?");
        $sth->execute(array($_POST['user_id']));
        header("Location: usrmngmt.php?status=del_success");
    } 
    catch (Exception $e) {
        header("Location: usrmngmt.php?status=del_fail");
    }
?>