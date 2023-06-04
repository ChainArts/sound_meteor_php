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
        $response = array('message' => 'Preference added successfully', 'status' => true);
        echo json_encode($response);
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
        $response = array('message' => 'Preference deleted successfully', 'status' => true);
        echo json_encode($response);
    }
    elseif(isset($_POST['action']) && $_POST['action'] == 'update'){
        if(isset($_POST['type'])&& $_POST['type'] === 'year'){
            $sth = $dbh->prepare("UPDATE user_pref_gen SET oldest_track_year = :new_val WHERE user_id = :user_id");
            $sth->bindParam(':user_id', $_SESSION['USER_ID']);
            $sth->bindParam(':new_val', $_POST['value']);
            $sth->execute();
        }
        else if(isset($_POST['type']) && $_POST['type'] == 'length'){
            $sth = $dbh->prepare("UPDATE user_pref_gen SET playlist_length = :new_val WHERE user_id = :user_id");
            $sth->bindParam(':user_id', $_SESSION['USER_ID']);
            $sth->bindParam(':new_val', $_POST['value']);
            $sth->execute();

        }
        $response = array('message' => 'Preference updated successfully', 'status' => true);
        echo json_encode($response);
        exit();
    }
    elseif(isset($_POST['action']) && $_POST['action'] == 'fill'){
        if(isset($_POST['songlist'])){
            try{
            foreach ($_POST['songlist'] as $item){
                $songsArray = $item['songsArray'];
                $genres = $item['styles'];

                $genreIds = [];

                foreach($genres as $genre){
                    $sth = $dbh->prepare("SELECT genre_id FROM genres WHERE name = :genre");
                    $sth->bindParam(':genre', $genre);
                    $sth->execute();
                    $existingGenre = $sth->fetch();

                    if(!$existingGenre){
                        $sth = $dbh->prepare("INSERT INTO genres (name) VALUES (:genre) RETURNING genre_id");
                        $sth->bindParam(':genre', $genre);
                        $sth->execute();
                        $newGenre = $sth->fetch();
                        $genreIds[] = $newGenre->genre_id;
                    }else{
                        $genreIds[] = $existingGenre->genre_id;
                    }
                }
                $dbh->beginTransaction();
                foreach($songsArray as $song)
                {
                    $sth = $dbh->prepare("INSERT INTO tracks (title, ytlink, sclink, discogs, albumcoverlink, year) VALUES (:title, :ytlink, :sclink, :discogs, :albumcoverlink, :year) RETURNING track_id");
                    $sth->bindParam(':title', $song['title']);
                    $sth->bindParam(':ytlink', $song['ytlink']);
                    $sth->bindParam(':sclink', $song['sclink']);
                    $sth->bindParam(':discogs', $song['discogs']);
                    $sth->bindParam(':albumcoverlink', $song['cover']);
                    $sth->bindParam(':year', $song['year']);
                    $sth->execute();
                    $track = $sth->fetch();
                    $trackId = $track->track_id;
                    foreach ($genreIds as $genreId) {
                        $sth = $dbh->prepare("INSERT INTO track_is_genre (track_id, genre_id) VALUES (:track_id, :genre_id)");
                        $sth->bindParam(':track_id', $trackId);
                        $sth->bindParam(':genre_id', $genreId);
                        $sth->execute();
                    }
                }
                $dbh->commit();
            }
            

            }
            catch(Exception $e){
                $dbh->rollBack();
                $error = array('error' => $e->getMessage(), 'status' => false, 'post_dump' => var_export($_POST, true));
                echo json_encode($error);
            }
            $response = array('message' => 'Songs inserted successfully', 'status' => true);
            echo json_encode($response);
        }
    }
    else{
        throw new Exception('Something went wrong');
    }
}
catch(Exception $e){
    $error = array('error' => $e->getMessage(), 'status' => false, 'post_dump' => var_export($_POST, true));
    echo json_encode($error);
}

?>