<?php
    session_start();

    // Check if selected_candidates session variable is set
    if (isset($_SESSION['selected_candidates'])) {
        
        $lastname = $_SESSION['lastname'];
        $firstname = $_SESSION['firstname'];
        $course_code = $_SESSION['course_code'];
        $student_number = $_SESSION['student_number'];
        $selected_candidates = $_SESSION['selected_candidates'];

        // Display selected candidates
        echo "<h2>Your Selected Candidates:</h2>";
        foreach ($selected_candidates as $candidate) {
            echo "<p>Position: " . $candidate['position'] . ", Candidate: " . $candidate['candidate'] . ", Party: " . $candidate['party'] . "</p>";
        }

    } else {
        echo "<p>No selected candidates found.</p>";
    }
?>
