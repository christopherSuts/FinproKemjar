<?php
session_start();

// Generate token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

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
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ubah Password</title>
</head>
<body>
    <h1>Ubah Password</h1>
    <form action="process_change_password.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <label>Password Baru:</label>
        <input type="password" name="new_password" required>
        <br>
        <button type="submit">Ubah</button>
    </form>
</body>
</html>
