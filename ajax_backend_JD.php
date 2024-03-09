<?php 
    session_start();
    require_once("db.php");

    //Setting database connection
    try{
        $db = new PDO($attr, $db_user, $db_pwd, $options);

        $rating = $_GET["rating"];
        $uid = $_SESSION["uid"];
        $joke_id = $_SESSION["joke_id"];

        $query = "SELECT COUNT(*) FROM Ratings WHERE user_id='$uid' AND joke_id='$joke_id'";
        $stmt = $db->query($query);
        $count = $stmt->fetchColumn();

        if($count == 0){    
            $query = "INSERT INTO Ratings (joke_id, user_id, rating_value) VALUES ('$joke_id', '$uid', '$rating')";
            $result = $db->exec($query);
        } else if($count == 1){
            $query = "UPDATE Ratings SET rating_value = '$rating' WHERE user_id = '$uid' AND joke_id = '$joke_id'";
            $result = $db->exec($query);
        } else{
            $rating = null;
        }

        print (json_encode($rating));
        $db = null;

    } catch(PDOException $e){
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
?>