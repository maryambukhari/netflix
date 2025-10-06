<?php
$servername = "localhost"; // Change to your actual host if not localhost
$username = "uczrllawgyzfy";
$password = "tmq3v2ylpxpl";
$dbname = "dbe987yaog2pgc";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
