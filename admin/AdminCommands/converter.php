<?php
    require_once "../../database/database.php";
    
// Select all records from the password field in the user table
$sql = "SELECT password FROM admin";
$result = mysqli_query($conn, $sql);

// Loop through each record and hash the password
while ($row = mysqli_fetch_assoc($result)) {
    $hashed_password = password_hash($row['password'], PASSWORD_DEFAULT);
    
    // Update the record with the hashed password
    $update_sql = "UPDATE admin SET password='$hashed_password' WHERE password='" . $row['password'] . "'";
    mysqli_query($conn, $update_sql);
}

// Close database connection
mysqli_close($conn);

echo "All passwords have been hashed successfully.";

?>