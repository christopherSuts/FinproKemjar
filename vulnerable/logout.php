<?php
session_start();

// Menghapus cookie dengan nama yang sesuai
if (isset($_SESSION['username'])) {
    setcookie($_SESSION['username'], '', time() - 3600, '/'); 
    
    session_unset();  // Menghapus semua variabel session
    session_destroy();  // Menghancurkan session
}

// Arahkan kembali ke halaman login
header("Location: index.php");
exit;
?>
