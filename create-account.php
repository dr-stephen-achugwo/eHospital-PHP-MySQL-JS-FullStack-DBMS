<?php

session_start();

include("connection.php");
require 'vendor/autoload.php';

$_SESSION["user"]="";
$_SESSION["usertype"]="";

date_default_timezone_set('Europe/Sofia');
$date = date('d.m.Y');

$_SESSION["date"]=$date;

if($_POST){

    $result= $database->query("SELECT * FROM webuser");

    $fname=$_SESSION['personal']['fname'];
    $lname=$_SESSION['personal']['lname'];
    $name=$fname." ".$lname;
    $city=$_SESSION['personal']['city'];
    $egn=$_SESSION['personal']['egn'];
    $dob=$_SESSION['personal']['dob'];

    $email = $_POST['email'];
    $tele = $_POST['tele'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    $email = mysqli_real_escape_string($database, $email);

    $check_email_query = "SELECT patient_email from ehos_patient WHERE patient_email=? LIMIT 1";
    $stmt = mysqli_prepare($database, $check_email_query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    $check_email_query = "SELECT email from ehos_webuser WHERE email=? LIMIT 1";
    $stmt = mysqli_prepare($database, $check_email_query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    $email_exists = mysqli_stmt_num_rows($stmt) > 0;
    mysqli_stmt_close($stmt);

    if ($email_exists) {
        $_SESSION['status'] = "The email has already been added to the system!";
        echo '<script>
            var alertBox = document.createElement("div");
            alertBox.style.position = "fixed";
            alertBox.style.top = "17%";
            alertBox.style.left = "50%";
            alertBox.style.fontSize = "22px";
            alertBox.style.transform = "translate(-50%, -50%)";
            alertBox.style.padding = "20px";
            alertBox.style.background = "white";
            alertBox.style.color = "black";
            alertBox.style.border = "1px solid cyan";
            alertBox.style.borderRadius = "5px";
            alertBox.style.textAlign = "center";
            alertBox.style.fontFamily = "Arial, sans-serif";
            alertBox.innerHTML = "Е-майлът вече е добавен в системата!";
            document.body.appendChild(alertBox);
            setTimeout(function() {
                document.body.removeChild(alertBox);
            }, 3000);
            setTimeout(function() {
                window.location="create-account.php";
            }, 3000);
        </script>';
        exit();
    }
    else
    {
        $query = "INSERT INTO ehos_patient (patient_email, patient_name, patient_password, patient_city, patient_egn, patient_dob, patient_tel) VALUES ('$email', '$name', '$password', '$city', '$egn', '$dob', '$tele')";
        $query_run = mysqli_query($database, $query);
        
        $query2 = "INSERT INTO ehos_webuser (email, usertype) VALUES ('$email', 'p')";
        $query_run2 = mysqli_query($database, $query2); 
        $usertype = 'p';
           

        if($query_run)
        {
            echo '<script>
            var alertBox = document.createElement("div");
            alertBox.style.position = "fixed";
            alertBox.style.top = "30%";
            alertBox.style.left = "50%";
            alertBox.style.fontSize = "36px"; // increased font size to 36px
            alertBox.style.transform = "translate(-50%, -50%)";
            alertBox.style.padding = "30px"; // increased padding to 30px
            alertBox.style.background = "white";
            alertBox.style.color = "black";
            alertBox.style.border = "1px solid cyan";
            alertBox.style.borderRadius = "5px";
            alertBox.style.textAlign = "center";
            alertBox.style.fontFamily = "Arial, sans-serif";
            alertBox.innerHTML = "Successful registration in eHospital!";
            document.body.appendChild(alertBox);
            setTimeout(function() {
                document.body.removeChild(alertBox);
                window.location="login.php";
            }, 3000);
        </script>';
        }
        else
        {
            echo '<script>
                    var alertBox = document.createElement("div");
                    alertBox.style.position = "fixed";
                    alertBox.style.top = "17%";
                    alertBox.style.left = "50%";
                    alertBox.style.fontSize = "22px";
                    alertBox.style.transform = "translate(-50%, -50%)";
                    alertBox.style.padding = "20px";
                    alertBox.style.background = "white";
                    alertBox.style.color = "black";
                    alertBox.style.border = "1px solid cyan";
                    alertBox.style.borderRadius = "5px";
                    alertBox.style.textAlign = "center";
                    alertBox.style.fontFamily = "Arial, sans-serif";
                    alertBox.innerHTML = "Registration failed, please try again.";
                    document.body.appendChild(alertBox);
                    setTimeout(function() {
                        document.body.removeChild(alertBox);
                        window.location="signup.php";
                    }, 3000);
                  </script>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link rel="stylesheet" href="css/main.css">  
    <link rel="stylesheet" href="css/signup.css">
        
    <title> eHospital|Registration </title>
    <style>
        .container{
            animation: transitionIn-X 0.5s;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
  <div class="container">
    <table style="width:69%; border:0px">
        <tr>
          <form>
            <tr>
                <td colspan="2">
                    <p class="header-text">Registration in eHospital</p>
                    <p class="sub-text">Please enter the additional details to create your account </p>
                </td>
            </tr>
            <tr>
                <form action="" method="POST" >
                <td class="label-td" colspan="2">
                    <label for="email" class="form-label">Email: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <input type="email" name="email" class="input-text" placeholder="Enter your email" required>
                </td>
                
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <label for="tele" class="form-label">Telephone: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <input type="tel" name="tele" class="input-text"  placeholder="ex: 0884159887" pattern="[0]{1}[0-9]{9}" >
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <label for="password" class="form-label">Create a password: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <input type="password" name="password " class="input-text" placeholder="Enter your password" required>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <label for="cpassword" class="form-label">Password confirmation: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <input type="password" name="cpassword" class="input-text" placeholder="Confirm your password" required>
                </td>
            </tr>     
            <tr>
                <td>
                    <input type="reset" value="Clear" class="login-btn btn-primary-soft btn" style="color:white" >
                </td>
                <td>
                    <input type="submit" value="Register" class="login-btn btn-primary btn" name="register_btn">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br>
                    <label for="" class="sub-text" style="font-weight: 280;">You already have an account&#63; </label>
                    <a href="login.php" class="hover-link1 non-style-link">Login</a>
                    <br><br><br>
                </td>
            </tr>
          </form>
        </tr>
    </table>
</div>
</body>
</html>