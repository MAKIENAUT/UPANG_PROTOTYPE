<?php
   // Start or Initialize the session
   session_start();

   require_once "../../database/database.php";

   $username = $password = "";
   $student_number_err = $password_err =  $login_err = "";

   if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Check if studentnumber is empty
      if (empty(trim($_POST["username"]))) {
         $student_number_err = "Please enter your Username.";
      } else {
         $username = trim($_POST["username"]);
      }
      // Check if password is empty
      if (empty(trim($_POST["password"]))) {
         $password_err = "Please enter your password.";
      } else {
         $password = trim($_POST["password"]);
      }
      // Get the hashed password for the entered username
      $sql = "SELECT * FROM admin WHERE username='$username'";
      $result = $conn->query($sql);
      $row = mysqli_fetch_assoc($result);

      if ($result->num_rows > 0) {
         $hashed_password = $row['password'];
         // Check if the entered password matches the hashed password
         if (password_verify($password, $hashed_password)) {
            session_start();
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['clearance'] = $row['clearance'];
            header('Location: ../AdminDisplays/AdminDashboard/Admin_Dashboard.php');
            exit;
         } else {
            $login_err = "Login failed. Incorrect password.";
         }
      } else {
         $login_err = "Login failed. Incorrect Student Number.";
      }
      $conn->close();
   }
   
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <link rel="stylesheet" href="admin_login.css">
   <link 
      rel="stylesheet" 
      crossorigin="anonymous" 
      referrerpolicy="no-referrer" 
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
      integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
   />
   <title>Document</title>
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
      <div class="login_form">
         <form name="login" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <fieldset>
               <legend>Admin's Login</legend>
               <p class="login_error">
                  <?php
                     if (!empty($login_err)) {
                        echo $login_err;
                     } else {
                        echo "Please Enter the required fields and use the provided format.";
                     }
                  ?>
               </p>
               <div class="form_fields">
                  <div class="input_fields">
                     <label for="username">Username: </label>
                     <input type="text" name="username" placeholder="Enter Username:" />
                  </div>

                  <div class="input_fields">
                     <label for="password">Password: </label>
                     <input type="password" name="password" placeholder="Enter Password:" />
                  </div>
               </div>
               <button 
                  id="submit" 
                  name="submit" 
                  type="submit" 
                  onclick="resetForm()"
               >
                  LOGIN
               </button>
            </fieldset>
         </form>
      </div>
   </main>
</body>

</html>