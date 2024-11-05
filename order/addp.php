<?php
session_start(); // Memulai session
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php'); // Arahkan ke halaman login jika belum login
    exit(); // Menghentikan eksekusi script
} 

// Konfigurasi database
$servername = "localhost";
$username = "root"; // Ganti dengan username database Anda
$password = ""; // Ganti dengan password database Anda
$dbname = "week18"; // Ganti dengan nama database Anda

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Menangani data dari formulir jika formulir disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari formulir
    $nama = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Menangani file gambar
    $image = $_FILES['image']['name'];
    $image_temp = $_FILES['image']['tmp_name'];
    $image_path = "uploads/" . basename($image);

    // Validasi file gambar
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $image_extension = pathinfo($image_path, PATHINFO_EXTENSION);

    if (in_array($image_extension, $allowed_extensions)) {
        // Cek ukuran file (misalnya, maksimal 2MB)
        if ($_FILES['image']['size'] <= 2 * 1024 * 1024) {
            if (move_uploaded_file($image_temp, $image_path)) {
                // Menyiapkan query SQL untuk memasukkan data ke tabel produk
                $sql = "INSERT INTO products (name, category_id, price, stock, image) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("siids", $nama, $category_id, $price, $stock, $image_path);

                if ($stmt->execute()) {
                    $message = "Produk berhasil ditambahkan!";
                } else {
                    $message = "Terjadi kesalahan: " . $stmt->error;
                }

                $stmt->close();
            } else {
                $message = "Terjadi kesalahan saat mengunggah gambar.";
            }
        } else {
            $message = "Ukuran gambar terlalu besar. Maksimal 2MB.";
        }
    } else {
        $message = "Format gambar tidak valid. Hanya diperbolehkan: JPG, JPEG, PNG, GIF.";
    }
}

// Mengambil daftar kategori dari database untuk dropdown
$categories_result = $conn->query("SELECT id, nama FROM categories");
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
    <link rel="icon" href="../ngawilogo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8fafc; /* Light gray background */
        }
        .form-container {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            max-width: 600px;
            margin: 2rem auto;
            transition: transform 0.3s ease;
        }
        .form-container:hover {
            transform: translateY(-5px); /* Subtle hover effect */
        }
        .form-header {
            background: linear-gradient(90deg, #6b7280 0%, #374151 100%);
            color: white;
            border-radius: 12px 12px 0 0;
            padding: 1.5rem;
            text-align: center;
        }
        .form-group input, .form-group select {
            border: 2px solid #e5e7eb; /* Enhanced border */
            border-radius: 0.375rem;
            padding: 0.75rem;
            transition: border-color 0.3s ease;
        }
        .form-group input:focus, .form-group select:focus {
            border-color: #4f46e5; /* Indigo border on focus */
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2);
        }
        .button-submit {
            background-color: #4f46e5; /* Indigo button */
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .button-submit:hover {
            background-color: #4338ca; /* Darker indigo on hover */
        }
        .button-back {
            background-color: #9ca3af; /* Gray button */
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .button-back:hover {
            background-color: #6b7280; /* Darker gray on hover */
        }
        .message {
            margin: 1rem 0;
            padding: 1rem;
            border-radius: 0.375rem;
            background-color: #d1fae5; /* Light green background */
            color: #065f46; /* Dark green text */
            border: 1px solid #34d399; /* Green border */
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h1 class="text-3xl font-bold">Tambah Produk</h1>
        </div>
        
        <div class="p-6">
            <?php if (isset($message)): ?>
                <div class="message">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group mb-4">
                    <label for="name" class="block text-gray-700">Nama Produk:</label>
                    <input type="text" id="name" name="name" placeholder="Masukkan nama produk" class="mt-1 block w-full focus:outline-none" required>
                </div>

                <div class="form-group mb-4">
                    <label for="category_id" class="block text-gray-700">Kategori:</label>
                    <select id="category_id" name="category_id" class="mt-1 block w-full focus:outline-none" required>
                        <?php
                        if ($categories_result->num_rows > 0) {
                            while($row = $categories_result->fetch_assoc()) {
                                echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['nama']) . "</option>";
                            }
                        } else {
                            echo "<option value=''>Tidak ada kategori</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group mb-4">
                    <label for="price" class="block text-gray-700">Harga:</label>
                    <input type="number" id="price" name="price" step="0.01" placeholder="Masukkan harga produk" class="mt-1 block w-full focus:outline-none" required>
                </div>

                <div class="form-group mb-4">
                    <label for="stock" class="block text-gray-700">Stok:</label>
                    <input type="number" id="stock" name="stock" placeholder="Masukkan stok produk" class="mt-1 block w-full focus:outline-none" required>
                </div>

                <div class="form-group mb-4">
                    <label for="image" class="block text-gray-700">Gambar Produk:</label>
                    <input type="file" id="image" name="image" class="mt-1 block w-full text-gray-700 focus:outline-none" accept="image/*" required>
                </div>

                <div class="flex justify-between">
                    <button type="submit" class="button-submit">Simpan Produk</button>
                    <button type="button" class="button-back" onclick="window.location.href = '../add/add.php'">Kembali</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
