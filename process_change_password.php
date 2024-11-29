<?php
session_start();
require 'db_connection.php'; // Hubungkan ke database

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
}
$conn->close();
?>