<?php

require_once('../_helpers/strip.php');

// Secure connection to the database
$db = new SQLite3('test.db');

// Start session securely
session_start();
if (!isset($_SESSION['user_id'])) {
    die('Unauthorized access');
}

// Secure session handling
$user_id = $_SESSION['user_id']; // Fetch authenticated user's ID
if (!is_numeric($user_id)) {
    die('Invalid session data.');
}

// Validate the `id` parameter
$id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

if ($id !== null && $id > 0) {
    try {
        // Secure query with prepared statements
        $stmt = $db->prepare('SELECT * FROM secrets WHERE id = :id AND user_id = :user_id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
        $result = $stmt->execute();

        // Fetch and display the result
        if ($row = $result->fetchArray()) {
            echo 'Secret: ' . htmlspecialchars($row['secret'], ENT_QUOTES, 'UTF-8'); // Prevent XSS
        } else {
            echo 'No secret found or unauthorized access.';
        }
    } catch (Exception $e) {
        error_log('Database error: ' . $e->getMessage());
        echo 'An error occurred. Please try again later.';
    }

    echo '<br /><br /><a href="/">Go back</a>';
} else {
    try {
        // View all the user's secrets securely
        $stmt = $db->prepare('SELECT * FROM secrets WHERE user_id = :user_id');
        $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
        $result = $stmt->execute();

        echo '<strong>Your secrets</strong><br /><br />';

        while ($row = $result->fetchArray()) {
            echo '<a href="/?id=' . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . '">#' . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . '</a><br />';
        }
    } catch (Exception $e) {
        error_log('Database error: ' . $e->getMessage());
        echo 'An error occurred. Please try again later.';
    }
}
?>
