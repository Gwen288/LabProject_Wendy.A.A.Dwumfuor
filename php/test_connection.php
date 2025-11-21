<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$env = parse_ini_file(__DIR__ . "/../env/connect.env");  // adjust path

$host = $env['host'];
$user = $env['user'];
$pass = $env['pass'];
$db   = $env['database'];

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
} else {
    echo "✅ Connected successfully to DB: " . $db;
}
?>
