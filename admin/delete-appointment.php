<?php
    @session_start();

    if (isset($_SESSION["user"])) {
        if (($_SESSION["user"]) == "" || $_SESSION['usertype'] != 'a') {
            echo '<script>window.location.href = "../login.php";</script>';
            exit();
        }
    } else {
        echo '<script>window.location.href = "../login.php";</script>';
        exit();
    }
    if($_GET){
        include("../connection.php");
        $id=$_GET["id"];
        $sql= $database->query("DELETE FROM ehos_appointment WHERE appointment_id='$id';");
        echo '<script>window.location.href = "appointment.php";</script>';
    }
?>