<?php 
require_once("db.php");

function test_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

//Initiating some variables to use later.
$errors = array();
$firstName = "";
$lastName = "";
$email = "";
$username = "";
$pwd = "";
$Cpwd = "";
$dob = "";
$avatar_url = "";

// Check whether the form was submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    //Collect and validate form inputs
    $firstName = test_input($_POST["first_name"]);
    $lastName = test_input($_POST["last_name"]);
    $email = test_input($_POST["email"]);
    $username = test_input($_POST["user_name"]);
    $pwd = test_input($_POST["pwd"]);
    $Cpwd = test_input($_POST["confirm_pwd"]);
    $dob = test_input($_POST["DOB"]);

    //Form field regular expressions
    $emailRegex = "/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/";
    $pwdRegex = "/^[a-zA-Z0-9@$#!%*?&]{8,}$/";
    $nameRegex = "/^[a-zA-Z]+$/";
    $unameRegex = "/[^\w]|\s/";
    $dobRegex = "/^\d{4}[-]\d{2}[-]\d{2}$/";

    //Validate the form inputs against their Regexes
    $dataOK = TRUE;
    if (!preg_match($nameRegex, $firstName)) {
        $errors["fname"] = "Invalid First Name";
        $dataOK = FALSE;
    }
    if (!preg_match($nameRegex, $lastName)) {
        $errors["lname"] = "Invalid Last Name";
        $dataOK = FALSE;
    }
    if (!preg_match($emailRegex, $email)) {
        $errors["email"] = "Invalid Email";
        $dataOK = FALSE;
    }
    if (preg_match($unameRegex, $username)) {
        $errors["username"] = "Invalid Username";
        $dataOK = FALSE;
    }
    $temp1 = htmlspecialchars_decode($pwd);
    if (!preg_match($pwdRegex, $temp1)) {
        $errors["password"] = "Invalid Password";
        $dataOK = FALSE;
    }
    if (!($pwd == $Cpwd)) {
        $errors["Cpassword"] = "Confirm password does not match with the previously entered password";
        $dataOk = FALSE;
    }
    if (!preg_match($dobRegex, $dob)) {
        $errors["dob"] = "Invalid DOB";
        $dataOK = FALSE;
    }

    // Declare $target_file here so we can use it later
    $target_file = "";
    if($dataOK){
        // Try to make a MySQL connection
        try {
            $db = new PDO($attr, $db_user, $db_pwd, $options);

            // Query to check if this email is already taken 
            $query = "SELECT COUNT(user_id) FROM Users_Class WHERE email = '$email'";
            $result = $db->query($query);
            $matches = $result->fetchColumn();

            // If the username is not already taken
            if($matches == 0){
                
                // Query to insert the user's details into the database
                $query = "INSERT INTO Users_Class (first_name, last_name, email, username, password, date_of_birth, avatar_url) VALUES ('$firstName', '$lastName', '$email', '$username', '$pwd', '$dob', 'avatar_stub')";
                $result = $db->exec($query);

                if(!$result){
                    $errors["Database Error:"] = "Failed to insert user";
                } else{
                    // Directory where the avatars will be uploaded.
                    $target_dir = "uploads/";
                    $uploadOk = TRUE;

                    // Fetch the image filetype
                    print_r($_FILES);
                    $imageFileType = strtolower(pathinfo($_FILES["profile_photo"]["name"],PATHINFO_EXTENSION));

                    // Grab the user_id for the last insert query.
                    $uid = $db->lastInsertId();

                    // Rename the user's image to "uploads/user_id.filetype" e.g: "uploads/12.jpg"
                    $target_file = $target_dir . $uid . "." . $imageFileType;
                    
                    // Check whether the file exists in the uploads directory
                    if (file_exists($target_file)) {
                        $errors["profile_photo"] = "Sorry, file already exists. ";
                        $uploadOk = FALSE;
                    }

                    // Check whether the file is not too large
                    if ($_FILES["profile_photo"]["size"] > 1000000) {
                        $errors["profile_photo"] = "File is too large. Maximum 1MB. ";
                        $uploadOk = FALSE;
                    }

                    // Check image file type
                    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                        $errors["profile_photo"] = "Bad image type. Only JPG, JPEG, PNG & GIF files are allowed. ";
                        $uploadOk = FALSE;
                    }

                    // Check if $uploadOk still TRUE after validations
                    if ($uploadOk) {
                        // Move the user's avatar to the uploads directory and capture the result as $fileStatus.
                        $fileStatus = move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file);

                        // Check $fileStatus:
                        if(!$fileStatus){
                            // The user's avatar file could not be moved
                            //remove the new user record
                            $errors["Server Error"] = "Avatar picture could not be moved to upload folder.";
                            $query = "DELETE FROM Users_Class WHERE user_id='$uid'";
                            $result = $db->exec($query);
                            if (!$result) {
                                $errors["Database Error"] = "could not delete user when avatar upload failed";
                            }
                            //close the database
                            $db = null;
                        } else{
                            // File moved, so update the avatar field on the new user record
                            $query = "UPDATE Users_Class SET avatar_url='$target_file' WHERE user_id='$uid'";
                            $result = $db->exec($query);
                            if (!$result) {
                                $errors["Database Error:"] = "could not update avatar_url";
                            } else {
                                // New user successfully created, so close the datanase and redirect the user to the login page.
                                $db = null;
                                header("Location: Login_Page.php");
                                exit();
                            }
                        }
                    } else{
                        // The user's avatar file should not be moved
                        // Remove the new user record
                        $query = "DELETE FROM Users_Class WHERE user_id='$uid'";
                        $result = $db->exec($query);
                        if (!$result) {
                            $errors["Database Error"] = "could not delete user when avatar upload was invalid";
                        }
                        //close the database
                        $db = null;
                    } 
                } // User inserted successfully
            } else{
                $errors["Account Taken"] = "A user with that email already exists.";
            }
        } catch(PDOException $e){
            // The email address was found in the Users table 
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    } // $dataOk was TRUE
    
    if(!empty($errors)){
        foreach($errors as $error => $message){
            print("$error: $message \n<br/>");
        }
    }

} // submit method was POST
?>
<!DOCTYPE html>
<html lang="eng-US">

