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
    $nama = $_POST['nama'];

    // Menyiapkan query SQL untuk memasukkan data ke tabel kategori
    $stmt = $conn->prepare("INSERT INTO categories (nama) VALUES (?)");
    $stmt->bind_param("s", $nama); // "s" untuk satu string parameter

    // Menjalankan pernyataan
    if ($stmt->execute()) {
        $message = "Kategori berhasil ditambahkan!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    // Menutup pernyataan
    $stmt->close();
}

// Menutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori Makanan</title>
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
            max-width: 500px;
            margin: 2rem auto;
            padding: 1.5rem;
            transition: transform 0.3s ease;
        }
        .form-container:hover {
            transform: translateY(-5px); /* Subtle hover effect */
        }
        .form-header {
            background: linear-gradient(90deg, #6b7280 0%, #374151 100%);
            color: white;
            border-radius: 12px 12px 0 0;
            padding: 1rem;
            text-align: center;
        }
        .form-group input {
            border: 2px solid #e5e7eb; /* Enhanced border */
            border-radius: 0.375rem;
            padding: 0.75rem;
            transition: border-color 0.3s ease;
        }
        .form-group input:focus {
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
            <h1 class="text-2xl font-bold">Tambah Kategori Makanan</h1>
        </div>
        
        <div class="p-6">
            <?php if (isset($message)): ?>
                <div class="message">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group mb-4">
                    <label for="nama" class="block text-gray-700">Nama Kategori:</label>
                    <input type="text" id="nama" name="nama" placeholder="Masukkan nama kategori" class="mt-1 block w-full border rounded-md p-2 focus:outline-none" required>
                </div>

                <div class="flex justify-between">
                    <button type="submit" class="button-submit">Kirim</button>
                    <button type="button" class="button-back" onclick="window.location.href = 'add.php';">Kembali</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
