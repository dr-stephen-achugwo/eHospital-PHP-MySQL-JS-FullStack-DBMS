<?php
    session_start();
    if (isset($_SESSION["user"]) && !empty($_SESSION["user"]) && $_SESSION['usertype'] == 'd') {
        include("../connection.php");
        $useremail = $_SESSION["user"];
        $userrow = $database->query("SELECT * FROM ehos_doctor WHERE doctor_email = '$useremail'");
        $userfetch = $userrow->fetch_assoc();
        $userid = $userfetch["doctor_id"];
        $username = $userfetch["doctor_name"];
    } else {
        echo '<script>window.location.href = "../login.php";</script>';
        exit();
    }
?>
<?php
if (isset($_POST['submit'])) {
      $patientName = $_POST['patient_id'];
      $diagnosisName = $_POST['diagnosis_id'];
      $medicationName = $_POST['medication_id'];
      $prescriptionDate = $_POST['prescription_date'];
    
      $patientQuery = "SELECT patient_id FROM ehos_patient WHERE patient_name=?";
      $diagnosisQuery = "SELECT diagnosis_id FROM ehos_diagnoses WHERE diagnosis_name=?";
      $medicationQuery = "SELECT medication_id FROM ehos_medications WHERE medication_name=?";
    
      $patientStmt = $database->prepare($patientQuery);
      $patientStmt->bind_param("s", $patientName);
      $patientStmt->execute();
      $patientResult = $patientStmt->get_result();
    
      $diagnosisStmt = $database->prepare($diagnosisQuery);
      $diagnosisStmt->bind_param("s", $diagnosisName);
      $diagnosisStmt->execute();
      $diagnosisResult = $diagnosisStmt->get_result();
    
      $medicationStmt = $database->prepare($medicationQuery);
      $medicationStmt->bind_param("s", $medicationName);
      $medicationStmt->execute();
      $medicationResult = $medicationStmt->get_result();
    
      if ($patientResult && $diagnosisResult && $medicationResult) {
          $patientId = mysqli_fetch_assoc($patientResult)['patient_id'];
          $diagnosisId = mysqli_fetch_assoc($diagnosisResult)['diagnosis_id'];
          $medicationId = mysqli_fetch_assoc($medicationResult)['medication_id'];
          $doctorID = $userid;
    
          $sql = $database->prepare("INSERT INTO ehos_prescriptions (patient_id, diagnosis_id, medication_id, prescription_date, doctor_id) VALUES (?, ?, ?, ?, ?)");
          $sql->bind_param("iiisi", $patientId, $diagnosisId, $medicationId, $prescriptionDate, $doctorID);
    
          if ($sql->execute()) {
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
            alertBox.innerHTML = "Успешно зададено лекарско предписание!";
            document.body.appendChild(alertBox);
            setTimeout(function() {
                document.body.removeChild(alertBox);
            }, 3000);
          </script>';
           echo '<script>location.replace("prescription.php");</script>';
          } else {
            echo "<div class='alert alert-danger'>Error: " . $sql->error . "</div>";
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
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/button.css">
    <link rel="stylesheet" href="../css/portal.css">
    <link rel="stylesheet" href="../css/admin-mobile.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <title>eHospital|Prescriptions</title>
</head>
<body>
  <style>
    .non-style-link:link, .non-style-link:visited, .non-style-link:hover, .non-style-link:active 
    {
    padding-right:10px;
    }
    </style>
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
                <tr>
                    <td width="13%" >
                    <a href="javascript:history.go(-1)"><button class="login-btn btn-primary-soft btn" style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px">Back</button></a>
                    </td>
                    <td>
                        <form action="" method="post" class="header-search">
                    <?php
                    if($_POST){

                        if(isset($_POST["search"])){
                            $keyword=$_POST["search"];   
                            $sqlmain= "SELECT * FROM ehos_patient WHERE patient_email='$keyword' 
                            or patient_name='$keyword' 
                            or patient_name like '$keyword%' 
                            or patient_name like '%$keyword'
                            or patient_name like '%$keyword%' ";
                        }
                    
                    } else{
                        $sqlmain= "SELECT * FROM ehos_appointment 
                        INNER JOIN ehos_patient on ehos_patient.patient_id = ehos_appointment.patient_id 
                        INNER JOIN ehos_schedule on ehos_schedule.schedule_id = ehos_appointment.schedule_id 
                        WHERE ehos_schedule.doctor_id = $userid;";
                    }
                    ?>
                            <input type="search" name="search12" class="input-text header-searchbar" placeholder="Search Patient (name/email)" list="patient">&nbsp;&nbsp;
                            
                            <?php
                                echo '<datalist id="patient">';
                                $list11 = $database->query($sqlmain);
                                for ($y=0;$y<$list11->num_rows;$y++){
                                    $row00=$list11->fetch_assoc();
                                    $d=$row00["patient_name"];
                                    $c=$row00["patient_email"];
                                    echo "<option value='$d'><br/>";
                                    echo "<option value='$c'><br/>";
                                };

                            echo ' </datalist>';
                            ?>                                     
                            <input type="Submit" value="Search" name="search" class="login-btn btn-primary btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">                        
                        </form>
                            </td>
                        </table>
                        <tr>
   <td colspan="6">
       <center>
        <div style="padding: 30px 10px 0px; margin-bottom: -50px">
        <table width="70%" class="sub-table scrolldown" border="0">
        <thead>
        <tr>
            <th class="table-headin">Patient</th>
            <th class="table-headin">Diagnosis</th>         
            <th class="table-headin">Medicine</th>     
            <th class="table-headin">Doctor</th>
            <th class="table-headin">Date</th>
            <th class="table-headin">Options</th>
        </thead>
        <tbody>                      
            <?php                             
               
               $sqlmain = "SELECT p.prescription_id, d.doctor_name, pt.patient_name, m.medication_name, dgn.diagnosis_name, p.prescription_date
               FROM ehos_prescriptions p
               INNER JOIN ehos_doctor d ON p.doctor_id = d.doctor_id
               INNER JOIN ehos_patient pt ON p.patient_id = pt.patient_id
               INNER JOIN ehos_medications m ON p.medication_id = m.medication_id
               INNER JOIN ehos_diagnoses dgn ON p.diagnosis_id = dgn.diagnosis_id
               WHERE p.doctor_id=$userid;";

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
               for ($x=0; $x<$result->num_rows;$x++){
                   $row=$result->fetch_assoc();
                   $prescription_id=$row["prescription_id"];
                   $patient_name=$row["patient_name"];
                   $diagnosis=$row["diagnosis_name"];
                   $medication_name=$row["medication_name"];
                   $doctor_name=$row["doctor_name"];
                   $prescription_date=$row["prescription_date"];
                   echo '<tr>
                       <td> &nbsp;'.
                       substr($patient_name,0,100)
                       .'</td>
                       <td style="text-align:center;">
                       '.substr($diagnosis,0,100).'
                      </td>
                       <td> &nbsp;'.
                       substr($medication_name,0,100)
                       .'</td>
                       <td style="text-align:center;">
                       '.substr($doctor_name,0,50).'
                       </td>
                       <td style="text-align:center;">
                           '.substr($prescription_date,0,10).' 
                       </td>
                   <td>
                   <div style="display:flex;justify-content: center;">
                   
                  <a href="print-prescription.php?prescription_id='.$prescription_id.'" class="non-style-link"> <button  class="btn-primary-soft btn button-icon btn-delete"  style="padding-left: 20px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;"><font class="tn-in-text">PDF </font> </button> </a>
                   </button>
                  </a>   
                  <a href="?action=drop&id='.$prescription_id.'&name='.$diagnosis.'" class="non-style-link"><button  class="btn-primary-soft btn button-icon btn-delete"  style="padding-left: 20px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;"><font class="tn-in-text">Отмяна</font></button></a>
                   </div>
                   </td>
                   </tr>';
               }
           }
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
                            <a href="delete-prescription.php?id='.$id.'" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"<font class="tn-in-text">&nbsp; Да &nbsp;</font></button></a>&nbsp;&nbsp;&nbsp;
                            <a href="prescription.php" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"><font class="tn-in-text">&nbsp;&nbsp; Не &nbsp;&nbsp;</font></button></a>
    
                            </div>
                        </center>
                </div>
                </div>
                '; 
            }
          }          
            ?> 
            </tbody>
        </table>
        </div>
        </center>
   </td> 
