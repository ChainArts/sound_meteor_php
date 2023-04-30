<?php
    include "functions.php";
    if (!isset($_SESSION["USER"])) {
        if($_SESSION["USER"] != "admin")
        {
            header('Location: schlingel.php?msg=no-access');
        }
        header('Location: schlingel.php');
    }

    if(isset ($_POST['delete'])){

 

    try {
        $sth = $dbh->prepare("DELETE FROM users WHERE user_id = ?");
        $sth->execute(array($_POST['user_id']));
        header("Location: usrmngmt.php?status=del_success");
    } 
    catch (Exception $e) {
        header("Location: usrmngmt.php?status=del_fail");
    }
}
?>