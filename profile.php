<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
include 'db.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    // Add file upload for profile pic if needed, but skipping for simplicity
    $sql_update = "UPDATE users SET username = '$username', email = '$email' WHERE id = $user_id";
    $conn->query($sql_update);
    echo "<script>window.location.href = 'profile.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        body { background-color: #141414; color: #fff; font-family: Arial, sans-serif; padding: 20px; }
        form { max-width: 400px; margin: auto; background: #222; padding: 20px; border-radius: 10px; box-shadow: 0 0 20px rgba(229,9,20,0.5); }
        input { display: block; margin: 10px 0; padding: 10px; width: 100%; background: #333; border: none; color: #fff; }
        button { background: #e50914; color: #fff; border: none; padding: 10px; width: 100%; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #ff0a16; }
        h2 { text-align: center; }
        @media (max-width: 768px) { form { padding: 10px; } }
    </style>
</head>
<body>
    <h2>Profile Management</h2>
    <form method="POST">
        <input type="text" name="username" value="<?php echo $user['username']; ?>" required>
        <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
        <!-- Add password change if needed -->
        <button type="submit">Update Profile</button>
    </form>
    <a href="index.php" style="display: block; text-align: center; color: #e50914;">Back to Home</a>
</body>
</html>
