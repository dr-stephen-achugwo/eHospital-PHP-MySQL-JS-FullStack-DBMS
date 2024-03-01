<?php
    session_start();
    if (isset($_SESSION["user"]) && !empty($_SESSION["user"]) && $_SESSION['usertype'] == 'd') {
        include("../connection.php");
        $useremail = $_SESSION["user"];
        $userrow = $database->query("SELECT * FROM ehos_doctor WHERE = '$useremail'");
        $userfetch = $userrow->fetch_assoc();
        $userid = $userfetch["doctor_id"];
        $username = $userfetch["doctor_name"];
    } else {
        echo '<script>window.location.href = "../login.php";</script>';
        exit();
    }
?>
<?php

    include("../connection.php");
    $userrow = $database->query("SELECT * FROM ehos_doctor WHERE doctor_email='$useremail'");
    $userfetch=$userrow->fetch_assoc();
    $userid= $userfetch["doctor_id"];
    $username=$userfetch["doctor_name"];

    if (isset($_POST['submit2'])) {
        $doctorID = $userid;
        $title = $_POST['title'];
        $schedule_date = $_POST['schedule_date'];
        $schedule_time = $_POST['schedule_time'];
        $nop = $_POST['nop'];
        
        $smnt = $database->prepare("INSERT INTO ehos_schedule (doctor_id, title, schedule_date, schedule_time, nop) VALUES (?, ?, ?, ?, ?)");
        $smnt->bind_param("isssi", $doctorID, $title, $schedule_date, $schedule_time, $nop);
    
        if ($smnt->execute()) {
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
                  alertBox.innerHTML = "Session set successfully!";
                  document.body.appendChild(alertBox);
                  setTimeout(function() {
                      document.body.removeChild(alertBox);
                  }, 3000);
                </script>';
            echo '<script>location.replace("schedule.php");</script>';
        } else {
            echo "<div class='alert alert-danger'>Error: " . mysqli_error($database) . "</div>";
        }
    }        
    
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
    <link rel="stylesheet" href="../css/admin-mobile.css">
    <link rel="stylesheet" href="../css/schedule.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
        
    <title>Doctor_Sessions</title>
    
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
                        <td style="padding:0px; margin:0px">
                            <p class="profile-title"> <?php echo substr($username,0,50) ?> </p>
                            <p class="profile-subtitle"> <?php echo substr($useremail,0,50) ?> </p>
                        </td>
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
                        <div class="menu-btn"> <a href="patient.php" style="text-decoration: none;"> <p class="menu-text">Diagnoses</p> </a> </div>
                        <div class="menu-btn"> <a href="prescription.php" style="text-decoration: none;"> <p class="menu-text">Settings</p> </a> </div>
    </div>
