<?php

require_once "../database/database.php";
// Get the data from the table
$sql = "SELECT * FROM supreme_candidates";
$result = $conn->query($sql);
 
// Create the header row of the CSV file
$header = array("id", "Candidate", "Department", "Position", "Votes");
 
// Open the file for writing
$file = fopen("supreme_candidates.csv", "w");
 
// Write the header row to the file
fputcsv($file, $header);
 
// Write the data rows to the file
while ($row = mysqli_fetch_assoc($result)) {
  fputcsv($file, $row);
}
 
// Close the file
fclose($file);
 
// Close the database connection
mysqli_close($conn);

?>