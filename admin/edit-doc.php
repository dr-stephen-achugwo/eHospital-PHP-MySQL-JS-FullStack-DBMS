<?php
    include("../connection.php");
    if($_POST){
        $result= $database->query("SELECT * FROM ehos_webuser");
        $name=$_POST['name'];
        $oldemail=$_POST["oldemail"];
        $spec=$_POST['spec'];
        $email=$_POST['email'];
        $tele=$_POST['Tele'];
        $password=$_POST['password'];
        $cpassword=$_POST['cpassword'];
        $id=$_POST['id00'];
        
        if ($password==$cpassword){
            $error='3';
            $result= $database->query("SELECT ehos_doctor.doctor_id FROM ehos_doctor INNER JOIN ehos_webuser on ehos_doctor.doctor_email = ehos_webuser.email WHERE ehos_webuser.email='$email';");
            if($result->num_rows==1){
                $id2=$result->fetch_assoc()["doctor_id"];
            }else{
                $id2=$id;
            }
            
            echo $id2."jdfjdfdh";
            if($id2!=$id){
                $error='1';
                    
            }else{
                $sql1="UPDATE ehos_doctor SET doctor_email='$email', doctor_name='$name',doctor_password='$password', doctor_tel='$tele', specialties=$spec WHERE doctor_id=$id ;";
                $database->query($sql1);
                
                $sql1="UPDATE ehos_webuser SET email='$email' WHERE email='$oldemail' ;";
                $database->query($sql1);
                $error= '4';
                
            } 
        }else{
            $error='2';
        }

    }else{
        $error='3';
    }
    echo 'window.location.href = "doctors.php?action=edit&error=" + encodeURIComponent("' . $error . '") + "&id=" + encodeURIComponent("' . $id . '");';
?>
