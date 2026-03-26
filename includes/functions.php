<?php
// includes/functions.php - Helper functions

/**
 * Sanitize user input
 * @param string $data
 * @return string
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Check if the user is logged in
 * @return bool
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}
?>
