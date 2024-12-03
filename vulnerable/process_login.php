<?php
session_start();
require 'db_connection.php';
require_once 'vendor/autoload.php';

use \Firebase\JWT\JWT;

$key = "123";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    if (!$stmt) {
        die("Kesalahan pada kueri: " . $conn->error); // Debug error
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $db_username, $db_password, $db_role);
        $stmt->fetch();

        // Verifikasi password
        if ($password === $db_password) {
            $_SESSION['username'] = $username;

            // Data untuk JWT
            $payload = array(
                "id" => $user_id,
                "username" => $db_username,
                "role" => $db_role
            );

            // Encode JWT
            $jwt = JWT::encode($payload, $key, 'HS256');

            // Menyimpan JWT ke cookie (bisa ditambah untuk CSRF)
            setcookie($username, $jwt,  [
                'expires' => time() + 31536000,
                'path' => '/',
                'secure' => false, 
                'httponly' => false, 
                'sameSite' => 'None'
            ]);

            // Menyimpan JWT ke database
            $update_stmt = $conn->prepare("UPDATE users SET jwt_token = ? WHERE id = ?");
            $update_stmt->bind_param("si", $jwt, $user_id);
            $update_stmt->execute();

            // Memeriksa role pengguna yang login
            if ($db_role === 'admin') {
                header("Location: admin.php");
                exit();
            } else {
                header("Location: dashboard.php");
                exit();
            }
        } else {
            echo "<script>
                alert('Password salah');
                window.location.href = 'index.php';
            </script>";
            exit();
        }
    } else {
        echo "<script>
            alert('Pengguna tidak ditemukan');
            window.location.href = 'index.php';
        </script>";
        exit();
    }
    $stmt->close();
}
$conn->close();
