<?php 
// Koneksi database
include 'koneksip.php';

// Menangkap data yang dikirim dari form
$id = $_POST['id'];
$name = $_POST['name'];
$category_id = (int) $_POST['category_id']; // Memastikan category_id adalah integer
$price = (float) $_POST['price']; // Memastikan price adalah float
$stock = (int) $_POST['stock']; // Memastikan stock adalah integer

// Mendapatkan nama gambar lama dari database
$stmt = $koneksi->prepare("SELECT image FROM products WHERE id=?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->bind_result($old_image);
$stmt->fetch();
$stmt->close();

// Menangani upload gambar
$uploadOk = 1;
$new_image = $_FILES['image']['name'];
$target_dir = "uploads/"; // Direktori untuk menyimpan gambar

// Pastikan folder uploads ada
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true); // Buat folder jika belum ada
}

// Menyusun path file target
$target_file = $target_dir . basename($new_image);
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Cek apakah ada gambar baru yang di-upload
if ($new_image) {
    // Cek jika file gambar adalah gambar sebenarnya
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Cek jika file sudah ada
    if (file_exists($target_file)) {
        // Jika file dengan nama yang sama sudah ada, hapus file lama sebelum upload yang baru
        unlink($target_file);
    }

    // Cek ukuran file
    if ($_FILES["image"]["size"] > 5000000) { // 5MB
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Hanya izinkan format file tertentu
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Jika tidak ada kesalahan, upload gambar baru
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($new_image)) . " has been uploaded.";
            // Hapus gambar lama jika gambar baru berhasil di-upload
            if ($old_image && file_exists($target_dir . $old_image)) {
                unlink($target_dir . $old_image);
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit();
        }
    }
} else {
    // Jika tidak ada gambar baru, gunakan gambar lama
    $new_image = $old_image;
}

// Menyusun path lengkap untuk gambar baru
$new_image = $new_image ? $target_dir . $new_image : $old_image;

// Menggunakan prepared statement untuk keamanan
$stmt = $koneksi->prepare("UPDATE products SET name=?, category_id=?, price=?, stock=?, image=? WHERE id=?");
$stmt->bind_param('siddsi', $name, $category_id, $price, $stock, $new_image, $id);

if ($stmt->execute()) {
    echo "Update successful!";
} else {
    echo "Error: " . $stmt->error;
}

// Menutup statement
$stmt->close();

// Mengalihkan halaman kembali ke data.php
header("Location: ../edit/datap.php");
exit();
?>
