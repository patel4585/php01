//////////////////////////////////////////////////////////////////////////////

//                      Event Handlers for Login Page

//////////////////////////////////////////////////////////////////////////////

function validateEmail(mail){
    let mailRegEx = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;    //Checkes for valid email

    return (mailRegEx.test(mail));
}

function validatePwd(pwd){
    let pwdRegEx = /^[a-zA-Z0-9@#$!%*?&]{8,}$/;    //Checkes for spaces and length

    return (pwdRegEx.test(pwd));
}
 
function validateLogin(event){
    let temp1 = document.getElementById("email_l");
    let email = temp1.value;
    let temp2 = document.getElementById(temp1.id + "_error_msg");
    let formIsValid = true;

    if(!validateEmail(email)){
        console.log("'" + email + "' is not a valid email");
        temp2.style.display = "block";
        formIsValid = false;
    }
    else
        temp2.style.display = "none";

    let pwd = document.getElementById("password").value;
    let temp3 = document.getElementById("pwd_l_error_msg_1");
    let temp4 = document.getElementById("pwd_l_error_msg_2");
    let temp1RegEx = /.{8,}/;       //Checkes for the length
    let temp2RegEx = /\s/;          //Checkes for spaces

    if(!validatePwd(pwd)){
        console.log("'" + pwd + "' is not a valid password");
        formIsValid = false;

        if((!temp1RegEx.test(pwd)) && temp2RegEx.test(pwd)){
            temp3.style.display = "block";
            temp4.style.display = "block";
        }
        else if(!temp1RegEx.test(pwd)){
            temp4.style.display = "none";
            temp3.style.display = "block";
        }
        else{
            temp3.style.display = "none";
            temp4.style.display = "block";
        }
    }
    else{
        temp3.style.display = "none";
        temp4.style.display = "none";
    }

    if (!formIsValid) {
		event.preventDefault();
	} else {
		console.log("Validation successful, sending data to the server");
	}

}

//////////////////////////////////////////////////////////////////////////////

//                     Event Handlers for SignUp Page

//////////////////////////////////////////////////////////////////////////////

function validateName(name) {
	let nameRegEx = /^[a-zA-Z]+$/;

	if (nameRegEx.test(name))
		return true;
	else
		return false;
}

function validateUname(uname){
    let unameRegEx = /[^\w]|\s/;

    return (unameRegEx.test(uname));
}

function validateDob(dob){
    let dobRegEx = /^\d{4}[-]\d{2}[-]\d{2}$/;

    return (dobRegEx.test(dob));
}

function validateAvatar(avatar){
    let avatarRegEx = /^[^\n]+.[a-zA-Z]{3,4}$/;

    return (avatarRegEx.test(avatar));
}

function passwordLV(pwd){
    if(pwd.length < 8){
        console.log("Password too short");
        return true;
    }
    else
        return false;
}

function passwordSV(pwd){
    let positionRegEx = pwd.search(/[^\w]/);

    if(positionRegEx < 0){
        console.log("Password doesn't have any symbols");
        return true;
    }    
    else
        return false;
}

function validateSignup(event){
    //Checking first name
    let fname = document.getElementById("first_name_input").value;
    let temp1 = document.getElementById("fname_error_msg");
    let formIsValid = true;

    if(!validateName(fname)){
        console.log("'" + fname + "' is not a valid first name");
        temp1.style.display = "block";
        formIsValid = false;
    }
    else
        temp1.style.display = "none";

    //Checking last name
    let lname = document.getElementById("last_name_input").value;
    let temp2 = document.getElementById("lname_error_msg");

    if(!validateName(lname)){
        console.log("'" + lname + "' is not a valid last name");
        temp2.style.display = "block";
        formIsValid = false;
    }
    else
        temp2.style.display = "none";

    //Checking email
    let temp3 = document.getElementById("email_s");
    let email = temp3.value;
    let temp4 = document.getElementById(temp3.id + "_error_msg");

    if(!validateEmail(email)){
        console.log("'" + email + "' is not a valid email");
        temp4.style.display = "block";
        formIsValid = false;
    }
    else
        temp4.style.display = "none";

    //Checking username
    let uname = document.getElementById("user_name").value;
    let temp5 = document.getElementById("uname_error_msg");

    if(validateUname(uname) || uname.length == 0){
        console.log("'" + uname + "' is not a valid username");
        temp5.style.display = "block";
        formIsValid = false;
    }
    else
        temp5.style.display = "none";
    
    //Checking date of birth
    let dob = document.getElementById("DOB_input").value;
    let temp6 = document.getElementById("DOB_input_error_msg");

    if(!validateDob(dob)){
        console.log("'" + dob + "' is not a valid date of birth");
        temp6.style.display = "block";
        formIsValid = false;
    }
    else
        temp6.style.display = "none";

    //Checking avatar field
    let avatar = document.getElementById("profile_photo").value;
    let temp7 = document.getElementById("avatar_error_msg");

    if(!validateAvatar(avatar)){
        console.log("Avatar is invalid");
        temp7.style.display = "block";
        formIsValid = false;
    }
    else
        temp7.style.display = "none";

    //Checking password
    let pwd = document.getElementById("pwd_input").value;
    let temp8 = passwordLV(pwd);
    let temp9 = passwordSV(pwd);
    let temp10 = document.getElementById("password_error_msg_1");
    let temp11 = document.getElementById("password_error_msg_2");

    if(temp8 || temp9){
        console.log("'" + pwd + "' is not a valid password");
        formIsValid = false;

        if(temp8 && temp9){
            temp10.style.display = "block";
            temp11.style.display = "block";
        }
        else if(temp8){
            temp11.style.display = "none";
            temp10.style.display = "block";
        }
        else{
            temp10.style.display = "none";
            temp11.style.display = "block";
        }
    }
    else{
        temp10.style.display = "none";
        temp11.style.display = "none";
    }

    //Checking confirm password
    let vpassword = document.getElementById("confirm_pwd_input").value;
    let temp12 = document.getElementById("pwd_input");
    let temp13 = document.getElementById("vp_error_msg");
    let password = temp12.value;
    console.log("end");

    if(vpassword === password)
        temp13.style.display = "none";
    else{
        temp13.style.display = "block";
        formIsValid = false;
    }

    //Final check
    if (!formIsValid) 
    event.preventDefault();
    else 
    console.log("Validation successful, sending data to the server");
	
}

//////////////////////////////////////////////////////////////////////////////

//                    Event Handlers for Post Joke Page

//////////////////////////////////////////////////////////////////////////////

function titleHandler(event){
    let title = event.target.value;
    let temp = document.getElementById("joke_title_input_msg");
    let temp2 = document.getElementById("joke_title_input_msg_2");

    if(title.length == 0){
        temp.style.display = "block";
        temp.style.float = "left";
    }
    else{
        temp.style.display = "none";
    }

    if(title.length > 50){
        temp2.style.display = "block";
        temp2.style.float = "left";
    }
    else{
        temp2.style.display = "none";
    }
}

function text_boxHandler(event){
    let text_box = event.target.value;
    let temp = document.getElementById("text_area_msg");

    if(text_box.length == 0 || text_box == " "){
        temp.style.display = "block";
        temp.style.float = "left";
    }
    else{
        temp.style.display = "none";
    }
}

function charCounter(event){
    let length = event.target.value.length;
	document.getElementById("counter").innerHTML = (50-length) + ' characters left out of 50';

    let temp = document.getElementById("counter");
    temp.style.display = "block";
}

//////////////////////////////////////////////////////////////////////////////

//                   Event Handlers for Joke Detail Page

//////////////////////////////////////////////////////////////////////////////


function plus_1(event){
    let temp = document.getElementById("input_JD");
    if(typeof(temp.value) == "undefined")
        temp.value = 0;

    temp.value++;
    document.getElementById("submit_JD").click();
}

function minus_1(event){
    let temp = document.getElementById("input_JD");

    temp.value = temp.value - 1;
    document.getElementById("submit_JD").click();
}

function updateRating(event){
    let rating = document.getElementById("input_JD");

    if((1 <= rating.value) && (rating.value <= 5)){
        document.getElementById("error_msg_jd").style.visibility = "hidden";
        let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function (){
            if (xhr.readyState == 4 && xhr.status == 200){
                let updatedRating = JSON.parse(xhr.responseText);

                rating.value = updatedRating;
            }
        }

        xhr.open("GET", "ajax_backend_JD.php?rating=" + rating.value, true);
		xhr.send();
    } else{
        document.getElementById("error_msg_jd").style.visibility = "visible";
    }

    temp1 = document.getElementById("plus_1");
    temp2 = document.getElementById("minus_1");

    if(temp.value >= 5)
        temp1.disabled = true;
    else 
        temp1.disabled = false;

    if(temp.value > 1)
        temp2.disabled = false;
    else
        temp2.disabled = true;

}