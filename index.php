<?php
session_start();
if (isset($_SESSION['jwt'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form action="process_login.php" method="POST">
        <label>Username:</label>
        <input type="text" name="username" required><br><br>
        
        <label>Password:</label>
        <input type="password" name="password" required><br><br>
        
        <button type="submit">Login</button>
    </form>

    <br>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</body>
</html>