<?php
session_start();
include 'db.php';

$search_results = [];
$genres = ['Action', 'Drama', 'Sci-Fi', 'Comedy', 'Animation', 'Fantasy']; // Sample genres

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['query'])) {
    $query = $_GET['query'];
    $genre = isset($_GET['genre']) ? $_GET['genre'] : '';
    $actor = isset($_GET['actor']) ? $_GET['actor'] : '';

    $sql = "SELECT * FROM content WHERE title LIKE '%$query%'";
    if ($genre) $sql .= " AND genre = '$genre'";
    if ($actor) $sql .= " AND actors LIKE '%$actor%'";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $search_results[] = $row;
        }
    }
}

// Recommendations
$recommendations = [];
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql_hist = "SELECT c.genre FROM content c JOIN watch_history h ON c.id = h.content_id WHERE h.user_id = $user_id GROUP BY c.genre ORDER BY COUNT(*) DESC LIMIT 1";
    $result_hist = $conn->query($sql_hist);
    if ($result_hist->num_rows > 0) {
        $top_genre = $result_hist->fetch_assoc()['genre'];
        $sql_rec = "SELECT * FROM content WHERE genre = '$top_genre' LIMIT 5";
        $result_rec = $conn->query($sql_rec);
        if ($result_rec->num_rows > 0) {
            while($row = $result_rec->fetch_assoc()) {
                $recommendations[] = $row;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <style>
        body { background-color: #141414; color: #fff; font-family: Arial, sans-serif; padding: 20px; }
        form { margin-bottom: 20px; }
        input, select { padding: 10px; background: #333; border: none; color: #fff; margin-right: 10px; }
        button { background: #e50914; color: #fff; border: none; padding: 10px; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #ff0a16; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; }
        .card { background: #222; border-radius: 5px; overflow: hidden; transition: transform 0.3s; }
        .card:hover { transform: scale(1.05); box-shadow: 0 0 20px rgba(255,0,0,0.5); }
        .card img { width: 100%; height: 300px; object-fit: cover; }
        .card h3 { padding: 10px; margin: 0; text-align: center; }
        @media (max-width: 768px) { input, select { width: 100%; margin-bottom: 10px; } button { width: 100%; } }
    </style>
</head>
<body>
    <h2>Search Content</h2>
    <form method="GET">
        <input type="text" name="query" placeholder="Title" required>
        <select name="genre">
            <option value="">Genre</option>
            <?php foreach ($genres as $g): ?>
                <option value="<?php echo $g; ?>"><?php echo $g; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="actor" placeholder="Actor">
        <button type="submit">Search</button>
    </form>
    <div class="grid">
        <?php foreach ($search_results as $item): ?>
            <div class="card">
                <img src="<?php echo $item['thumbnail_url']; ?>" alt="<?php echo $item['title']; ?>">
                <h3><?php echo $item['title']; ?></h3>
                <button onclick="watchContent(<?php echo $item['id']; ?>)">Watch</button>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if (!empty($recommendations)): ?>
        <h2>Recommendations</h2>
        <div class="grid">
            <?php foreach ($recommendations as $item): ?>
                <div class="card">
                    <img src="<?php echo $item['thumbnail_url']; ?>" alt="<?php echo $item['title']; ?>">
                    <h3><?php echo $item['title']; ?></h3>
                    <button onclick="watchContent(<?php echo $item['id']; ?>)">Watch</button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <a href="index.php" style="display: block; text-align: center; color: #e50914;">Back to Home</a>
    <script>
        function watchContent(id) {
            window.location.href = 'watch.php?id=' + id;
        }
    </script>
</body>
</html>
