<?php
    //! Declare variables for server credentials
    $servername = "localhost";
    $username = "u546105649_admin";
    $password = "MakiePioneer17";
    $dbname = "u546105649_phinmaelection";
    // Create connection
    $conn =  mysqli_connect($servername,$username,$password,"$dbname");
    if (!$conn) {
    
    die("Could Not Connect:" .mysqli_connect_error());
    }
?>