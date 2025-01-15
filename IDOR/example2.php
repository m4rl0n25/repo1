<?php
// Database connection
$db = new mysqli('localhost', 'username', 'password', 'example_db');

// Get the `user_id` parameter from the URL
$user_id = $_GET['user_id'];

// Fetch user details directly without verifying access
$query = "SELECT * FROM users WHERE id = $user_id";
$result = $db->query($query);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo "Name: " . htmlspecialchars($user['name']) . "<br>";
    echo "Email: " . htmlspecialchars($user['email']) . "<br>";
} else {
    echo "User not found.";
}

$db->close();
?>