<head>
    <title>SignUp Page</title>
    <link rel="stylesheet" type="text/css" href="Style.css" />
    <script src="js/eventHandlers.js"></script>
</head>

<body>
    <div id="container">
        <header id="header-auth">
            <h1>SignUp Page</h1>
        </header>
        <main id="main-left">

        </main>
        <main id="main-center">
            <form class="auth-form" id="signup-form" action="" method="post" enctype="multipart/form-data">
                <p class="input-field">

                    <lable for="first_name_input"> First Name </lable>
                    <input type="text" id="first_name_input" name="first_name" /><br/><br/>
                    <text id="fname_error_msg" class="error_msg">First name is invalid</text>

                </p>
                <p class="input-field">

                    <lable for="last_name_input"> Last Name </lable>
                    <input type="text" id="last_name_input" name="last_name" /><br/><br/>
                    <text id="lname_error_msg" class="error_msg">Last name is invalid</text>

                </p>
                <p class="input-field">

                    <lable for="email_s"> Email </lable>
                    <input type="text" id="email_s" name="email" /> <br/><br/>
                    <text id="email_s_error_msg" class="error_msg">Email is invalid</text>

                </p>
                <p class="input-field">

                    <lable for="user_name"> Username </lable> 
                    <input type="text" id="user_name" name="user_name" /><br/><br/>
                    <text id="uname_error_msg" class="error_msg">Username is invalid</text>

                </p>
                <p class="input-field">

                    <lable for="pwd_input"> Password </lable>
                    <input type="password" id="pwd_input" name="pwd" /><br/><br/>
                    <text id="password_error_msg_1" class="error_msg">Password is too short</text>
                    <text id="password_error_msg_2" class="error_msg">Password has no symbols</text>

                </p>
                <p class="input-field">

                    <lable for="confirm_pwd_input"> Confirm Password </lable>
                    <input type="password" id="confirm_pwd_input" name="confirm_pwd" /><br/><br/>
                    <text id="vp_error_msg" class="error_msg">Doesn't match with previously entered password</text>

                </p>
                <p class="input-field">

                    <lable for="DOB_input"> Date of Birth </lable>
                    <input type="date" id="DOB_input" name="DOB" /> <br/><br/>
                    <text id="DOB_input_error_msg" class="error_msg">Date of Birth is invalid</text>

                </p>
                <p class="input-field">

                    <lable for="profile_photo_input"> Avatar </lable>
                    <input type="file" id="profile_photo" name="profile_photo" accept="image/*"  /> <br/><br/>
                    <text id="avatar_error_msg" class="error_msg">Avatar is invalid</text>

                </p>

                <p class="input-field">

                     <input type="submit" id="submit_s" value="SignUp" />

                </p>
            </form>
            <div class="foot-note">

                <p><a id="foot_note_button" href="Login_Page.php"> Login Page </a></p>

            </div>
        </main>
        <main id="main-right">

        </main>
        <footer id="footer-auth">
            <p class="footer-text"></p>
        </footer>
    </div>
    <script src="js/eventRegisterSignup.js"></script>
</body>

</html>