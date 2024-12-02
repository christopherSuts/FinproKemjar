<?php
session_start();
require 'db_connection.php';
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token tidak valid. Operasi dihentikan.");
    }
    
    $new_password = $_POST['new_password'];
    $username = $_SESSION['username'];

    // Update password di database tanpa hashing
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
    $stmt->bind_param("ss", $new_password, $username);

    if ($stmt->execute()) {
        echo "Password berhasil diperbarui. <a href='dashboard.php'>Kembali ke Dashboard</a>";
    } else {
        echo "Gagal memperbarui password: " . $stmt->error;
    }

    $stmt->close();

    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$conn->close();
?>

