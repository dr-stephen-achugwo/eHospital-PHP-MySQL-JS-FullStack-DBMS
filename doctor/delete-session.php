<?php
    session_start();
    if (isset($_SESSION["user"]) && !empty($_SESSION["user"]) && $_SESSION['usertype'] == 'a') {
        include("../connection.php");
        $useremail = $_SESSION["user"];
        $userrow = $database->query("SELECT * FROM ehos_doctor WHERE doctor_email='$useremail'");
        $userfetch = $userrow->fetch_assoc();
        $userid = $userfetch["doctor_id"];
        $username = $userfetch["doctor_name"];
    } else {
        echo '<script>window.location.href = "../login.php";</script>';
        exit();
    }
?>
<?php
if ($_GET) {
    include("../connection.php");
    $id = $_GET["id"];
    $sql = $database->query("DELETE FROM ehos_schedule WHERE schedule_id='$id';");
    echo '<script>window.location.href = "schedule.php";</script>';
}
?>