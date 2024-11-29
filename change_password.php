<?php
session_start();
if (!isset($_SESSION['username'])) {
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
        <label>Password Baru:</label>
        <input type="password" name="new_password" required>
        <br>
        <button type="submit">Ubah</button>
    </form>
</body>
</html>
