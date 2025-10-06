<?php
session_start();
include 'db.php';

$featured = [];
$trending = [];
$sql_featured = "SELECT * FROM content WHERE featured = TRUE LIMIT 5";
$result_featured = $conn->query($sql_featured);
if ($result_featured->num_rows > 0) {
    while($row = $result_featured->fetch_assoc()) {
        $featured[] = $row;
    }
}

$sql_trending = "SELECT * FROM content WHERE trending = TRUE LIMIT 5";
$result_trending = $conn->query($sql_trending);
if ($result_trending->num_rows > 0) {
    while($row = $result_trending->fetch_assoc()) {
        $trending[] = $row;
    }
}

$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Netflix Clone - Homepage</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #141414; color: #fff; margin: 0; padding: 0; }
        header { background: #000; padding: 10px; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 24px; color: #e50914; }
        nav a { color: #fff; margin: 0 10px; text-decoration: none; }
        .carousel { position: relative; height: 500px; overflow: hidden; }
        .carousel-item { position: absolute; width: 100%; height: 100%; background-size: cover; background-position: center; transition: opacity 1s ease-in-out; opacity: 0; }
        .carousel-item.active { opacity: 1; }
        .carousel-item::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(to bottom, transparent, #141414); }
        .carousel-content { position: absolute; bottom: 50px; left: 50px; }
        .carousel-content h2 { font-size: 48px; margin: 0; animation: fadeIn 2s; }
        .carousel-content p { font-size: 24px; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .section { padding: 20px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; }
        .card { background: #222; border-radius: 5px; overflow: hidden; transition: transform 0.3s; }
        .card:hover { transform: scale(1.05); box-shadow: 0 0 20px rgba(255,0,0,0.5); }
        .card img { width: 100%; height: 300px; object-fit: cover; }
        .card h3 { padding: 10px; margin: 0; text-align: center; }
        button { background: #e50914; color: #fff; border: none; padding: 10px; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #ff0a16; }
        @media (max-width: 768px) { .carousel { height: 300px; } .carousel-content h2 { font-size: 24px; } .carousel-content p { font-size: 16px; } }
    </style>
</head>
<body>
    <header>
        <div class="logo">Netflix Clone</div>
        <nav>
            <?php if ($is_logged_in): ?>
                <a href="profile.php">Profile</a>
                <a href="watchlist.php">Watchlist</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="signup.php">Signup</a>
            <?php endif; ?>
            <a href="search.php">Search</a>
        </nav>
    </header>
    <div class="carousel">
        <?php foreach ($featured as $index => $item): ?>
            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>" style="background-image: url('<?php echo $item['thumbnail_url']; ?>');">
                <div class="carousel-content">
                    <h2><?php echo $item['title']; ?></h2>
                    <p><?php echo $item['description']; ?></p>
                    <button onclick="watchContent(<?php echo $item['id']; ?>)">Watch Now</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="section">
        <h2>Trending Shows</h2>
        <div class="grid">
            <?php foreach ($trending as $item): ?>
                <div class="card">
                    <img src="<?php echo $item['thumbnail_url']; ?>" alt="<?php echo $item['title']; ?>">
                    <h3><?php echo $item['title']; ?></h3>
                    <button onclick="watchContent(<?php echo $item['id']; ?>)">Watch</button>
                    <?php if ($is_logged_in): ?>
                        <button onclick="addToWatchlist(<?php echo $item['id']; ?>)">Add to Watchlist</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-item');
        function nextSlide() {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        }
        setInterval(nextSlide, 5000);
        function watchContent(id) {
            window.location.href = 'watch.php?id=' + id;
        }
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