</tr> 

<div style="width: 50%; padding-left: 300px">
  <form method="POST" action="">
    <table width="100%" class="sub-table scrolldown" border="0" style="padding: 50px;border:none">
    <table class="filter-container" style="margin: 0 30px 10px">
      <tbody> 
        <tr>
          <td width="25%" style="font-size: 24px; padding: 30px 20px 10px">Patient:</td>
          <td>
            <select name="patient_id" style="font-size: 18px;">
              <option value="">Select a Patient</option>
              <?php
                $query = "SELECT patient_name FROM ehos_patient ORDER BY patient_name ASC";
                $result = mysqli_query($database, $query);
                while($row = mysqli_fetch_array($result)) {
                  echo '<option value="'. $row['patient_name'] . '">' . $row['patient_name'] . '</option>';
                }
              ?>
            </select>
          </td>
        </tr>
        <tr>
        <td width="25%" style="font-size: 24px; padding: 30px 20px 10px">Diagnosis: </td>
          <td>
            <select name="diagnosis_id" style="font-size: 18px;">
              <option value="">Select a Diagnosis</option>
              <?php
                $query = "SELECT diagnosis_name FROM ehos_diagnoses ORDER BY diagnosis_name ASC";
                $result = mysqli_query($database, $query);
                while($row = mysqli_fetch_array($result)) {
                  echo '<option value="'. $row['diagnosis_name'] . '">' . $row['diagnosis_name'] . '</option>';
                }
              ?>
            </select>
          </td>
        </tr>
        <tr>
        <td width="25%" style="font-size: 24px; padding: 30px 20px 10px"> Медикамент:</td>
          <td>
            <select name="medication_id" style="font-size: 18px;">
              <option value="">Изберете медикамент</option>
              <?php
                $query = "SELECT medication_name FROM ehos_medications ORDER BY medication_name ASC";
                $result = mysqli_query($database, $query);
                while($row = mysqli_fetch_array($result)) {
                  echo '<option value="' . $row['medication_name'] . '">' . $row['medication_name'] . '</option>';
                }
              ?>
            </select>
          </td>
        </tr>
        <tr>
           <td width="25%" style="font-size: 24px; padding: 30px 20px 10px">Дата:</td>
            <td>
                <?php
                date_default_timezone_set('Europe/Sofia');
                $today = date('Y-m-d');
                ?>
               <input type="text" name="prescription_date" value="<?php echo $today; ?>" size="6.5" readonly style="font-size: 20px;">
           </td>
        </tr>
        <tr>
          <td colspan="2" style="padding-top:30px">
            <center>
              <input type="submit" name="submit" value="Set a Prescription" style="font-size: 18px; background-color: #57B0BE; color: white; border: none; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; margin: 4px 2px; cursor: pointer; border-radius:7px;">
              <input type="reset" value="Reset" style="font-size: 18px; background-color:#39d6d4; color: white; border: none; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; margin: 4px 2px; cursor: pointer; border-radius:7px;">
            </center>
          </td>
        </tr>
    </tbody>
    </table>
  </table>
</form>
</body>
</html>