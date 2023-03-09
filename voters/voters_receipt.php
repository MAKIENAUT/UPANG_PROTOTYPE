<?php
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    Session_start();
    Session_destroy();
    header('Location: ../voter_login.php');
    exit;
}

// Check if selected_candidates session variable is set
if (isset($_SESSION['selected_candidates'])) {

    $lastname = $_SESSION['lastname'];
    $firstname = $_SESSION['firstname'];
    $course_code = $_SESSION['course_code'];
    $student_number = $_SESSION['student_number'];
    $selected_candidates = $_SESSION['selected_candidates'];

    $ballot_type = $_SESSION['ballot_type'];

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="voters_receipt.css">
        <link rel="stylesheet" crossorigin="anonymous" referrerpolicy="no-referrer"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
            integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" />
        <link rel="icon" type="image/x-icon" href="photos/phinma_seal.png" />
        <script src="voters_receipt.js"></script>
        <title>Document</title>
    </head>

    <body>
        <header>
            <div class="logos" onclick="javascript:location.href='finish.php'">
                <img class="phinma_seal" src="../photos/phinma_seal.png">
                <div class="main_title">
                    <h1>University of Pangasinan</h1>
                    <h3>APEC Voting and Tallying System</h3>
                </div>
                <img class="upang_seal" src="../photos/upang_seal.png">
            </div>
        </header>
        <main>
            <div class="disclaimer">
                <h2>~ Voter's Discretion ~</h2>
                <div class="receipt_paragraph">
                    <p>
                        <b>One Time Receipt:</b>
                    </p>
                    <p>
                        Hello, <?php echo $firstname; ?>! This is to inform you that your ballot has been successfully received! Worry not though. This is only a One-Time Receipt, <b>ONLY YOU</b> can see this part. This is just to confirm that your votes (per candidate) are correct and nothing had an error. If you noticed that there is an error, please contact (Insert Contact), and we will respond as immediately as possible. This is also to note to you that if you try to Login again with the provided Voting Account, you will not see the receipt again, just a prompt that you already voted. Thank you, and we appreciate your time! 
                    </p>
                </div>
            
            </div>
            <div class="database_info">
            <?php
                foreach ($selected_candidates as $candidate) { 
                    $lastname = strstr($candidate['candidate'], ',', true);
                    $firstname = substr(strstr($candidate['candidate'], ','), 1);
            ?>
                <div class="candidate_card">
                    <h2>
                        <?php $renamed_position = str_replace("_", " ", $candidate['position']);
                        echo str_replace("Council", "", $renamed_position); ?>
                    </h2>
                    <img src="../../../photos/<?php echo $ballot_type; ?>-Photos/<?php echo $lastname ?>.png">
                    <p>
                        <b>
                            <?php 
                                echo $lastname . ',' . $firstname;
                            ?>
                        </b>
                    </p>
                    <p>
                        <?php echo $candidate['party']; ?>
                    </p>
                </div>

            <?php
                }
            ?>
            </div>
        </main>
    </body>

    </html>
<?php
}
?>
