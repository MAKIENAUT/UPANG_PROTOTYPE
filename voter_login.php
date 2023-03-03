<?php
   // Start or Initialize the session
   session_start();
   require_once "database/database.php";

   

   $student_number = $password = "";
   $password_err = $student_number_err = $student_email_err = $login_err = "";

   if ($_SERVER["REQUEST_METHOD"] == "POST") {
      //! Check if student number is empty
      if (empty(trim($_POST["student_number"]))) {
         $student_number_err = "Please enter Student Number.";
      } else {
         $student_number = trim($_POST["student_number"]);
      }
      //! Check if student email is empty
      if (empty(trim($_POST["student_email"]))) {
         $student_email_err = "Please enter Student Email.";
      } else {
         $student_email = trim($_POST["student_email"]);
      }
      //! Check if password is empty
      if (empty(trim($_POST["password"]))) {
         $password_err = "Please enter your password.";
      } else {
         $password = trim($_POST["password"]);
      }

      //! Get the hashed password for the entered username
      $sql = "SELECT * FROM students WHERE student_number='$student_number' AND student_email='$student_email'";
      $result = $conn->query($sql);
      $row = mysqli_fetch_assoc($result);

      if ($result->num_rows > 0) {
         $hashed_password = $row['password'];
         
         //! Check if the hashed password is correct
         if (password_verify($password, $hashed_password)) {
            //! Declare columns that are needed for ballot.
            $status = $row['status'];
            $lastname = $row['lastname'];
            $firstname = $row['firstname'];
            $education = $row['education'];
            $middlename = $row['middlename'];
            $year_level = $row['year_level'];
            $course_code = $row['course_code'];
            $student_number = $row['student_number'];

            //! Check the voter's status: pending => vote || finished => can't vote
            if ($status === "pending") {
               //! Declare $_SESSION variables to send  voter information to ballot.
               $_SESSION['logged_in'] = true;   //! Tell the Ballot that user is now logged in.
               $_SESSION['lastname'] = $lastname;
               $_SESSION['firstname'] = $firstname;
               $_SESSION['education'] = $education;
               $_SESSION['middlename'] = $middlename;
               $_SESSION['year_level'] = $year_level;
               $_SESSION['course_code'] = $course_code;
               $_SESSION['student_number'] = $student_number;
               $_SESSION['student_email'] = $student_email;

                //! See if voter is in tertiary (✔️ = SSC || ❌ = SSG)
               if ($education == "tertiary") {
                  header('Location: ballots/SupremeCouncil/SupremeCouncil.php');
                  exit;
               } else {
                  header('Location: ballots/SupremeGovernment/SupremeGovernment.php');
                  exit;
               }
            } else {
               header('Location: voters/voters_receipt.php');
            }
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
   <link 
      rel="stylesheet" 
      href="voter_login.css"
   />
   <link 
      rel="stylesheet" 
      crossorigin="anonymous" 
      referrerpolicy="no-referrer" 
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
      integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
   />
   <link 
      rel="icon" 
      type="image/x-icon" 
      href="photos/phinma_seal.png"
   />
   <title>PRECINCT</title>
</head>

<body>
   <header>
      <div class="logos">
         <img class="phinma_seal" src="photos/phinma_seal.png">
         <div class="main_title">
            <h1>University of Pangasinan</h1>
            <h3>APEC Voting and Tallying System</h3>
         </div>
         <img class="upang_seal" src="photos/upang_seal.png">
      </div>
   </header>

   <main>
      <div class="login_form">
         <form name="login" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <fieldset>
               <legend>Voter's Login</legend>
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
                     <label for="student_number">Student Number: </label>
                     <input type="text" name="student_number" placeholder="XX - XX - XXXXX" />
                  </div>

                  <div class="input_fields">
                     <label for="student_email">Student Email: </label>
                     <input type="email" name="student_email" placeholder="FNMN.LASTNAME.UP@PHINMAED.COM" />
                  </div>

                  <div class="input_fields">
                     <label for="password">Password: </label>
                     <input type="password" name="password" placeholder="Enter Provided Password:" />
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
               <p class="admin_login">
                  Administrator Login <a href="admin/AdminLogin/admin_login.php">here</a>.
               </p>
            </fieldset>
         </form>
      </div>
   </main>  
</body>

</html>