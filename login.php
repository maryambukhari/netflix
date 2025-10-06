<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            echo "<script>window.location.href = 'index.php';</script>";
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body { background-color: #141414; color: #fff; font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        form { background: #222; padding: 40px; border-radius: 10px; box-shadow: 0 0 20px rgba(229,9,20,0.5); animation: formAppear 1s; }
        input { display: block; margin: 10px 0; padding: 10px; width: 300px; background: #333; border: none; color: #fff; }
        button { background: #e50914; color: #fff; border: none; padding: 10px; width: 100%; cursor: pointer; transition: background 0.3s, transform 0.3s; }
        button:hover { background: #ff0a16; transform: scale(1.05); }
        @keyframes formAppear { from { opacity: 0; transform: translateY(-50px); } to { opacity: 1; transform: translateY(0); } }
        @media (max-width: 768px) { form { padding: 20px; } input { width: 100%; } }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Login</h2>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <p>Don't have an account? <a href="signup.php" style="color: #e50914;">Signup</a></p>
    </form>
</body>
</html>
