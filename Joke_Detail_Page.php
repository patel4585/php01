<?php 
    session_start();
    require_once("db.php");
    
    // Check whether the user has logged in or not.
    if (!isset($_SESSION["uid"])) {
        header("Location: Login_Page.php");
        exit();
    }

    //Initiating some variables to use later.
    $errors = array();
    $uid = $_SESSION["uid"];

    $i = $_GET["i"];
    $temp = $_SESSION["uid_array"];
    $uid_C = $temp[$i];    //Here C reprsents creater of the joke.

    $temp = $_SESSION["joke_id_array"];
    $joke_id = $temp[$i];
    $_SESSION["joke_id"] = $joke_id;

    //Setting database connection
    try{
        $db = new PDO($attr, $db_user, $db_pwd, $options);
    } catch(PDOException $e){
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }

    //Finding info about Writer of the joke
    $query = "SELECT username, avatar_url FROM Users_Class WHERE user_id = '$uid_C'";
    $result = $db->query($query);
    $temp = $result->fetch();
    $username_C = $temp["username"];
    $avatar_url_C = $temp["avatar_url"];

    //Finding info about the joke
    $query = "SELECT title, joke_text, joke_dt, average_rating FROM Joke_Posts WHERE user_id = '$uid_C' AND joke_id = '$joke_id'";
    $result = $db->query($query);
    $temp = $result->fetch();
    $title = $temp["title"];
    $joke_text = $temp["joke_text"];
    $joke_dt = $temp["joke_dt"];
    $average_rating = $temp["average_rating"];

    //Finding info about ratings
    $query = "SELECT rating_value FROM Ratings WHERE user_id = '$uid' AND joke_id = '$joke_id'";
    $result = $db->query($query);
    if($result != false)
        $rating = $result->fetchColumn();
    
?>
<!DOCTYPE html>
<html lang="eng-US">
    <head>
        <title> Joke Detail Page </title>
        <link rel="stylesheet" type="text/css" href="Style.css" />
        <script src="js/eventHandlers.js"></script>
    </head>

    <body>
        <div id="container-JL">
            <header id="header-JL">
                <h1> 
                    Joke Detail Page
                    <a class="logout-JL" href="logout.php" > Logout </a>
                    <a class="logout-JL" href="Joke_List_Page.php" > Home </a> <br />
                    <text id="user_name"> User currently logged in: <?=$_SESSION["username"]?> </text>
                </h1>
            </header>

            <main id="main_left-JL">

            </main>

            <main id="main_center-JL">
                <div class="session-JL">
                    <p>
                        <strong> Info about the writer of the joke: </strong> <br/>
                        <img width="40" height="40" src="<?=$avatar_url_C?>" alt="Avatar of Writer" /><br/>
                        Username: <?= $username_C ?> <br/>
                    </p>
                    <p>
                        Current average rating of the joke: <?= $average_rating ?> 
                        <span> &nbsp&nbsp&nbsp&nbsp *average rating will only change once you visit joke list page </span> <br/><br/>

                        If you would like to add/change your rating, please input it in the input box here <br/>
                        <button class="buttons" id="plus_1">+1</button>

                        <?php if(isset($rating)) { ?>
                        <input id="input_JD" value='<?= $rating ?>' type="number" min="1" max="5" name="rating_input" /> 
                        <?php } else {?>
                        <input id="input_JD" placeholder="NA" value="" type="number" min="1" max="5" name="rating_input" /> 
                        <?php } ?>

                        <button class="buttons" id="minus_1">-1</button> <span> *rating ranges from 1 to 5 </span> 
                        <span class="hidden_EM" id="error_msg_jd"> INVALID RATING </span> <br />
                        <input type="submit" id="submit_JD" value="Submit" /> <br/><br/>

                        <script src="js/eventRegisterJokeDetailPage_2.js"></script>
                    </p>
                </div>

                <div class="session-JL">
                    <h3> Joke detail </h3>
                    <p>
                        <p> Title: <?= $title ?> </p>
                        <p> Joke text: <br/> <?= $joke_text ?> </p>
                    </p>
                </div>
            </main>

            <main id="main_right-JL">

            </main>

            <footer id="fotter-JL">

            </footer>

        </div>
        <script src="js/eventRegisterJokeDetailPage.js"></script>
    </body>
</html>