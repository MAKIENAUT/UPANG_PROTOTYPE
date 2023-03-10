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
   // Get the new password from the form
   $new_password = $_POST['new_password'];

   // Hash the new password using Bcrypt
   $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

   // Update the user's password in the database
   $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
   $stmt->bind_param("ss", $hashed_password, $username);
   $stmt->execute();
   $stmt->close();

   // Redirect to the user's profile page
   header('Location: profile.php');
   exit;
}
?>

<html>
   <head>
      <title>Reset Password</title>
   </head>
   <body>
      <h1>Reset Password</h1>
      <form method="post" action="">
         <label>New Password:</label>
         <input type="password" name="new_password" required><br><br>
         <input type="submit" value="Reset Password">
      </form>
   </body>
</html>