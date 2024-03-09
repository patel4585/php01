<?php
    session_start();
    require_once("db.php");

    // Check whether the user has logged in or not.
    if (!isset($_SESSION["uid"])) {
        header("Location: Login_Page.php");
        exit();
    }

    function test_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    //Initiating some variables to use later.
    $errors = array();
    $uid = $_SESSION["uid"];
    $title = "";
    $joke_text = "";

    // Check whether the form was submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        //Collect and validate form inputs
        $title = test_input($_POST["title_input"]);
        $joke_text = test_input($_POST["text_area"]);
        $temp1 = $_POST["title_input"];
        $dataOK = TRUE;

        if(strlen($temp1) > 50){
            $errors["Title lenght"] = "Title lenght more than 50 characters";
            $dataOK = FALSE;
        }

        if($dataOK){
            try{
                $db = new PDO($attr, $db_user, $db_pwd, $options);

                $query = "INSERT INTO Joke_Posts (user_id, title, joke_text, joke_dt) VALUES ('$uid', '$title', '$joke_text', NOW())";
                $result = $db->exec($query);

                if(!$result){
                    $errors["Database Error"] = "Could not insert joke into table";
                } 
                //close the database
                $db = null;
            } catch(PDOException $e){
                throw new PDOException($e->getMessage(), (int)$e->getCode());
            }
        }

        if(!empty($errors)){
            foreach($errors as $error => $message){
                print("$error: $message \n<br/>");
            }
        }
    }

?>
<!DOCTYPE html>
<html lang="eng-US">
    <head>
        <title> Post Joke Page </title>
        <link rel="stylesheet" type="text/css" href="Style.css" />
        <script src="js/eventHandlers.js"></script>
    </head>

    <body>
        <div id="container-JL">
            <header id="header-JL">
                <h1> 
                    Post Joke Page 
                    <a class="logout-JL" href="logout.php" > Logout </a>
                    <a class="logout-JL" href="Joke_List_Page.php" > Home </a> <br />
                    <text id="user_name"> User currently logged in: <?= $_SESSION["username"] ?> </text>
                </h1>
            </header>

            <main id="main_left-JL">

            </main>

            <main id="main_center-JL">
                <form id="pj_form" action="" method="post">
                    <div class="session-JL">
                        <p><lable for="joke_title_input"> Title </lable></p>
                        <input class="input_PJ" type="text" id="joke_title_input" name="title_input" /> <br /> <br/>
                        <span id="counter" class="counter" ></span> 
                        <text id="joke_title_input_msg" class="error_msg">Title can not be empty</text>
                        <text id="joke_title_input_msg_2" class="error_msg">Title limit exceeded</text> <br /> <br />
                    </div>

                    <div class="session-JL">
                        <p> <lable for="text_joke"> Joke text </lable> </p> 
                        <textarea name="text_area" id="text_joke" name="text_joke" rows="15" cols="80"> </textarea> <br /> 
                        <text id="text_area_msg" class="error_msg">Text area can not be empty</text> <br /> <br />
                        <input class="input_PJ" type="submit" value="Submit" /> <br /> <br />
                    </div>

                </form>
            </main>

            <main id="main_right-JL">

            </main>

            <footer id="fotter-JL">

            </footer>

        </div>
        <script src="js/eventRegisterPostJokePage.js"></script>
    </body>
</html>