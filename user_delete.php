<?php
    include "functions.php";

    $id = $_POST['user_id'];

    try {
        $sth = $dbh->prepare("DELETE FROM users WHERE user_id = ?");
        $sth->execute(array($id));
        header("Location: usrmngmt.php?status=del_success");
    } 
    catch (Exception $e) {
        header("Location: usrmngmt.php?status=del_fail");
        //echo ($e->getMessage());    
    }
?>