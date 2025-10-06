<?php
session_start();
include 'db.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $content_id = $_POST['content_id'];
    $progress = $_POST['progress'];

    $sql = "INSERT INTO watch_history (user_id, content_id, progress) VALUES ($user_id, $content_id, $progress) 
            ON DUPLICATE KEY UPDATE progress = $progress, last_watched = CURRENT_TIMESTAMP";
    $conn->query($sql);
}
?>
