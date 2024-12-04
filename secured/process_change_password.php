<?php
session_start();
require 'db_connection.php';
require_once 'vendor/autoload.php';

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = "O3nmMs3K4cfYA3dQBj0FzMFiGVrL9WGj";

if (isset($_COOKIE[$_SESSION['username']])) {
    $jwt = $_COOKIE[$_SESSION['username']];

    try {
        // Decoding token
        $decoded = JWT::decode($jwt, new Key($key, "HS512"));
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

    $options = ['cost' => 12];
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT, $options);

    // Update password di database dengan
    $stmt = $conn->prepare("UPDATE user SET password = ? WHERE username = ?");
    $stmt->bind_param("ss", $hashed_password, $username);

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

