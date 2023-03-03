<?php
session_start();

require_once "../../../database/database.php";

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
   header('Location: ../../AdminLogin/admin_login.php');
   exit;
}

//* Receive session message from login by storing it in variable
$username = $_SESSION['username'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">



   <link rel="stylesheet" href="Admin_Results.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
      integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />
   <title>COMELEC ADMIN</title>
   <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
   <script type="text/javascript" src="Admin_Results.js"></script>
</head>

<body onload="date_display(), show_tab_position(2)">

   <nav>
      <div class="nav_head">
         <div class="user_icon">
            <img src="../../../photos/Man-Placeholder.png" alt="">
         </div>

         <div class="user_details">
            <h2>
               <?php echo $username; ?>
            </h2>
            <h4>Super Admin</h4>
         </div>
      </div>

      <div class="nav_title">
         <h1>APEC</h1>
         <h3>Election Tally and Result</h3>
      </div>

      <div class="nav_options">
         <div class="main_options">
            <h1>Elective Report</h1>
            <button class="dashboard" id="dashboard" onclick="show(1)">
               <div class="button_icon">
                  <i class="fa-solid fa-gauge"></i>
               </div>
               <p>Dashboard</p>
            </button>

            <button class="result" id="result" onclick="show(2)">
               <div class="button_icon">
                  <i class="fa-solid fa-square-poll-vertical"></i>
               </div>
               <p>Result</p>
            </button>
         </div>
         <div class="manage_options">
            <h1>Manage</h1>
            <button class="voters" id="voters" onclick="show(3)">
               <div class="button_icon">
                  <i class="fa-solid fa-people-group"></i>
               </div>
               <p>Voters</p>
            </button>
            <button class="candidates" id="candidates" onclick="show(4)">
               <div class="button_icon">
                  <i class="fa-solid fa-user-plus"></i>
               </div>
               <p>Candidates</p>
            </button>
         </div>
      </div>
   </nav>

   <main>
      <div class="dashboard_body">
         <div class="dashboard_header">
            <h1><b>RESULTS</b> (Leading Candidates) </h1>

            <div class="info_container">
               <p id="datetime">Date: </p>
            </div>
         </div>

         <div class="dashboard_main">
            <h2>SUPREME STUDENT <b>COUNCIL</b> </h2>
            <div class="database_info">
               <?php
               $positions = array(
                  "Council_President",
                  "Council_Vice_President",
                  "Council_Secretary",
                  "Council_Treasurer",
                  "Council_Auditor",
                  "Council_Peace_Officer",
                  "Council_Student_Outreach_Community_Officer"
               );

               foreach ($positions as $position) {
                  $sql = "SELECT candidate, votes FROM supreme_council WHERE position = '$position' AND votes = (SELECT MAX(votes) FROM supreme_council WHERE position = '$position')";
                  $result = mysqli_query($conn, $sql);
                  if (mysqli_num_rows($result) > 0) {
                     $row = mysqli_fetch_assoc($result);
                     $lastname = strstr($row['candidate'], ',', true);
                     $firstname = substr(strstr($row['candidate'], ','), 1); ?>

                     <div class="winning_candidate">
                        <h2>
                           <?php $renamed_position = str_replace("_", " ", $position);
                           echo str_replace("Council", "", $renamed_position); ?>
                        </h2>
                        <img src="../../../photos/<?php echo $lastname ?>.png">
                        <p>
                           <b>
                              <?php echo $row['candidate']; ?>
                           </b>
                        </p>
                        <p>Votes:
                           <?php echo $row['votes']; ?>
                        </p>
                     </div>

                  <?php
                  } else {
                     // If no results were returned, output a message
                     echo "No results found for the position of $position.<br>";
                  }
               }
               ?>
            </div>
         </div>

         <div class="dashboard_main">
            <h2>SUPREME STUDENT <b>GOVERNMENT</b> </h2>
            <div class="database_info">
               <?php
               $positions = array(
                  "Government_President",
                  "Government_Vice_President",
                  "Government_Secretary",
                  "Government_Public_Information_Officer",
                  "Government_Peace_Officer",
                  "Government_Student_Outreach_Community_Officer",
                  "Government_ABM_Representative",
                  "Government_HUMSS_Representative",
                  "Government_GRADE_12_Representative"
               );

               foreach ($positions as $position) {
                  if ($position === "Government_Public_Information_Officer") {
                     $sql2 = "SELECT candidate, votes FROM supreme_government WHERE position = '$position' ORDER BY votes DESC LIMIT 2";
                  } else {
                     $sql2 = "SELECT candidate, votes FROM supreme_government WHERE position = '$position' AND votes = (SELECT MAX(votes) FROM supreme_government WHERE position = '$position')";
                  }

                  $result = mysqli_query($conn, $sql2);
                  if (mysqli_num_rows($result) > 0) {
                     while ($row = mysqli_fetch_assoc($result)) {
                        $lastname = strstr($row['candidate'], ',', true);
                        $firstname = substr(strstr($row['candidate'], ','), 1); ?>
                        <div class="winning_candidate">
                           <h2>
                              <?php $renamed_position = str_replace("_", " ", $position);
                              echo str_replace("Government", "", $renamed_position); ?>
                           </h2>
                           <img src="../../../photos/SSG-Photos/<?php echo $lastname ?>.png">
                           <p>
                              <b>
                                 <?php echo $row['candidate']; ?>
                              </b>
                           </p>
                           <p>Votes:
                              <?php echo $row['votes']; ?>
                           </p>
                        </div>
                     <?php
                     }
                  } else {
                     // If no results were returned, output a message
                     echo "No results found for the position of $position.<br>";
                  }
               }
               ?>
            </div>
         </div>

      </div>
   </main>
</body>

</html>