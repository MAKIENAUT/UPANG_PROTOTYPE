<?php
session_start();

require_once "../../../database/database.php";

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
   header('Location: ../../AdminLogin/admin_login.php');
   exit;
}

//* Receive session message from login by storing it in variable
$username = $_SESSION['username'];
$clearance = $_SESSION['clearance'];


if ($clearance == 'Admin') {
   echo "<script> document.getElementById('manage_options').style.display = 'none'; </script>";
}

// Count the number of unique positions in the "supreme_council" table
$result_council = mysqli_query($conn, "SELECT COUNT(DISTINCT position) as count FROM supreme_council");
$row_council = mysqli_fetch_assoc($result_council);
$count_council_positions = $row_council['count'];

// Count the number of unique positions in the "supreme_government" table
$result_government = mysqli_query($conn, "SELECT COUNT(DISTINCT position) as count FROM supreme_government");
$row_government = mysqli_fetch_assoc($result_government);
$count_government_positions = $row_government['count'];

// Count the number of unique candidates in the "supreme_council" table
$result_council = mysqli_query($conn, "SELECT COUNT(DISTINCT candidate) as count FROM supreme_council");
$row_council = mysqli_fetch_assoc($result_council);
$count_council_candidates = $row_council['count'];

// Count the number of unique candidates in the "supreme_government" table
$result_government = mysqli_query($conn, "SELECT COUNT(DISTINCT candidate) as count FROM supreme_government");
$row_government = mysqli_fetch_assoc($result_government);
$count_government_candidates = $row_government['count'];

// Count the total number of voters in the "students" table
$result_total = mysqli_query($conn, "SELECT COUNT(*) as count FROM students");
$row_total = mysqli_fetch_assoc($result_total);
$count_total = $row_total['count'];

// Count the number of voters with the status "finished" in the "students" table
$result_finished = mysqli_query($conn, "SELECT COUNT(*) as count FROM students WHERE status = 'finished'");
$row_finished = mysqli_fetch_assoc($result_finished);
$count_finished = $row_finished['count'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">



   <link rel="stylesheet" href="Admin_Dashboard.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
      integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />
   <title>COMELEC ADMIN</title>
   <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
   <script type="text/javascript" src="Admin_Dashboard.js"></script>
</head>

<body onload="date_display(), show_tab_position(1), hide_admin()">

   <nav>
      <div class="nav_head">
         <div class="user_icon">
            <img src="../../../photos/Man-Placeholder.png" alt="">
         </div>

         <div class="user_details">
            <h2>
               <?php echo $username; ?>
            </h2>
            <h4>
               <?php echo $clearance ?>
            </h4>
         </div>
      </div>

      <div class="nav_title">
         <h1>APEC</h1>
         <h3>Election Tally and Result</h3>
      </div>

      <div class="nav_options" id="nav_options">
         <div class="main_options">
            <div class="top_layer">
               <h1>Elective Report</h1>
               <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">
                  <i class="fa-solid fa-x"></i>
               </a>
            </div>
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
         <div class="manage_options" id="manage_options">
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
         <div class="account_settings">
            <h1>Account Settings</h1>
            <div class="dropdown">
               <i class="fa-solid fa-gear"></i>
               <button class="dropbtn" onclick="show_dropdown()">Settings</button>
               <div class="dropdown-content" id="dropdown-content">
                  <a href="../../AdminCommands/logout.php">Logout</a>
                  <a href="../../AdminCommands/Password/password_change.php">Password</a>
               </div>
            </div>
         </div>
      </div>
      <div class="hamburger" onclick="openNav()">
         
         <i class="fa-solid fa-bars"></i>
      </div>
   </nav>
   <main>
      <div class="dashboard_body">
         <div class="dashboard_header">
            <h1><b>DASHBOARD</b> (Overview)</h1>

            <div class="info_container">
               <p id="datetime">Date: </p>
            </div>
         </div>

         <div class="dashboard_main">
            <h2>SUPREME STUDENT <b>COUNCIL</b></h2>
            <div class="database_info">
               <div class="positions_count">
                  <h1>
                     <?php echo $count_council_positions; ?>
                  </h1>
                  <h3>Number of Positions:</h3>
               </div>
               <div class="candidates_count">
                  <h1>
                     <?php echo $count_council_candidates; ?>
                  </h1>
                  <h3>Number of Candidates:</h3>
               </div>
               <div class="total_voters">
                  <h1>
                     <?php echo $count_total; ?>
                  </h1>
                  <h3>Number of Total Voters:</h3>
               </div>
               <div class="voters_count">
                  <h1>
                     <?php echo $count_finished; ?>
                  </h1>
                  <h3>Voters who Voted:</h3>
                  <a href="../../AdminDisplays/ManageVoters/Manage_Voters.php"></a>
               </div>
            </div>
         </div>
         <div class="dashboard_main">
            <h2>SUPREME STUDENT <b>GOVERNMENT</b></h2>
            <div class="database_info">
               <div class="positions_count">
                  <h1>
                     <?php echo $count_government_positions; ?>
                  </h1>
                  <h3>Number of Positions:</h3>
               </div>
               <div class="candidates_count">
                  <h1>
                     <?php echo $count_government_candidates; ?>
                  </h1>
                  <h3>Number of Candidates:</h3>
               </div>
               <div class="total_voters">
                  <h1>
                     <?php echo $count_total; ?>
                  </h1>
                  <h3>Number of Total Voters:</h3>
               </div>
               <div class="voters_count">
                  <h1>
                     <?php echo $count_finished; ?>
                  </h1>
                  <h3>Voters who Voted:</h3>
               </div>
            </div>
         </div>
      </div>
   </main>
</body>

</html>