<?php 
// Koneksi database
include 'koneksic.php';

// Menangkap data yang dikirim dari form
$id = $_POST['id'];
$name = $_POST['nama'];

// Menggunakan prepared statement untuk keamanan
$stmt = $koneksi->prepare("UPDATE categories SET nama=? WHERE id=?");
$stmt->bind_param('si', $name, $id);

// Eksekusi statement dan cek hasilnya
if ($stmt->execute()) {
    echo "Update successful!";
} else {
    echo "Error: " . $stmt->error;
}

// Menutup statement
$stmt->close();

// Mengalihkan halaman kembali ke datac.php
header("Location: datac.php");
exit();
?>
