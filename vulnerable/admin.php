<?php
session_start();
require 'db_connection.php';
require_once 'vendor/autoload.php';

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = "123";
$token = $_COOKIE['admin'] ?? '';

if ($token) {
    try {
        // Decode token
        $decoded = JWT::decode($token, new Key($key, "HS256"));
        $decoded_array = (array)$decoded;
        $username = $decoded_array['username'];
    } catch (Exception $e) {
        // Beralih ke login jika token tidak valid
        header("Location: index.php");
        exit;
    }
} else {
    // Beralih ke login jika token tidak ditemukan
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
</head>

<body>
    <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
    <a href="logout.php">Logout</a>
    <?php
    // Query untuk mendapatkan semua data dari tabel users
    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Menampilkan data
        while ($row = $result->fetch_assoc()) {
            echo "<p>ID: " . $row["id"] . "</p>";
            echo "<p>Email: " . $row["email"] . "<p>";
            echo "<p>Username: " . $row["username"] . "</p>";
            echo "<p>Password: " . $row["password"] . "</p>";
            echo "<p>Role: " . $row["role"] . "</p>";
            echo "<p>JWT Token: " . $row["jwt_token"] . "</p>";
            echo "<hr>";
        }
    } else {
        echo "No users found.";
    }

    $conn->close();
    ?>
</body>

</html>