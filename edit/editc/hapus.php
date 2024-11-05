<?php 
// koneksi database
include 'koneksic.php';

// menangkap data id yang di kirim dari url
$id = $_GET['id'];

// Cek apakah kategori ini masih digunakan oleh produk
$result = mysqli_query($koneksi, "SELECT * FROM products WHERE category_id='$id'");
$products = mysqli_num_rows($result);

if ($products > 0) {
    // Jika ada produk yang menggunakan kategori, tampilkan SweetAlert dan jangan hapus
    echo "<script>
        alert('Ada produk yang menggunakan kategori ini. Tidak dapat menghapus kategori.');
        window.location.href = 'datac.php'; // Kembali ke halaman daftar kategori
    </script>";
} else {
    // Jika tidak ada produk, hapus kategori
    mysqli_query($koneksi, "DELETE FROM categories WHERE id='$id'");
    
    // Mengalihkan halaman kembali ke datac.php
    header("location:datac.php");
}
?>
