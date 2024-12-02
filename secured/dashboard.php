<?php
session_start();
require_once 'vendor/autoload.php';

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = "123";

if (isset($_COOKIE[$_SESSION['username']])) {
    $jwt = $_COOKIE[$_SESSION['username']];

    try {
        // Decoding token
        $decoded = JWT::decode($jwt, new Key($key, "HS256"));
    } catch (Exception $e) {
        // Beralih ke login jika token tidak valid
        header("Location: index.php");
        exit;
    }
} elseif (!isset($_SESSION['username'])) {
    // Beralih ke login jika token tidak ada
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h1>Selamat datang, <?php echo $_SESSION['username']; ?>!</h1>
    <a href="change_password.php">Ubah Password</a>
    <br>
    <a href="logout.php">Logout</a>
</body>
</html>
