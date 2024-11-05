<?php
$servername = "localhost";
$username = "root"; // default username untuk XAMPP atau WAMP
$password = ""; // default password untuk XAMPP atau WAMP
$dbname = "week18";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
