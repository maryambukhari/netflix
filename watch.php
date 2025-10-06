<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
include 'db.php';

$content_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM content WHERE id = $content_id";
$result = $conn->query($sql);
$content = $result->fetch_assoc();

// Get progress
$progress = 0;
$sql_progress = "SELECT progress FROM watch_history WHERE user_id = $user_id AND content_id = $content_id";
$result_progress = $conn->query($sql_progress);
if ($result_progress->num_rows > 0) {
    $progress = $result_progress->fetch_assoc()['progress'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watching <?php echo $content['title']; ?></title>
    <style>
        body { background-color: #000; margin: 0; padding: 0; }
        video { width: 100%; height: 100vh; object-fit: contain; }
        .controls { position: absolute; bottom: 20px; left: 20px; color: #fff; }
        .controls button { background: #e50914; border: none; padding: 10px; cursor: pointer; margin-right: 10px; transition: background 0.3s; }
        .controls button:hover { background: #ff0a16; }
        @media (max-width: 768px) { .controls { bottom: 10px; left: 10px; } .controls button { padding: 5px; } }
    </style>
</head>
<body>
    <video id="videoPlayer" controls autoplay>
        <source src="<?php echo $content['video_url']; ?>" type="video/mp4">
    </video>
    <div class="controls">
        <button onclick="addToWatchlist(<?php echo $content_id; ?>)">Add to Watchlist</button>
        <a href="index.php" style="color: #fff;">Back</a>
    </div>
    <script>
        const video = document.getElementById('videoPlayer');
        video.currentTime = <?php echo $progress; ?>;
        video.addEventListener('timeupdate', () => {
            fetch('save_progress.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'content_id=<?php echo $content_id; ?>&progress=' + Math.floor(video.currentTime)
            });
        });
        function addToWatchlist(id) {
            fetch('add_to_watchlist.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'content_id=' + id
            }).then(response => response.text()).then(data => alert(data));
        }
    </script>
</body>
</html>
