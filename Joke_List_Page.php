<?php 
    session_start();
    require_once("db.php");

    if (!isset($_SESSION["uid"])) {
        header("Location: Login_Page.php");
        exit();
    } else {
        $uid = $_SESSION["uid"];
        $username = $_SESSION["username"];
        $avatar_url = $_SESSION["avatar_url"];
        $dob = $_SESSION["dob"];
    }

    $uid_array = array();
    $joke_id_array = array();

    // Connect to the database and verify the connection
    try {
        $db = new PDO($attr, $db_user, $db_pwd, $options);
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }

    // Query to fetch all recent joke posts (up to 20).
    $query = "SELECT title, joke_text, joke_dt, average_rating, joke_id, user_id FROM Joke_Posts ORDER BY joke_dt DESC LIMIT 20";
    $result = $db->query($query);

    function findUsername($uid_temp, $db){
        $query = "SELECT username FROM Users_Class WHERE user_id = '$uid_temp'";
        $result_2 = $db->query($query);
        $username_creater = $result_2->fetchColumn();

        return $username_creater;
    }

    function findUrl($uid_temp, $db){
        $query = "SELECT avatar_url FROM Users_Class WHERE user_id = '$uid_temp'";
        $result_2 = $db->query($query);
        $username_creater_url = $result_2->fetchColumn();

        return $username_creater_url;
    }

    function findAverageRating($joke_id, $db){
        $query = "SELECT rating_value FROM Ratings WHERE joke_id = '$joke_id'";
        $stmt = $db->query($query);
        $total = 0;
        $i = 0;

        foreach($stmt as $row){
            if(!(is_null($row["rating_value"]))){
                $total += $row["rating_value"];
                $i = $i + 1;
            }
        }

        if($i != 0)
            return round(($total/$i), 2);
        else
            return "Not Rated Yet";
    }
?>
<!DOCTYPE html>
<html lang="eng-US">
    <head>
        <title> Joke List Page </title>
        <link rel="stylesheet" type="text/css" href="Style.css" />
        <script src="js/eventHandlers_JL.js"></script>
    </head>

    <body>
        <div id="container-JL">
            <header id="header-JL">
                <h1> 
                    Joke List Page 
                    <a class="logout-JL" href="logout.php" > Logout </a><br />
                    <text id="user_name"> User currently logged in: <?= $username ?> </text>
                </h1>
            </header>

            <main id="main_left-JL">

            </main>

            <main id="main_center-JL">
                <form action="">
                    <div>
                        <input id="search-JL" type="text" placeholder="Search.." name="search" />
                        <button id="go-JL" type="submit"> Go </button>
                    </div> <br />
                </form>

                <h2> 
                    Joke List 
                    <a class="logout-JL" ID="Post-JL" href="Post_Joke_Page.php"> POST </a>
                </h2>

                <div class="session-JL">

                </div>

                <?php
                // Loop over the result set 
                $i = 1;
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                ?>

                    <div class="session-JL" id="JL_block<?=$i?>">
                        <p> <a class="joke_name" href="Joke_Detail_Page.php?i=<?=$i?>"> <?= $row["title"] ?> </a></p>
                        <p> <?= $row["joke_text"] ?> <br/><br/> Created by: <br/>

                        <?php $avatar_url_temp = findUrl($row["user_id"], $db); ?>
                        <img width="40" height="40" src="<?=$avatar_url_temp?>" alt="Avatar of User" /><br/>

                        <?php $username_temp = findUsername($row["user_id"], $db); ?>
                        Username: <?= $username_temp ?> <br/>

                        Ttimestamp when the joke posted: <?= $row["joke_dt"] ?> <br/>

                        <?php $averageRating_temp = findAverageRating($row["joke_id"], $db) ?>
                        Average rating of the joke: <span id="aRating_<?= $row["joke_id"] ?>"> <?= $averageRating_temp ?> </span> <br/>

                        <?php 
                            if((!(is_null($averageRating_temp))) && ($averageRating_temp != "Not Rated Yet")){
                                $temp = $row["joke_id"];
                                $query = "UPDATE Joke_Posts SET average_rating = '$averageRating_temp' WHERE joke_id = '$temp'";
                                $result_3 = $db->exec($query);
                            }
                        ?>
                        </p>
                    </div>

                    <?php 
                        $uid_array[$i] = $row["user_id"];
                        $joke_id_array[$i] = $row["joke_id"];
                        $i = $i + 1;
                    ?>
                <?php 
                }

                $_SESSION["uid_array"] = $uid_array;
                $_SESSION["joke_id_array"] = $joke_id_array;
                ?>

            <div id="jsVar" style="display: none;">
                <?php 
                    echo htmlspecialchars($joke_id_array[1]);
                ?>
            </div>

            </main>

            <main id="main_right-JL">

            </main>

            <footer id="fotter-JL">

            </footer>
        </div>
    </body>
</html>