</div>
        <div class="dash-body">
            
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
                <tr >
                    <td width="13%" >
                    <a href="javascript:history.go(-1)" ><button  class="login-btn btn-primary-soft btn btn-icon-back"  style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text"> Back </font></button></a>
                    </td>
                    <td>
                        <p style="font-size: 23px;padding-left:12px;font-weight: 600;">My Sessions </p>
                                           
                    </td>
                    <td width="15%">
                        <p class="heading-sub12" style="padding: 0;margin: 0;">
                        <?php 
                        date_default_timezone_set('Europe/Sofia');
                        $today = date('d.m.Y');
                        $stmt = $database->prepare("SELECT * FROM ehos_schedule WHERE doctor_id = ?");
                        $stmt->bind_param("i", $userid);
                        $stmt->execute();
                        $list110 = $stmt->get_result();                        
                        ?>
                        </p>
                    </td>
                </tr>           
                
                <tr>
                    <td colspan="4" style="padding-top:10px;width: 100%;" >
                    
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">My Sessions (<?php echo $list110->num_rows; ?>) </p>
                    </td>
                    
                </tr>
                
                <tr>
                    <td colspan="4" style="padding-top:0px;width: 100%;" >
                        <center>
                        <table class="filter-container" border="0" >
                        <tr>
                           <td width="10%">

                           </td> 
                        <td width="5%" style="text-align: center;">
                        Date:
                        </td>
                        <td width="30%">
                        <form action="" method="post">
                            
                            <input type="date" name="schedule_date" id="date" class="input-text filter-container-items" style="margin: 0;width: 95%;">

                        </td>
                        
                    <td width="12%">
                        <input type="submit"  name="filter" value=" Филтър" class=" btn-primary-soft btn button-icon btn-filter"  style="padding: 15px; margin :0;width:100%">
                        </form>
                    </td>

                    </tr>
                            </table>
                                                                        <form method="POST" action="">
                                                                        <div class="form-group">
                                                                             <label for="title">Session Name:</label>
                                                                             <input type="text" name="title" id="title">
                                                                        </div>
                                                                        <div class="form-group">
                                                                             <label for="schedule_time">Time:</label>
                                                                             <input type="time" name="schedule_time" id="schedule_time">
                                                                       </div>
                                                                        <div class="form-group">
                                                                            <label for="nop"> Number of Patient:</label>
                                                                            <input type="number" name="nop" id="nop" value="0" min="1" max="50">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="schedule_date">Date:</label>
                                                                            <input type="date" name="schedule_date" id="schedule_date">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <button type="submit" name="submit2" class="btn btn-primary">Assignment</button>
                                                                            <button type="reset" class="btn btn-secondary">Reset</button>
                                                                        </div>
                                                                    </form>
                        </center>
                    </td>
                    
                </tr>
                
                <?php

                $sqlmain= "SELECT ehos_schedule.schedule_id, ehos_schedule.title, ehos_doctor.doctor_name, ehos_schedule.schedule_date, ehos_schedule.schedule_time, ehos_schedule.nop FROM ehos_schedule INNER JOIN ehos_doctor on ehos_schedule.doctor_id = ehos_doctor.doctor_id WHERE ehos_doctor.doctor_id = $userid ";
                    if($_POST){
   
                        $sqlpt1="";
                        if(!empty($_POST["schedule_date"])){
                            $schedule_date=$_POST["schedule_date"];
                            $sqlmain.=" and ehos_schedule.schedule_date='$schedule_date' ";
                        }
                    }
                ?>                  
                <tr>
                   <td colspan="4">
                       <center>
                        <div class="abc scroll">
                        <table width="93%" class="sub-table scrolldown" border="0">
                        <thead>
                        <tr>
                                <th class="table-headin"> Session </th>         
                                <th class="table-headin"> Date and Time</th>
                                <th class="table-headin"> Maximum Number of Entries </th>     
                                <th class="table-headin"> Options </tr>
                        </thead>
                        <tbody>                      
                            <?php                             
                                $result= $database->query($sqlmain);
                                if($result->num_rows==0){
                                    echo '<tr>
                                    <td colspan="4">
                                    <br><br><br><br>
                                    <center>
                                    <br>
                                    <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">The system did not find what you were looking for!</p>
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
                                    $doctor_name=$row["doctor_name"];
                                    $schedule_date=$row["schedule_date"];
                                    $schedule_time=$row["schedule_time"];
                                    $nop=$row["nop"];
                                    echo '<tr>
                                        <td> &nbsp;'.
                                        substr($title,0,100)
                                        .'</td>
                                        
                                        <td style="text-align:center;">
                                            '.substr($schedule_date,0,10).' '.substr($schedule_time,0,5).'
                                        </td>
                                        <td style="text-align:center;">
                                            '.$nop.'
                                        </td>

                                        <td>
                                        <div style="display:flex;justify-content: center;">
                                        
                                        <a href="?action=view&id='.$schedule_id.'" class="non-style-link"><button  class="btn-primary-soft btn button-icon btn-view"  style="padding-left: 20px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;"><font class="tn-in-text">Review</font></button></a>
                                       &nbsp;&nbsp;&nbsp;
                                       <a href="?action=drop&id='.$schedule_id.'&name='.$title.'" class="non-style-link"><button  class="btn-primary-soft btn button-icon btn-delete"  style="padding-left: 20px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;"><font class="tn-in-text">Cancellation</font></button></a>
                                        </div>
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
            </table>
        </div>
    </div>
    
    <?php
    
    if($_GET){
        $id=$_GET["id"];
        $action=$_GET["action"];
        if($action=='drop'){
            $nameget=$_GET["name"];
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                        <h2> Are you sure?</h2>
                        <a class="close" href="schedule.php">&times;</a>
                        <div class="content">
                            Do you want to delete this record?<br>('.substr($nameget,0,100).').
                            
                        </div>
                        <div style="display: flex;justify-content: center;">
                        <a href="delete-session.php?id='.$id.'" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"<font class="tn-in-text">&nbsp; Да &nbsp;</font></button></a>&nbsp;&nbsp;&nbsp;
                        <a href="schedule.php" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"><font class="tn-in-text">&nbsp;&nbsp; Не &nbsp;&nbsp;</font></button></a>

                        </div>
                    </center>
            </div>
            </div>
            '; 
        }elseif($action=='view'){
            $sqlmain= "SELECT ehos_schedule.schedule_id, ehos_schedule.title, ehos_doctor.doctor_name, ehos_schedule.schedule_date, ehos_schedule.schedule_time, ehos_schedule.nop
            FROM ehos_schedule
            INNER JOIN ehos_doctor on ehos_schedule.doctor_id = ehos_doctor.doctor_id
            WHERE ehos_schedule.schedule_id=$id";

            $result= $database->query($sqlmain);
            $row=$result->fetch_assoc();
            $doctor_name=$row["doctor_name"];
            $schedule_id=$row["schedule_id"];
            $title=$row["title"];
            $schedule_date=$row["schedule_date"];
            $schedule_time=$row["schedule_time"];
               
            $nop=$row['nop'];

            $sqlmain12= "SELECT * FROM ehos_appointment 
            INNER JOIN ehos_patient on ehos_patient.patient_id = ehos_appointment.patient_id 
            INNER JOIN ehos_schedule on ehos_schedule.schedule_id = ehos_appointment.schedule_id WHERE ehos_schedule.schedule_id = $id;";
            $result12 = $database->query($sqlmain12);
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup" style="width: 70%;">
                    <center>
                        <h2></h2>
                        <a class="close" href="schedule.php">&times;</a>
                        <div class="content">                    
                        </div>
                        <div class="abc scroll" style="display: flex;justify-content: center;">
                        <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">                                            
                            <tr>
                                
                                <td class="label-td" colspan="2">
                                    <label for="title" class="form-label" style="font-weight: bold; font-size:20px">Saved Time: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    '.$title.'<br><br>
                                </td>
                                
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                <label for="title" class="form-label" style="font-weight: bold; font-size:20px"> Doctor: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                '.$doctor_name.'<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                <label for="title" class="form-label" style="font-weight: bold; font-size:20px"> Date: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                '.$schedule_date.'<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                <label for="title" class="form-label" style="font-weight: bold; font-size:20px"> Time: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                '.$schedule_time.'<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="spec" class="form-label"><b>Already enrolled Patients:</b> ('.$result12->num_rows."/".$nop.')</label>
                                    <br><br>
                                </td>
                            </tr>                        
                            <tr>
                            <td colspan="4">
                                <center>
                                 <div class="abc scroll">
                                 <table width="100%" class="sub-table scrolldown" border="0">
                                 <thead>
                                 <tr>   
                                        <th class="table-headin">  Patient  </th>
                                        <th class="table-headin"> Name </th>
                                        <th class="table-headin"> Enrollment Number </th>
                                        <th class="table-headin"> Phone </th>                                 
                                 </thead>
                                 <tbody>';            
                                         
                                         $result= $database->query($sqlmain12);
                
                                         if($result->num_rows==0){
                                             echo '<tr>
                                             <td colspan="7">
                                             <br><br><br><br>
                                             <center>                                           
                                             <br>
                                             <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)"> The system did not find what you were looking for! </p>
                                             </center>
                                             <br><br><br><br>
                                             </td>
                                             </tr>';
                                             
                                         }
                                         else{
                                         for ( $x=0; $x<$result->num_rows;$x++){
                                             $row=$result->fetch_assoc();
                                             $appointment_num=$row["appointment_num"];
                                             $patient_id=$row["patient_id"];
                                             $patient_name=$row["patient_name"];
                                             $patient_tel=$row["patient_tel"];
                                             
                                             echo '<tr style="text-align:center;">
                                                <td>
                                                '.substr($patient_id,0,15).'
                                                </td>
                                                 <td style="font-weight:600;padding:25px">'.
                                                 
                                                 substr($patient_name,0,25)
                                                 .'</td >
                                                 <td style="text-align:center;font-size:23px;font-weight:500; color: var(--btnnicetext);">
                                                 '.$appointment_num.'
                                                 
                                                 </td>
                                                 <td>
                                                 '.substr($patient_tel,0,25).'
                                                 </td>                                                                                           
                                             </tr>';
                                             
                                         }
                                     }                                                     
                                    echo '</tbody>
                
                                 </table>
                                 </div>
                                 </center>
                            </td> 
                         </tr>

                        </table>
                        </div>
                    </center>
                    <br><br>
            </div>
            </div>
            ';  
    }
}
    ?>

    </div>
</body>
</html>