<?php
require 'db_connection.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validasi agar tidak menerima input kosong
    if (empty($email) || empty($username) || empty($password)) {
        echo "Semua field harus diisi!";
        exit;
    }

    // Memeriksa apakah email/username sudah terdaftar
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Username atau email sudah terdaftar!";
    } else {
        // Menyimpan data pengguna ke database
        $stmt = $conn->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $username, $password);

        if ($stmt->execute()) {
            echo "Registrasi berhasil! <a href='index.php'>Login</a>";
        } else {
            echo "Terjadi kesalahan saat registrasi!";
        }
    }

    $stmt->close();
}
$conn->close();
?>
