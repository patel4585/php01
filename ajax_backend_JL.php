<?php 
    require_once("db.php");

    //Setting database connection
    try{
        $db = new PDO($attr, $db_user, $db_pwd, $options);

        $query = "SELECT joke_id, AVG(rating_value) FROM Ratings GROUP BY joke_id";
        $stmt = $db->query($query);

        // Create an empty array
        $jsonArray = array();
        $i = 0;

        foreach($stmt as $raw){
            $jsonArray[$i] = $raw;
            $i = $i + 1;
        }

        echo (json_encode($jsonArray));
        $db = null;

    } catch(PDOException $e){
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
?>