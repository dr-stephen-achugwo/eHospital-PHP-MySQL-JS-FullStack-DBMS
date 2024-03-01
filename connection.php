<?php
// Create connection to database with mysql 
#    $database = new mysqli("mysql", "root", "password", "ehospital");
#    if ($database->connect_error){
#        die("Connection failed: " . $database->connect_error);
#    }
?>


<?php
// the 4 variables below are used to initialize the database
$servername = "localhost";
$username = "artappco_dr_stephen17";
$password = "Iloveyou2!&?";
$database = "artappco_php";

// Create connection object for the database
$connection = new mysqli($servername, $username, $password, $database);

// Create connection to database with localhost
$database = new mysqli("localhost", "artappco_dr_stephen17", "Iloveyou2!&?", "artappco_php");

// Check Connection
if ($database->connect_error) {
     die("Connection failed: ". $database->connect_error);
}
?>





