<?php
    session_start();

    // Check if selected_candidates session variable is set
    if (isset($_SESSION['selected_candidates'])) {
        $selected_candidates = $_SESSION['selected_candidates'];

        // Display selected candidates
        echo "<h2>Your Selected Candidates:</h2>";
        foreach ($selected_candidates as $candidate) {
            echo "<p>Position: " . $candidate['position'] . ", Candidate: " . $candidate['candidate'] . ", Party: " . $candidate['party'] . "</p>";
        }

        // Clear selected_candidates session variable
        unset($_SESSION['selected_candidates']);
    } else {
        echo "<p>No selected candidates found.</p>";
    }
?>
