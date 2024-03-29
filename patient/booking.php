<?php
    session_start();

    include("../connection.php");

    if(isset($_SESSION["user"])){
    if(($_SESSION["user"])=="" or $_SESSION['usertype']!='p'){
        echo '<script>window.location.href = "../login.php";</script>';
        exit();
    }else{
        $useremail=$_SESSION["user"];
    }
    }else{
       echo '<script>window.location.href = "../login.php";</script>';
       exit();
    }
?>
<?php

    $sqlmain = 'SELECT * FROM ehos_patient WHERE patient_email=?';
    $stmt = $database->prepare($sqlmain);
    $stmt->bind_param("s",$useremail);
    $stmt->execute();

    $userrow = $stmt->get_result();
    $userfetch=$userrow->fetch_assoc();

    $userid= $userfetch["patient_id"];
    $username=$userfetch["patient_name"];

date_default_timezone_set('Europe/Sofia');

$today = date('Y.m.d');


?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/button.css">
    <link rel="stylesheet" href="../css/portal.css">
    <link rel="stylesheet" href="../css/mobi.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <title>eHospital|Hours reserved</title>
</head>
<body>
    <div class="container">
    <div class="navigation">
    <div class="navbar-toggler">
    <button class="hamburger" onclick="show()">
        <div id="bar1" class="bar"> </div>
        <div id="bar2" class="bar"> </div>
        <div id="bar3" class="bar"> </div>
    </button>
    </div>
    <div class="menu-container">
    <nav>
                <ul>
                         <li>    
                             <div style="padding:10px">
                                <div class="profile-container">
                                <div style="width:30%; padding-left:20px;"> 
                                <img src="../img/user.png?v=2" alt="" width="100%" style="border-radius:50%">
                        </div>
                        <div style="padding:0px;margin:0px;">
                            <p class="profile-title">
                                <?php echo substr($username,0,50)?>             
                            </p>
                            <p class="profile-subtitle">
                                <?php echo substr($useremail,0,50)?>
                            </p>
                        </div>
                        <div>
                            <a href="../logout.php"><input type="button" value="logout" class="logout-btn btn-primary-soft btn"></a>
                        </div>
                     </div>
                    </div> 
                        </li>
                        <div class="menu-btn"> <a href="index.php"    style="text-decoration: none;"> <p class="menu-text">begining</p> </a> </div>
                        <div class="menu-btn"> <a href="doctors.php"  style="text-decoration: none;"> <p class="menu-text">Doctors</p> </a> </div>
                        <div class="menu-btn"> <a href="schedule.php" style="text-decoration: none;"> <p class="menu-text">Sessions</p> </a> </div>
                        <div class="menu-btn"> <a href="appointment.php" style="text-decoration: none;"> <p class="menu-text">Hours reserved</p> </a> </div>
                        <div class="menu-btn"> <a href="diagnoses.php" style="text-decoration: none;"> <p class="menu-text">Diagnoses</p> </a> </div>
                        <div class="menu-btn"> <a href="settings.php" style="text-decoration: none;"> <p class="menu-text">Settings</p> </a> </div>
                </ul>
        </nav>
        </div>
    </div>
    <div class="menu">
            <div class="menu-container">
                <div style="padding:10px">
                    <div class="profile-container">
                        <div style="width:30%; padding-left:20px;">
                            <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                        </div>
                        <div style="padding:0px;margin:0px;">
                            <p class="profile-title"><?php echo substr($username,0,50)  ?>..</p>
                            <p class="profile-subtitle"><?php echo substr($useremail,0,50)  ?></p>
                        </div>
                        <div>
                            <a href="../logout.php"><input type="button" value="Logout" class="logout-btn btn-primary-soft btn"></a>
                        </div>
                    </div>
                </div>
                <div class="menu-btn"> <a href="index.php"    style="text-decoration: none;"> <p class="menu-text">begining</p> </a> </div>
                        <div class="menu-btn"> <a href="doctors.php"  style="text-decoration: none;"> <p class="menu-text">Doctors</p> </a> </div>
                        <div class="menu-btn"> <a href="schedule.php" style="text-decoration: none;"> <p class="menu-text">Sessions</p> </a> </div>
                        <div class="menu-btn"> <a href="appointment.php" style="text-decoration: none;"> <p class="menu-text">Hours reserved</p> </a> </div>
                        <div class="menu-btn"> <a href="diagnoses.php" style="text-decoration: none;"> <p class="menu-text">Diagnoses</p> </a> </div>
                        <div class="menu-btn"> <a href="settings.php" style="text-decoration: none;"> <p class="menu-text">Settings</p> </a> </div>
    </div>
