<?php
session_start();
include("connection.php");
date_default_timezone_set('Europe/Sofia');
$_SESSION["user"] = "";
$_SESSION["usertype"] = ""; 
$date = date('d.m.Y');
$_SESSION["date"] = $date;
?>

<?php
    if ($_POST) {
        $email = $_POST['useremail'];
        $password = $_POST['userpassword'];
        $error = '<label for="promter" class="form-label"></label>';

        $stmt = $database->prepare("SELECT * FROM ehos_webuser WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $utype = $result->fetch_assoc()['usertype'];

        if ($utype == 'p') {
            $stmt = $database->prepare("SELECT * FROM ehos_patient WHERE patient_email=? AND patient_password=?");
            $stmt->bind_param("ss", $email, $password);
            $stmt->execute();
            $checker = $stmt->get_result();

            if ($checker->num_rows == 1) {
                $_SESSION['user'] = $email;
                $_SESSION['usertype'] = 'p';                 
                echo '<script>window.location.href = "patient/index.php";</script>';
            } else {
                $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Невалиден е-майл или парола.</label>';
            }
        } elseif ($utype == 'a') {
            $stmt = $database->prepare("SELECT * FROM ehos_admin WHERE admin_email=? and admin_password=?");
            $stmt->bind_param("ss", $email, $password);
            $stmt->execute();
            $checker = $stmt->get_result();

            if ($checker->num_rows == 1) {
                $_SESSION['user'] = $email;
                $_SESSION['usertype'] = 'a';   
                echo '<script>window.location.href = "admin/index.php";</script>';
            } else {
                $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Невалиден е-майл или парола.</label>';
            }
        } elseif ($utype == 'd') {
            $stmt = $database->prepare("SELECT * FROM ehos_doctor WHERE doctor_email=? AND doctor_password=?");
            $stmt->bind_param("ss", $email, $password);
            $stmt->execute();
            $checker = $stmt->get_result();

            if ($checker->num_rows == 1) {
                $_SESSION['user'] = $email;
                $_SESSION['usertype'] = 'd';
                echo '<script>window.location.href = "doctor/index.php";</script>';
            } else {
                $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;"> Невалиден е-майл или парола. </label>';
            }
        }        
    } else {
        $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;"> Системата не намери акаунта за дадения е-майл. </label>';
    }
    }else{
        $error='<label for="promter" class="form-label">&nbsp;</label>';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="css/index.css"> 
    <link rel="stylesheet" href="./css/login.css"> 
    <link rel="stylesheet" href="css/main.css">  
    <link rel="stylesheet" href="assets/vendor/animate/animate.css">
    <link rel="stylesheet" href="css/preloader.css">
    <script src="js/loader.js"></script>
</head>

<body>
    
  <div id="preloader">
    <div id="loader"></div>
  </div>

  <center>
  <div class="container">
    <table style="margin:0; border:0; padding:0; width:60%;">
      <form action="" method="POST" >

            <tr>
                <td>
                    <p class="header-text">Welcome to eHospital</p>
                </td>
            </tr>

          <div class="form-body">
            <tr>
                <td>
                    <p class="sub-text">Login to continue.</p>
                </td>
            </tr>
            <tr>
                <td class="label-td">
                    <label for="useremail" class="form-label">E-mail: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td">
                    <input type="email" name="useremail" class="input-text" placeholder="" required>
                </td>
            </tr>
            <tr>
                <td class="label-td">
                    <label for="userpassword" class="form-label">Password: </label>
                </td>
            </tr>

            <tr>
                <td class="label-td">
                    <input type="Password" name="userpassword" class="input-text" placeholder="" required>
                </td>
            </tr>
            <tr>
                <td><br>
                <?php echo $error ?>
                </td>
            </tr>
            <tr>
                 <td>
                    <input type="submit" value="LOGIN" class="login-btn btn-primary btn" style="background-color: #64B9C5; border: #64B9C5; color:white;">
                </td>
                <tr>
                  <td>
                    <input type="button" value="Back" class="login-btn btn-primary-soft btn" onclick="window.location.href='index.html';" style="background-color: #57B0BE; border: #57B0BE; color:white; font-size:12px">
                    </a>
                  </td>
                </tr>
            </tr>
            
          </div>
          
            <tr>
                <td>
                    <br>
                    <label for="" class="sub-text" style="font-weight: 280;">Forgot your password&#63; </label>
                    <a href="#" class="hover-link1 non-style-link">Click here</a>
                    <br><br><br>
                </td>
            </tr> 

      </form>
    </table>
  </div>
</center>

</body>
</html>