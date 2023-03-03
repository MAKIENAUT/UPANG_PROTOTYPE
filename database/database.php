<?php
    //! Declare variables for server credentials
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "phinma_election";
    // Create connection
    $conn =  mysqli_connect($servername,$username,$password,"$dbname");
    if (!$conn) {
    
    die("Could Not Connect:" .mysqli_connect_error());
    }
?>