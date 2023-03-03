<?php
session_start();
require_once "../../../database/database.php";

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
   header('Location: ../../AdminLogin/admin_login.php');
   exit;
}

//* Receive session message from login by storing it in variable
$username = $_SESSION['username'];


$table = 'students'; // Replace with your own table name    
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="Admin_Voters.css">
   <link rel="stylesheet" 
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
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
      <h1>STUDENTS DIRECTORY</h1>
      <?php
      // Pagination
      $records_per_page = 12;
      $page = isset($_GET['page']) ? $_GET['page'] : 1;
      $offset = ($page - 1) * $records_per_page;

      // Search
      $search = isset($_GET['search']) ? $_GET['search'] : '';

      // Build SQL query
      $sql = "SELECT id, student_number, lastname, firstname, middlename, course_code, year_level, education, student_email, status FROM $table";
      if (!empty($search)) {
         $sql .= " WHERE student_number LIKE '%$search%' OR lastname LIKE '%$search%' OR firstname LIKE '%$search%' OR middlename LIKE '%$search%' OR course_code LIKE '%$search%' OR year_level LIKE '%$search%' OR education LIKE '%$search%' OR student_email LIKE '%$search%' OR status LIKE '%$search%'";
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
            <th>Actions</th>
         </tr>
         <?php
         while ($row = $result->fetch_assoc()) { ?>
            <tr>
               <td>
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
               <td>
                  <?php echo $row['year_level']; ?>
               </td>
               <td>
                  <?php echo $row['education']; ?>
               </td>
               <td>
                  <?php echo $row['student_email']; ?>
               </td>
               <td>
                  <?php echo $row['status']; ?>
               </td>
               <td>
                  <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                  <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
               </td>
            </tr>
            <?php
         }
         ?>
      </table>

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