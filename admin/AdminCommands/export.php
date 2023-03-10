<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'phinma_election';

// Tables to export
$tables = array('admin', 'students', 'supreme_council', 'supreme_government');

// Connect to MySQL server
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set the filename for the SQL file
$filename = 'phinma_election_backup_' . date('Y-m-d_H-i-s') . '.sql';

// Open the file for writing
$file = fopen($filename, 'w');

// Loop through each table
foreach ($tables as $table_name) {
    // Get the create statement for the table
    $create = mysqli_query($conn, "SHOW CREATE TABLE $table_name");
    $create_row = mysqli_fetch_row($create);
    $create_statement = $create_row[1];
    
    // Write the create statement to the file
    fwrite($file, $create_statement . ";\n");
    
    // Get all data in the table
    $data = mysqli_query($conn, "SELECT * FROM $table_name");
    
    // Get column information
    $columns = array();
    $result = mysqli_query($conn, "SHOW COLUMNS FROM $table_name");
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['Extra'] != 'auto_increment') {
            $columns[] = $row['Field'];
        }
    }
    
    // Loop through each row and write to the file
    while ($row = mysqli_fetch_array($data)) {
        $values = array();
        foreach ($columns as $column) {
            $value = mysqli_real_escape_string($conn, $row[$column]);
            $values[] = "'$value'";
        }
        
        $insert_statement = "INSERT INTO $table_name (" . implode(',', $columns) . ") VALUES (" . implode(',', $values) . ");\n";
        fwrite($file, $insert_statement);
    }
}

// Close the file and database connection
fclose($file);
mysqli_close($conn);

echo "Selected tables exported to $filename";

?>