</div>
        <div class="dash-body">
            <table style="border-spacing: 0; border:0; width:100%; margin:0; padding:0;margin-top:25px;">
                <tr >
                    <td width="13%" >
                    <a href="javascript:history.go(-1)"><button class="login-btn btn-primary-soft btn" style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px">Back</button></a>
                    </td>
                    <td>
                            <form action="schedule.php" method="POST" class="header-search">

                                        <input type="search" name="search" class="input-text header-searchbar" placeholder="Търсене на лекар" list="doctors" >&nbsp;&nbsp;
                                        
                                        <?php
                                            echo '<datalist id="doctors">';
                                                $list11 = $database->query("SELECT DISTINCT doctor_id, doctor_name FROM ehos_doctor;");
                                                $list12 = $database->query("SELECT DISTINCT title FROM ehos_schedule GROUP BY title;");

                                            for ($y=0;$y<$list11->num_rows;$y++)
                                            {
                                                $row00=$list11->fetch_assoc();
                                                $d=$row00["doctor_name"];
                                              
                                                echo "<option value='$d'><br/>";                                             
                                            };
                                            for ($y=0;$y<$list12->num_rows;$y++)
                                            {
                                                $row00=$list12->fetch_assoc();
                                                $d=$row00["title"];
                                               
                                                echo "<option value='$d'><br/>";
                                            };

                                        echo ' </datalist>';
                                         ?>

                                        <input type="Submit" value="Search" class="login-btn btn-primary btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
                                        </form>
                    </td>
                </tr>            
                <tr>
                    <td colspan="4" style="padding-top:10px;width: 100%;" >  
                    </td>                 
                </tr>         
                <tr>
                   <td colspan="4" style="text-align: left;">
                        <div class="abc scroll">
                        <table width="100%" class="sub-table scrolldown" style="padding: 50px;border:none;  border:0;">
                            
                        <tbody>
                        
                            <?php
                            
                            if(($_GET)){
                                                               
                                if(isset($_GET["id"])){
                                    
                                    $id=$_GET["id"];

                                    $sqlmain= "SELECT * FROM ehos_schedule 
                                    inner join doctor on ehos_schedule.doctor_id=doctor.doctor_id 
                                    where ehos_schedule.schedule_id=? 
                                    order by ehos_schedule.schedule_date desc";
                                    $stmt = $database->prepare($sqlmain);
                                    $stmt->bind_param("i", $id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $row=$result->fetch_assoc();
                                    $schedule_id=$row["schedule_id"];
                                    $title=$row["title"];
                                    $doctor_name=$row["doctor_name"];
                                    $doctor_email=$row["doctor_email"];
                                    $schedule_date=$row["schedule_date"];
                                    $schedule_time=$row["schedule_time"];
                                    $sql2="SELECT * FROM ehos_appointment where schedule_id=$id";
                                    $result12= $database->query($sql2);
                                    $appointment_num=($result12->num_rows)+1;
                                    
                                    echo '
                                        <form action="booking-complete.php" method="post">
                                            <input type="hidden" name="schedule_id" value="'.$schedule_id.'" >
                                            <input type="hidden" name="appointment_num" value="'.$appointment_num.'" >
                                            <input type="hidden" name="date" value="'.$today.'" >                                                                   
                                    ';                                  
                                    echo '
                                    <td style="width: 50%;" rowspan="2">
                                            <div  class="dashboard-items search-items"  >
                                            
                                                <div style="width:100%">
                                                        <div class="h1-search" style="font-size:25px;">
                                                            Details of the reserved time
                                                        </div><br><br>
                                                        <div class="h3-search" style="font-size:18px;line-height:30px">
                                                            Doctor:  &nbsp;&nbsp;<b>'.$doctor_name.'</b><br>
                                                            Email:  &nbsp;&nbsp;<b>'.$doctor_email.'</b> 
                                                        </div>
                                                        <div class="h3-search" style="font-size:18px;">
                                                          
                                                        </div><br>
                                                        <div class="h3-search" style="font-size:18px;">
                                                            Number: '.$title.'<br>
                                                            Date: '.$schedule_date.'<br>
                                                            Time: '.$schedule_time.'<br>
                                                        </div>
                                                        <br>                                                       
                                                </div>                                                       
                                            </div>
                                        </td>                                     
                                        <td style="width: 25%;">
                                            <div  class="dashboard-items search-items"  >
                                            
                                                <div style="width:100%;padding-top: 15px;padding-bottom: 15px;">
                                                        <div class="h1-search" style="font-size:20px;line-height: 35px;margin-left:8px;text-align:center;">
                                                            Your number
                                                        </div>
                                                        <center>
                                                        <div class=" dashboard-icons" style="margin-left: 0px;width:90%;font-size:70px;font-weight:800;text-align:center;color:#333;background-color: var(--btnice)">'.$appointment_num.'</div>
                                                    </center>
                                                       
                                                        </div><br>
                                                        
                                                        <br>
                                                        <br>
                                                </div>
                                                        
                                            </div>
                                        </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="Submit" class="login-btn btn-primary btn btn-book" style="margin-left:10px;padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;width:95%;text-align: center;" value="Book an appointment" name="booknow"></button>
                                            </form>
                                            </td>
                                        </tr>
                                        '; 
                                }
                            }                           
                            ?>
                            </tbody>
                        </table>
                        </div>
                   </td> 
                </tr>                   
            </table>
        </div>
    </div>   
    </div>
</body>
</html>