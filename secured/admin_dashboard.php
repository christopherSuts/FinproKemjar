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
        // Decode token
        $decoded = JWT::decode($jwt, new Key($key, "HS512"));
    } catch (Exception $e) {
        // Beralih ke login jika token tidak valid
        header("Location: index.php");
        exit;
    }
} elseif (!isset($_SESSION['username'])) {
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
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <a href="logout.php">Logout</a>
    <?php
    // Query untuk mendapatkan semua data dari tabel users
    $sql = "SELECT * FROM user";
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