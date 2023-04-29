<?php
    session_start();
    ini_set('display_errors', true);
    $pagetitle = "";
    
    require "config.php";

    try {
        $dbh = new PDO($DSN, $DB_USER, $DB_PASS);
        $dbh->setAttribute(PDO::ATTR_ERRMODE,            PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

    } catch (Exception $e) {
        die ("Problem connecting to database $DB_NAME as $DB_USER: " . $e->getMessage() );
    }

    function convert($sec)
    {
        $min = intval($sec / 60);
        return $min . ':' . str_pad(($sec % 60), 2, '0', STR_PAD_LEFT);
    }

?>