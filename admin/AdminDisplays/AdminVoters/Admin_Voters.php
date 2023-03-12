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
$clearance = $_SESSION['clearance'];


$table = 'students'; // Replace with your own table name    
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="Admin_Voters.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
      integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />
   <title>COMELEC ADMIN</title>
   <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
   <script type="text/javascript" src="Admin_Voters.js"></script>
</head>

<body onload="date_display(), show_tab_position(3)">
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
      <h1>STUDENTS DIRECTORY</h1>
      <?php
      // Pagination
      $records_per_page = 12;
      $page = isset($_GET['page']) ? $_GET['page'] : 1;
      $offset = ($page - 1) * $records_per_page;

      // Search
      $search = isset($_GET['search']) ? $_GET['search'] : '';

      // Build SQL query
      $sql = "SELECT id, student_number, lastname, firstname, middlename, course_code, year_level, education, student_email, status, time FROM $table";
      if (!empty($search)) {
         // Split search string into individual words
         $keywords = preg_split('/\s+/', $search);
         $conditions = array();
         foreach ($keywords as $keyword) {
            $conditions[] = "student_number LIKE '%$keyword%' OR lastname LIKE '%$keyword%' OR firstname LIKE '%$keyword%' OR middlename LIKE '%$keyword%' OR course_code LIKE '%$keyword%' OR year_level LIKE '%$keyword%' OR education LIKE '%$keyword%' OR student_email LIKE '%$keyword%' OR status LIKE '%$keyword%'";
         }
         $sql .= " WHERE " . implode(' AND ', $conditions);
      }
      $sql .= " LIMIT $records_per_page OFFSET $offset";

      // Execute SQL query
      $result = $conn->query($sql);
      ?>

      <table>
         <tr>
            <th>Student Number</th>
            <th>Last Name</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Course Code</th>
            <th>Year Level</th>
            <th>Education</th>
            <th>Student Email</th>
            <th>Status</th>
            <th>Time Voted</th>
         </tr>
         <?php
         while ($row = $result->fetch_assoc()) { ?>
            <tr>
               <td class="student_number">
                  <?php echo $row['student_number']; ?>
               </td>
               <td>
                  <?php echo $row['lastname']; ?>
               </td>
               <td>
                  <?php echo $row['firstname']; ?>
               </td>
               <td>
                  <?php echo $row['middlename']; ?>
               </td>
               <td>
                  <?php echo $row['course_code']; ?>
               </td>
               <td class="year_level">
                  <?php echo $row['year_level']; ?>
               </td>
               <td>
                  <?php echo $row['education']; ?>
               </td>
               <td>
                  <form method="POST" action="">
                     <input type="hidden" name="student_id" value="<?php echo $row['id']; ?>">
                     <input class="student_email" type="email" name="student_email" value="<?php echo $row['student_email']; ?>">
                     <button class="email_submit" type="submit">Save</button>
                  </form>
               </td>
               <td >
                  <?php echo $row['status']; ?>
               </td>
               <td class="time">
                  <?php echo $row['time']; ?>
               </td>
            </tr>
            <?php
         }
         ?>
      </table>

      <?php
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
         // Get the student ID and email from the form submission
         $student_id = $_POST['student_id'];
         $student_email = $_POST['student_email'];

         $sql = "UPDATE students SET student_email='$student_email' WHERE id=$student_id";
         $result = $conn->query($sql);

         if ($result) {
            echo "<p style='color: white;'> Email updated successfully. </p>" ;
         } else {
            echo "Error updating email: " . $mysqli->error;
         }
      }
      ?>


      <?php
      // Build pagination links
      $sql = "SELECT COUNT(*) AS total FROM $table";
      if (!empty($search)) {
         $sql .= " WHERE student_number LIKE '%$search%' OR lastname LIKE '%$search%' OR firstname LIKE '%$search%' OR middlename LIKE '%$search%' OR course_code LIKE '%$search%' OR year_level LIKE '%$search%' OR education LIKE '%$search%' OR student_email LIKE '%$search%' OR status LIKE '%$search%'";
      }
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
      $total_records = $row['total'];
      $total_pages = ceil($total_records / $records_per_page); ?>

      <div class="search_bar">
         <form method="get">
            <input type="text" name="search" value="<?php echo $search; ?>" placeholder="Search">
            <button type="submit">Search</button>
         </form>

         <?php
         if ($total_pages > 1) { ?>
            <div class="pagination">
               <?php
               $range = 5; // Number of links to show before and after the current page
               $prev_page = ($page > 1) ? $page - 1 : 1;
               $next_page = ($page < $total_pages) ? $page + 1 : $total_pages;
               // Previous button
               if ($page > 1) {
                  echo '<a href="?page=' . $prev_page . '&search=' . $search . '">Prev</a>';
               }

               // Pagination links
               for ($i = ($page - $range); $i <= ($page + $range); $i++) {
                  if ($i > 0 && $i <= $total_pages) {
                     if ($i == $page) {
                        echo '<span><a href="#">' . $i . '</a></span>';
                     } else {
                        echo '<a href="?page=' . $i . '&search=' . $search . '">' . $i . '</a>';
                     }
                  }
               }

               // Next button
               if ($page < $total_pages) {
                  echo '<a href="?page=' . $next_page . '&search=' . $search . '">Next</a>';
               } ?>

            </div>
            <?php
         }
         ?>
      </div>

   </main>
</body>

</html>