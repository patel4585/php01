<?php 
    require_once("db.php");

    //Setting database connection
    try{
        $db = new PDO($attr, $db_user, $db_pwd, $options);

        $query = "SELECT joke_id FROM Joke_Posts ORDER BY Joke_dt DESC LIMIT 1";
        $stmt = $db->query($query);
        $joke_id_temp = $stmt->fetchColumn();

        $joke_id = $_GET["jI"];

        if($joke_id == $joke_id_temp){
            echo (json_encode(null));
        } else{
            $difference = $joke_id_temp - $joke_id;

            $query = "SELECT user_id, joke_id, title, joke_text, joke_dt, average_rating FROM Joke_Posts ORDER BY Joke_dt DESC LIMIT $difference";
            $stmt = $db->query($query);

            // Create an empty array
            $jsonArray = array();
            $i = 0;

            foreach($stmt as $raw){
                $jsonArray[$i] = $raw;
                $i = $i + 1;
            }

            echo (json_encode($jsonArray));
        }
        $db = null;
    } catch(PDOException $e){
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
?>