<?php
require_once("db.php");

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data); //encodes
    return $data;
}

// Check whether the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST"){

    $errors = array();
    $dataOK = TRUE;

    // Get and validate the username and password fields
    $email = test_input($_POST["email_l"]);
    $emailRegex = "/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/";
    if (!preg_match($emailRegex, $email)) {
        $errors["email"] = "Invalid Email";
        $dataOK = FALSE;
    }

    $pwd = test_input($_POST["pwd"]);
    $temp1 = htmlspecialchars_decode($pwd);
    $pwdRegex = "/^[a-zA-Z0-9@$#!%*?&]{8,}$/";
    if (!preg_match($pwdRegex, $temp1)) {
        $errors["password"] = "Invalid Password";
        $dataOK = FALSE;
    }

    if($dataOK){
        try{
            $db = new PDO($attr, $db_user, $db_pwd, $options);

            $query = "SELECT user_id, username, date_of_birth, avatar_url FROM Users_Class WHERE email = '$email' AND password = '$pwd'";
            $result = $db->query($query);

            if(!$result){
                $errors["Database Error"] = "Could not retrieve user information";
            } elseif ($row = $result->fetch()) {
                session_start();

                $uid = $row["user_id"];
                $_SESSION["uid"] = $uid;
                $_SESSION["avatar_url"] = $row["avatar_url"];
                $_SESSION["username"] = $row["username"];
                $_SESSION["dob"] = $row["date_of_birth"];

                $db = null;
                header("Location: Joke_List_Page.php");
                exit();
            } else{
                $errors["Login Failed"] = "That username/password combination does not exist.";
            }
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    foreach($errors as $message) {
        echo $message . "<br />\n";
    }
}

?>

<!DOCTYPE html>
<html lang="eng-US">

<head>
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="Style.css" />
    <script src="js/eventHandlers.js"></script>
</head>

<body>
    <div id="container">
        <header id="header-auth">
            <h1>Login Page</h1>
        </header>
        <main id="main-left">

        </main>
        <main id="main-center">
            <form class="auth-form" name="login-form" id="login-form" action="" method="post">
                <p class="input-field">

                    <lable for="email_l"> Email </lable>
                    <input type="text" id="email_l" name="email_l" /> <br/> <br/>
                    <text id="email_l_error_msg" class="error_msg">Email is invalid</text>

                </p> 
                <p class="input-field">
            
                    <lable for="password"> Password </lable>
                    <input type="password" id="password" name="pwd" /> <br/> <br/>
                    <text id="pwd_l_error_msg_1" class="error_msg">Password is too short</text>
                    <text id="pwd_l_error_msg_2" class="error_msg">Password contains spaces</text>

                </p>
                <p class="input-field">

                    <input type="submit" id="login_button" value="Login" />

                </p>
            </form>
            <div class="foot-note">

                <p> Don't have an account?
                    <a id="foot_note_button" href="SignUp_Page.php"> SignUp </a>
                </p>
                <p> Demo login credentials </br>
                    username: ddd@uregina.ca, password: 12345678$
                </p>

            </div>
        </main>
        <main id="main-right">

        </main>
        <footer id="footer-auth">
            <p class="footer-text">  </p>
        </footer>
    </div>
    <script src="js/eventRegisterLogin.js"></script>
</body>

</html>