<?php
include "functions.php";
if (!isset($_SESSION["USER"])) {
    header('Location: ./login.php');
}

if (isset($_GET['method'])) {
    try {
        if ($_GET['method'] == 'like') {
            $sth = $dbh->prepare("INSERT INTO savedplaylists (user_id, playlist_id) VALUES (?, ?)");
            $sth->execute(array($_SESSION['USER_ID'], $_GET['pid']));
        } else if ($_GET['method'] == 'dislike') {
            $sth = $dbh->prepare("DELETE FROM savedplaylists WHERE user_id = ? AND playlist_id = ?");
            $sth->execute(array($_SESSION['USER_ID'], $_GET['pid']));
        }
        header("Location: ./meteor.php?id=". $_GET['pid']);
    } catch (Exception $e) {
        header("Location: ./meteor.php?id=". $_GET['pid']);
    }
}