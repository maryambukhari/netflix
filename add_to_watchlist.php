<?php
session_start();
include 'db.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $content_id = $_POST['content_id'];

    $sql = "INSERT INTO watchlist (user_id, content_id) VALUES ($user_id, $content_id) ON DUPLICATE KEY UPDATE added_at = CURRENT_TIMESTAMP";
    if ($conn->query($sql) === TRUE) {
        echo "Added to watchlist!";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Please login first.";
}
?>
