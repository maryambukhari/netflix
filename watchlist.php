<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
include 'db.php';

$user_id = $_SESSION['user_id'];
$watchlist = [];
$sql = "SELECT c.* FROM content c JOIN watchlist w ON c.id = w.content_id WHERE w.user_id = $user_id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $watchlist[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watchlist</title>
    <style>
        body { background-color: #141414; color: #fff; font-family: Arial, sans-serif; padding: 20px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; }
        .card { background: #222; border-radius: 5px; overflow: hidden; transition: transform 0.3s; }
        .card:hover { transform: scale(1.05); box-shadow: 0 0 20px rgba(255,0,0,0.5); }
        .card img { width: 100%; height: 300px; object-fit: cover; }
        .card h3 { padding: 10px; margin: 0; text-align: center; }
        button { background: #e50914; color: #fff; border: none; padding: 10px; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #ff0a16; }
        @media (max-width: 768px) { .grid { grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); } }
    </style>
</head>
<body>
    <h2>My Watchlist</h2>
    <div class="grid">
        <?php foreach ($watchlist as $item): ?>
            <div class="card">
                <img src="<?php echo $item['thumbnail_url']; ?>" alt="<?php echo $item['title']; ?>">
                <h3><?php echo $item['title']; ?></h3>
                <button onclick="watchContent(<?php echo $item['id']; ?>)">Watch</button>
            </div>
        <?php endforeach; ?>
    </div>
    <a href="index.php" style="display: block; text-align: center; color: #e50914;">Back to Home</a>
    <script>
        function watchContent(id) {
            window.location.href = 'watch.php?id=' + id;
        }
    </script>
</body>
</html>
