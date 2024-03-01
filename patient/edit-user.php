
    <?php
    
    include("../connection.php");

    if($_POST){

        $result= $database->query("SELECT * FROM ehos_webuser");
        $name=$_POST['name'];
        $egn=$_POST['egn'];
        $oldemail=$_POST["oldemail"];
        $address=$_POST['address'];
        $email=$_POST['email'];
        $tele=$_POST['Tele'];
        $password=$_POST['password'];
        $cpassword=$_POST['cpassword'];
        $id=$_POST['id00'];
        
        if ($password == $cpassword) {
            $error = '3';
        
            $sqlmain = "SELECT ehos_patient.patient_id FROM ehos_patient INNER JOIN ehos_webuser ON ehos_patient.patient_email = ehos_webuser.email WHERE ehos_webuser.email=?;";
            $stmt = $database->prepare($sqlmain);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $id2 = $result->fetch_assoc()["patient_id"];
            } else {
                $id2 = $id;
            }
        
            if ($id2 != $id) {
                $error = '1';
            } else {
                $sql1 = "UPDATE ehos_patient SET patient_email=?, patient_name=?, patient_password=?, patient_egn=?, patient_tel=?, patient_city=? WHERE patient_id=?";
                $stmt = $database->prepare($sql1);
                $stmt->bind_param("ssssssi", $email, $name, $password, $egn, $tele, $address, $id);
                $stmt->execute();
        
                $sql1 = "UPDATE ehos_webuser SET email=? WHERE email=?";
                $stmt = $database->prepare($sql1);
                $stmt->bind_param("ss", $email, $oldemail);
                $stmt->execute();
        
                $error = '4';
            }
        } else {
            $error = '2';
        } 
    }
    
    echo 'window.location.href = "settings.php?action=edit&error=" + encodeURIComponent(error) + "&id=" + encodeURIComponent(id);';

    ?> 

</body>
</html>