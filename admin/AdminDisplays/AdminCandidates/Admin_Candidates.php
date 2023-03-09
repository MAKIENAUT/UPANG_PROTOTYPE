<?php
session_start();

require_once "../../../database/database.php";

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
   header('Location: ../../AdminLogin/admin_login.php');
   exit;
}

if ($_SESSION['clearance'] == 'Admin') {
   header('Location: ../../AdminDisplays/AdminDashboard/Admin_Dashboard.php');
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



   <link rel="stylesheet" href="Admin_Candidates.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
      integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />
   <title>COMELEC ADMIN</title>
   <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
   <script type="text/javascript" src="Admin_Candidates.js"></script>
</head>

<body onload="date_display(), show_tab_position(4)">

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
            <h1>CANDIDATES</h1>

            <div class="info_container">
               <p id="datetime">Date: </p>
            </div>
         </div>

         <div class="council_dash">
            <h2>SUPREME STUDENT <b>COUNCIL</b> (Tertiary)</h2>
            <div class="vote_tally">
               <?php
               $sql1 = "SELECT SUM(votes) AS total_votes1 FROM supreme_council";
               $result1 = $conn->query($sql1);
               $row1 = $result1->fetch_assoc();
               $total_votes1 = $row1["total_votes1"];
               $sql1 = "SELECT DISTINCT position FROM supreme_council";
               $result1 = $conn->query($sql1);

               while ($row1 = $result1->fetch_assoc()) {
                  $position = $row1["position"];
                  // Get the accumulated votes for each candidate for this position
                  $sql1 = "SELECT candidate, votes FROM supreme_council WHERE position = '$position' ORDER BY votes DESC";
                  $result2 = $conn->query($sql1);
                  $candidates = array();
                  while ($row2 = $result2->fetch_assoc()) {
                     $candidate = $row2["candidate"];
                     $votes = $row2["votes"];
                     $percent = ($votes / $total_votes1) * 100;
                     $candidates[$candidate] = array('percent' => $percent, 'votes' => $votes);
                  }

                  // Display the accumulated votes for each candidate for this position
                  $total_percent = 0;
                  $candidate_names = array_keys($candidates);

                  $data = array();
                  $data[] = ['Candidate', 'Percentage', 'Votes'];

                  foreach ($candidate_names as $candidate) {
                     $name = substr($candidate, 0, strpos($candidate, ','));
                     $percent = $candidates[$candidate]['percent'];
                     $votes = $candidates[$candidate]['votes'];
                     $percentage = floatval($percent);
                     $data[] = [$name . ': ' . $votes, $percentage, $votes];
                  }
                  ?>
                  <div id="<?php echo $position; ?>" style="width:48%; height:250px; background-color: transparent;">
                     <div>Please Wait</div>
                  </div>
                  <script>
                     google.charts.load('current', { 'packages': ['corechart'] });
                     google.charts.setOnLoadCallback(drawChart<?php echo $position; ?>);
                     function drawChart<?php echo $position; ?>() {
                        var data = google.visualization.arrayToDataTable(<?php echo json_encode($data); ?>);
                        var options = {
                           pieHole: 0.8,
                           pieSliceText: 'none',
                           title: '<?php $renamed_position = str_replace("_", " ", $position); echo str_replace("Council", "", $renamed_position); ?>',
                           titleTextStyle: {
                              color: 'white',
                              fontSize: 14,
                           },
                           legend: {
                              position: 'bottom',
                              alignment: 'center',
                              textStyle: {
                                 fontSize: 13,
                                 color: 'white',
                              }
                           },
                           color: 'white',
                           backgroundColor: 'transparent',
                           border: '1px solid white'
                        };
                        var chart = new google.visualization.PieChart(document.getElementById('<?php echo $position; ?>'));
                        chart.draw(data, options);
                     }
                  </script>
                  <?php
               }
               ?>
            </div>
         </div>
         <div class="council_dash">
            <h2>SUPREME STUDENT <b>GOVERNMENT</b> (Secondary)</h2>
            <div class="vote_tally">
               <?php
               // Get the total number of votes for supreme government
               $sql = "SELECT SUM(votes) AS total_votes FROM supreme_government";
               $result_gov = $conn->query($sql);
               $row = $result_gov->fetch_assoc();
               $total_votes_government = $row["total_votes"];

               // Get the positions for supreme government
               $sql = "SELECT DISTINCT position FROM supreme_government";
               $result_gov_pos = $conn->query($sql);

               while ($row = $result_gov_pos->fetch_assoc()) {
                  $position = $row["position"];
                  // Get the accumulated votes for each candidate for this position
                  $sql = "SELECT candidate, votes FROM supreme_government WHERE position = '$position' ORDER BY votes DESC";
                  $result2 = $conn->query($sql);
                  $candidates = array();
                  while ($row2 = $result2->fetch_assoc()) {
                     $candidate = $row2["candidate"];
                     $votes = $row2["votes"];
                     $percent = ($votes / $total_votes_government) * 100;
                     $candidates[$candidate] = array('percent' => $percent, 'votes' => $votes);
                  }

                  // Display the accumulated votes for each candidate for this position
                  $total_percent = 0;
                  $candidate_names = array_keys($candidates);

                  $data = array();
                  $data[] = ['Candidate', 'Percentage', 'Votes'];

                  foreach ($candidate_names as $candidate) {
                     $name = substr($candidate, 0, strpos($candidate, ','));
                     $percent = $candidates[$candidate]['percent'];
                     $votes = $candidates[$candidate]['votes'];
                     $percentage = floatval($percent);
                     $data[] = [$name . ': ' . $votes, $percentage, $votes];
                  }
                  ?>
                  <div id="<?php echo $position; ?>" style="width:48%; height:250px; background-color: transparent;">
                     <div>Please Wait.</div>
                  </div>
                  <script>
                     google.charts.load('current', { 'packages': ['corechart'] });
                     google.charts.setOnLoadCallback(drawChart<?php echo $position; ?>);
                     function drawChart<?php echo $position; ?>() {
                        var data = google.visualization.arrayToDataTable(<?php echo json_encode($data); ?>);
                        var options = {
                           pieHole: 0.8,
                           pieSliceText: 'none',
                           title: '<?php $renamed_position = str_replace("_", " ", $position); echo str_replace("Government", "", $renamed_position); ?>',
                           titleTextStyle: {
                              color: 'white',
                              fontSize: 14,
                           },
                           legend: {
                              position: 'bottom',
                              alignment: 'center',
                              textStyle: {
                                 fontSize: 13,
                                 color: 'white',
                              }
                           },
                           color: 'white',
                           backgroundColor: 'transparent',
                           border: '1px solid white'
                        };
                        var chart = new google.visualization.PieChart(document.getElementById('<?php echo $position; ?>'));
                        chart.draw(data, options);
                     }
                  </script>
                  <?php
               }
               ?>
            </div>
         </div>

      </div>
   </main>
</body>

</html>