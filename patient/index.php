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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/button.css">
    <link rel="stylesheet" href="../css/portal.css">
    <link rel="stylesheet" href="../css/mobi.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <title>Patient_Dashboard</title>
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
                            <a href="../logout.php"><input type="button" value="Logout" class="logout-btn btn-primary-soft btn"></a>
                        </div>
                     </div>
                    </div> 
                        </li>
                        <div class="menu-btn"> <a href="index.php"    style="text-decoration: none;"> <p class="menu-text">Begining</p> </a> </div>
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
                <div class="menu-btn"> <a href="index.php"    style="text-decoration: none;"> <p class="menu-text">Begining</p> </a> </div>
                        <div class="menu-btn"> <a href="doctors.php"  style="text-decoration: none;"> <p class="menu-text">Doctors</p> </a> </div>
                        <div class="menu-btn"> <a href="schedule.php" style="text-decoration: none;"> <p class="menu-text">Sessions</p> </a> </div>
                        <div class="menu-btn"> <a href="appointment.php" style="text-decoration: none;"> <p class="menu-text">Hours reserved</p> </a> </div>
                        <div class="menu-btn"> <a href="diagnoses.php" style="text-decoration: none;"> <p class="menu-text">Diagnoses</p> </a> </div>
                        <div class="menu-btn"> <a href="settings.php" style="text-decoration: none;"> <p class="menu-text">Settings</p> </a> </div>
    </div>
</div>
    <div class="dash-body" style="margin-top: 15px">
            <div class="date">  <p style="font-size: 23px;font-weight: 600;margin-left:20px; padding-left:20px"> Start</div>
                                <p class="heading-sub12" style="padding: 0;margin: 0;padding-right: 78px">
                                <?php 
                                date_default_timezone_set('Europe/Sofia'); 
                                $today = date('d.m.Y');
                                $patientrow = $database->query("SELECT * FROM  ehos_patient;");
                                $doctorrow = $database->query("SELECT * FROM  ehos_doctor;");
                                $appointmentrow = $database->query("SELECT * FROM  ehos_appointment WHERE appointment_date>='$today';");
                                $schedulerow = $database->query("SELECT * FROM  ehos_schedule WHERE schedule_date='$today';");
                                ?>
                                </p>
                <div>
                </div>
                <div>
                <div>
                    <td colspan="4">
                        <table border="0" width="100%">
                            <tr>
                                <td>
                                <p style="font-size: 20px;font-weight:600;padding-left: 40px;" class="anime">Your upcoming classes</p>
                                    <center>
                                        <div class="abc scroll" style="height: 250px;padding: 0;margin: 0;">
                                        <table width="95%" class="sub-table scrolldown" border="0" >
                                        <thead>      
                                        <tr>
                                        <th class="table-headin"> Reserved Class Number </th>
                                            <th class="table-headin">Session Name </th>
                                            <th class="table-headin">  Doctor</th>
                                            <th class="table-headin"> Date and Time</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        
                                            <?php
                                            $nextweek=date("d.m.Y",strtotime("+1 week"));
                                                $sqlmain= "SELECT * FROM ehos_schedule 
                                                INNER JOIN ehos_appointment on ehos_schedule.schedule_id=ehos_appointment.schedule_id 
                                                INNER JOIN ehos_patient on ehos_patient.patient_id = ehos_appointment.patient_id 
                                                INNER JOIN ehos_doctor on ehos_schedule.doctor_id = ehos_doctor.doctor_id  
                                                WHERE  ehos_patient.patient_id = $userid and ehos_schedule.schedule_date>='$today' 
                                                order by ehos_schedule.schedule_date asc";
                                                $result= $database->query($sqlmain);
                
                                                if($result->num_rows==0){
                                                    echo '<tr>
                                                    <td colspan="4">
                                                    <br><br><br><br>
                                                    <center>
                                                    <br>
                                                    <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">Ненамерени часове!</p>
                                                    <a class="non-style-link" href="schedule.php"><button  class="login-btn btn-primary-soft btn"  style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Изберете лекар &nbsp;</font></button>
                                                    </a>
                                                    </center>
                                                    <br><br><br><br>
                                                    </td>
                                                    </tr>';
                                                    
                                                }
                                                else{
                                                for ( $x=0; $x<$result->num_rows;$x++){
                                                    $row=$result->fetch_assoc();
                                                    $schedule_id=$row["schedule_id"];
                                                    $title=$row["title"];
                                                    $appointment_num=$row["appointment_num"];
                                                    $doctor_name=$row["doctor_name"];
                                                    $schedule_date=$row["schedule_date"];
                                                    $schedule_time=$row["schedule_time"];
                                                   
                                                    echo '<tr>
                                                        <td style="padding:30px;font-size:25px;font-weight:700;"> &nbsp;'.
                                                        $appointment_num
                                                        .'</td>
                                                        <td style="padding:20px;"> &nbsp;'.
                                                        substr($title,0,100)
                                                        .'</td>
                                                        <td>
                                                        '.substr($doctor_name,0,100).'
                                                        </td>
                                                        <td style="text-align:center;">
                                                            '.substr($schedule_date,0,10).' '.substr($schedule_time,0,5).'
                                                        </td>
                                                    </tr>';
                                                    
                                                }
                                            }      
                                            ?>
                                            </tbody>
                                        </table>
                                        </div>
                                        </center>
                                </td>
                            </tr>
                        </div>
                        </table>
                    </td>
                <tr>
            </table>
        </div>
    </div>
    <script>
        function show() {
    document.querySelector('.hamburger').classList.toggle('open');
    document.querySelector('.navigation').classList.toggle('active');
    }
    </script>
</body>
</html>