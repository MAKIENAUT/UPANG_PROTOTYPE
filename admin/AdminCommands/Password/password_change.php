<?php
// Initialize the session
session_start();

require_once "../../../database/database.php";

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
   header('Location: ../../../admin/AdminLogin/admin_login.php');
   exit;
}

// Check if user has admin clearance
if ($_SESSION['clearance'] == 'Admin') {
   header('Location: ../../AdminDisplays/AdminDashboard/Admin_Dashboard.php');
   exit;
}

// Get the user's username
$username = $_SESSION['username'];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   // Get the old and new passwords from the form
   $old_password = $_POST['old_password'];
   $new_password = $_POST['new_password'];

   // Check if old password matches the password stored in the database for the user
   $stmt = $conn->prepare("SELECT password FROM admin WHERE username = ?");
   $stmt->bind_param("s", $username);
   $stmt->execute();
   $stmt->bind_result($password);
   $stmt->fetch();
   $stmt->close();

   if (password_verify($old_password, $password)) {
      // Hash the new password using Bcrypt
      $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

      // Update the user's password in the database
      $stmt = $conn->prepare("UPDATE admin SET password = ? WHERE username = ?");
      $stmt->bind_param("ss", $hashed_password, $username);
      $stmt->execute();
      $stmt->close();

      // Redirect to the user's profile page
      header('Location: ../../AdminDisplays/Admin_Dashboard.php');
      exit;
   } else {
      // If old password is incorrect, show an error message
      $error_message = "Incorrect old password.";
   }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="../Password/password_change.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
      integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />
   <title>COMELEC ADMIN</title>
   <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
   <script type="text/javascript" src="password_change.js"></script>
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
      <h1>Reset Password</h1>
      <?php if (isset($error_message)): ?>
         <p>
            <?php echo $error_message; ?>
         </p>
      <?php endif; ?>
      <form method="post" action="">
         <label>Old Password:</label>
         <input type="password" name="old_password" required value=""><br><br>
         <label>New Password:</label>
         <input type="password" name="new_password" required><br><br>
         <input type="submit" value="Change Password" onclick="return confirm('Are you sure you want to submit this ballot?')">
      </form>
   </main>
</body>

</html>