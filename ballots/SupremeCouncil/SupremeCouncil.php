<?php
session_start();
require_once "../../database/database.php";

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
   $_SESSION['logged_in'] = false;
   header('Location: ../../voter_login.php');
   exit;
}

// Receive voter's information as SESSIONS and store them in variables
$lastname = $_SESSION['lastname'];
$firstname = $_SESSION['firstname'];
$education = $_SESSION['education'];
$middlename = $_SESSION['middlename'];
$year_level = $_SESSION['year_level'];
$course_code = $_SESSION['course_code'];
$student_number = $_SESSION['student_number'];

$sql = "SELECT student_number, status FROM students WHERE student_number = '$student_number'";
$result = mysqli_query($conn, $sql);
$row = $result->fetch_assoc();
$status_check = $row["status"];

if ($status_check == 'finished'){
   $_SESSION['logged_in'] = false;
   header('Location: ../../voter_login.php');
   exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="SupremeCouncil.css" />
   <script src="SupremeCouncil.js"></script>
   <title>SSC BALLOT</title>
</head>

<body>
   <header>
      <div class="logos">
         <img class="phinma_seal" src="../../photos/phinma_seal.png">
         <div class="main_title">
            <h1>University of Pangasinan</h1>
            <h3>APEC Voting and Tallying System</h3>
         </div>
         <img class="upang_seal" src="../../photos/upang_seal.png">
      </div>
   </header>
   <main>
      <div class="ballot_header">
         <div class="header_left">
            <h1>PHINMA-UPANG Official Ballot</h1>
            <h2>
               Supreme Student <b>Council</b>
            </h2>
         </div>

         <div class="header_right">
            <h3>
               Voter: <b>
                  <?php echo $lastname . " " . $student_number ?>
               </b>
            </h3>
            <h3>
               Education Level: <b>
                  <?php echo ucfirst($education) ?>
               </b>
            </h3>
            <h3>
               Course Code: <b>
                  <?php echo $course_code ?>
               </b>
            </h3>
         </div>
      </div>
      <div class="progress-container" id="progress-container">
         <div class="progress-bar" id="myBar">

         </div>
      </div>
      <div class="ballot_container">
         <form method="post" action="">
            <?php
            // Get position, candidate, and party data from the database
            $sql = "SELECT position, candidate, party FROM supreme_council";
            $result = mysqli_query($conn, $sql);
            $positions = array();

            if (mysqli_num_rows($result) > 0) {
               // Group candidates by position
               while ($row = mysqli_fetch_assoc($result)) {
                  $position = $row["position"];
                  $candidate = $row["candidate"];
                  $party = $row["party"];

                  if (!array_key_exists($position, $positions)) {
                     $positions[$position] = array();
                  }

                  // Create an array of candidate-party pairs
                  $candidate_party = array(
                     'candidate' => $candidate,
                     'party' => $party
                  );

                  // Add the candidate-party pair to the position array
                  array_push($positions[$position], $candidate_party);
               }
            } else {
               echo "No results found.";
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
               // Create array to store selected candidates and positions
               $selected_candidates = array();

               // Increment the vote count for the selected candidate(s)
               foreach ($positions as $position => $candidates) {
                  $post_candidates = $_POST[$position];
                  if (!empty($post_candidates)) {
                     foreach ($post_candidates as $selected_candidate) {
                        // Add selected candidate, position, and party to array
                        foreach ($candidates as $candidate_party) {
                           if ($candidate_party['candidate'] == $selected_candidate) {
                              $selected_candidates[] = array(
                                 'position' => $position,
                                 'candidate' => $selected_candidate,
                                 'party' => $candidate_party['party']
                              );
                           }
                        }

                        $sql = "UPDATE supreme_council SET votes = votes + 1 WHERE position = '$position' AND candidate = '$selected_candidate'";
                        if (mysqli_query($conn, $sql)) {
                           $sql2 = "UPDATE students SET status='finished' WHERE student_number='$student_number'";
                           if (mysqli_query($conn, $sql2)) {
                              // Redirect user to voters_receipt.php after all candidates have been added
                              // Set selected candidates array as session variable
                              $_SESSION['selected_candidates'] = $selected_candidates;
                              
                           } else {
                              echo "Error updating changing the student's vote status: " . mysqli_error($conn);
                           }
                        } else {
                           echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                        }
                     }
                  }
               }

               // Redirect user to voters_receipt.php after all candidates have been added
               header("Location: ../../voters/voters_receipt.php");
               exit();

               
            }

            // Close database connection
            mysqli_close($conn);

            // Generate the form
            for ($i = 0; $i < count($positions); $i++) {
               $position = array_keys($positions)[$i];
               $candidates = $positions[$position];
               $inputname = 'radio';

               ?>
               <fieldset>
                  <legend>
                     <?php
                     $renamed_position = str_replace("_", " ", $position);
                     echo str_replace("Council", " ", $renamed_position);
                     ?>
                  </legend>
                  <input type='hidden' name='$position'>
                  <?php
                  for ($j = 0; $j < count($candidates); $j++) {
                     $candidate = $candidates[$j]['candidate'];
                     $party = $candidates[$j]['party'];
                     $lastname = strstr($candidate, ',', true);
                     $firstname = substr(strstr($candidate, ','), 1);
                     echo "<input
                          id='$candidate'
                          type='$inputname'
                          value='$candidate'

                              name='{$position}[]' 
                           >";
                     ?>
                     <label for='<?php echo $candidate ?>'>
                        <h4>
                           <?php echo $party ?>
                        </h4>
                        <img src="../../photos/<?php echo $lastname ?>.png">
                        <div class="candidate_name">
                           <h2>
                              <?php echo $lastname ?>,
                           </h2>
                           <h4>
                              <?php echo $firstname ?>
                           </h4>
                        </div>
                     </label>
                     <?php
                  }
                  ?>
               </fieldset>
               <?php
            }
            ?>
            <input type="submit" name="submit" value="Submit" onclick="return confirm('Are you sure you want to submit this ballot?')">
         </form>
      </div>
   </main>
</body>

</html>