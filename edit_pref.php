<?php
include "functions.php";

$_POST = json_decode(file_get_contents('php://input'), true);

try{

    if(isset($_POST['action']) && $_POST['action'] == 'add'){
        if(isset($_POST['type'])&& $_POST['type'] == 'genre'){
            $sth = $dbh->prepare("INSERT INTO user_pref_genre (user_id, genre_id) VALUES (:user_id, :genre_id)");
            $sth->bindParam(':user_id', $_SESSION['USER_ID']);
            $sth->bindParam(':genre_id', $_POST['pref_id']);
            $sth->execute();
        }
        else if(isset($_POST['type']) && $_POST['type'] == 'mood'){
            $sth = $dbh->prepare("INSERT INTO user_pref_mood (user_id, mood_id) VALUES (:user_id, :mood_id)");
            $sth->bindParam(':user_id', $_SESSION['USER_ID']);
            $sth->bindParam(':mood_id', $_POST['pref_id']);
            $sth->execute();

        }
    }
    elseif(isset($_POST['action']) && $_POST['action'] == 'delete'){
        if(isset($_POST['type'])&& $_POST['type'] === 'genre'){
            $sth = $dbh->prepare("DELETE FROM user_pref_genre WHERE user_id = :user_id and genre_id = :genre_id");
            $sth->bindParam(':user_id', $_SESSION['USER_ID']);
            $sth->bindParam(':genre_id', $_POST['pref_id']);
            $sth->execute();
        }
        else if(isset($_POST['type']) && $_POST['type'] == 'mood'){
            $sth = $dbh->prepare("DELETE FROM user_pref_mood WHERE user_id = :user_id and mood_id = :mood_id");
            $sth->bindParam(':user_id', $_SESSION['USER_ID']);
            $sth->bindParam(':mood_id', $_POST['pref_id']);
            $sth->execute();

        }
        $response = array('message' => 'Preferences updated successfully', 'status' => true);
        echo json_encode($response);
    }
    
    else{
        throw new Exception('Something went wrong');
    }


}
catch(Exception $e){
    $error = array('error' => $e->getMessage());
    echo json_encode($error);
    echo($e->getMessage());
}

?>