<?php
// config/database.php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'latihan1';

// Create connection
$conn = mysqli_connect($host, $user, $pass, $db);

// Check connection
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8');
?>