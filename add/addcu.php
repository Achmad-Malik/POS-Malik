<?php
session_start(); // Memulai session
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php'); // Arahkan ke halaman login jika belum login
    exit(); // Menghentikan eksekusi script
} 
include('db.php'); // Include your database connection file

$successMessage = ""; // Inisialisasi variabel untuk pesan sukses

if (isset($_POST['register'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $alamat = $_POST['alamat'];

    // Sanitize input
    $nama = mysqli_real_escape_string($conn, $nama);
    $email = mysqli_real_escape_string($conn, $email);
    $phone = mysqli_real_escape_string($conn, $phone);
    $alamat = mysqli_real_escape_string($conn, $alamat);

    // Check if user already exists
    $query = "SELECT * FROM customers WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['message'] = 'Email sudah terdaftar. Silakan gunakan email lain.';
    } else {
        // Insert new user
        $query = "INSERT INTO customers (nama, email, phone, alamat) VALUES ('$nama', '$email', '$phone', '$alamat')";
        if (mysqli_query($conn, $query)) {
            $successMessage = 'Registration Successful.';
        } else {
            $_SESSION['message'] = 'Terjadi kesalahan saat registrasi.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun</title>
    <link rel="icon" href="../ngawilogo.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e8f0ff, #d4e4ff, #b0c9ff);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        h2 {
            color: #333;
            font-weight: 500;
            margin-bottom: 30px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 10px;
            font-size: 14px;
            color: #666;
        }
        input[type="text"],
        input[type="email"],
        textarea {
            margin-bottom: 20px;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        textarea {
            resize: none;
            height: 80px;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        textarea:focus {
            border-color: #007BFF;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }
        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 15px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        p {
            margin-top: 15px;
            font-size: 14px;
        }
        a {
            color: #007BFF;
            text-decoration: none;
            font-weight: 500;
            margin-top: 15px;
            display: inline-block;
        }
        a:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Customer Registration</h2>
        <form method="post" action="">
            <label for="nama"></label>
            <input type="text" id="nama" name="nama" required placeholder="Name">
            <label for="email"></label>
            <input type="email" id="email" name="email" required placeholder="Email">
            <label for="phone"></label>
            <input type="text" id="phone" name="phone" required placeholder="Phone Number">
            <label for="alamat"></label>
            <textarea id="alamat" name="alamat" required placeholder="Address"></textarea>
            <input type="submit" name="register" value="Registration">
            <a href="add.php">Return</a>
        </form>
        <?php
        if (isset($_SESSION['message'])) {
            echo "<p class='message'>" . $_SESSION['message'] . "</p>";
            unset($_SESSION['message']);
        }
        ?>
    </div>

    <script>
        <?php if (!empty($successMessage)): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?php echo $successMessage; ?>',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'addcu.php'; // Redirect to login
                }
            });
        <?php endif; ?>
    </script>
</body>
</html>
