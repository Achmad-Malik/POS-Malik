<?php 
session_start(); // Memulai session
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php'); // Arahkan ke halaman login jika belum login
    exit(); // Menghentikan eksekusi script
} 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Penjualan</title>
    <link rel="icon" href="../ngawilogo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f0f4f8; /* Light gray background */
            font-family: 'Arial', sans-serif;
        }
        .overlay {
            background-color: rgba(255, 255, 255, 0.8);
        }
        .sidebar {
            height: 100vh;
            background-color: #2d3748; /* Dark gray for the sidebar */
            padding-top: 20px;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
        }
        .sidebar h2 {
            color: #fff;
            font-size: 2rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .sidebar ul {
            margin-top: 2rem;
            padding: 0;
            list-style: none;
            width: 100%;
        }
        .sidebar ul li a {
            display: flex;
            align-items: center;
            padding: 12px 24px;
            color: #a0aec0;
            font-size: 1.125rem;
            transition: all 0.3s ease;
            border-radius: 8px;
        }
        .sidebar ul li a:hover {
            background-color: #4a5568;
            color: #fff;
            transform: translateX(5px);
            box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #007bff;
            margin: 20px 0;
            font-size: 2.5rem;
            font-weight: bold;
        }
        .table-container {
            max-width: 1000px; /* Width limit for the table */
            margin: 20px auto; /* Center the table */
            padding: 20px; /* Padding around the table */
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 16px;
            text-align: left;
            border: 1px solid #dee2e6;
            transition: background-color 0.3s ease;
        }
        th {
            background-color: #007bff;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #e2e6ea;
        }
        .total-payment {
            font-weight: bold;
            color: #28a745; /* Green for total payment */
        }
        .empty-message {
            text-align: center;
            color: #6c757d; /* Gray color for empty message */
            font-size: 1.2rem;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            color: #2d3748;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<div class="flex">
    <!-- Sidebar -->
    <div class="w-64 h-screen sidebar flex flex-col items-center justify-start fixed top-0 left-0">
        <h2>Ngawi Resto</h2>
        <ul class="space-y-4 w-full">
            <li>
                <a href="../home.php">
                    <i class="fas fa-home mr-3 text-lg"></i> Home
                </a>
            </li>
            <li>
                <a href="../dashboard.php">
                    <i class="fas fa-user-cog mr-3 text-lg"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="../add/add.php">
                    <i class="fas fa-plus-circle mr-3 text-lg"></i> Add
                </a>
            </li>
            <li>
                <a href="../edit/datap.php">
                    <i class="fas fa-cogs mr-3 text-lg"></i> Manage Products
                </a>
            </li>
            <li>
                <a href="../order/order.php">
                    <i class="fas fa-shopping-cart mr-3 text-lg"></i> Transaction
                </a>
            </li>
            <li>
                <a href="salesdetail.php">
                    <i class="fas fa-chart-pie mr-3 text-lg"></i> Sales Detail
                </a>
            </li>
            <?php if (isset($_SESSION['user_id'])): ?>
        <li>
            <a href="../logout.php" class="hover:bg-gray-800 hover:text-white">
                <i class="fas fa-sign-out-alt mr-3 text-lg"></i> Logout
            </a>
        </li>
    <?php endif; ?>
        </ul>
    </div>

    <div class="content flex-grow ml-64 p-8">
        <h1>Detail Penjualan</h1>

        <div class="table-container">
        <?php
        // Menghubungkan ke database
        $host = 'localhost'; // Ganti dengan host database Anda
        $db_name = 'week18'; // Ganti dengan nama database Anda
        $username = 'root'; // Ganti dengan username database Anda
        $password = ''; // Ganti dengan password database Anda

        $conn = new mysqli($host, $username, $password, $db_name);

        // Memeriksa koneksi
        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        // Query untuk mengambil data dari tabel orders
        $sql = "SELECT o.id, o.created_at, o.total_payment, o.total_product, 
                       a.username, c.nama 
                FROM orders o 
                JOIN admins a ON o.admin_id = a.id 
                JOIN customers c ON o.customer_id = c.id";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Menampilkan data
            echo "<table border='1'>
                    <tr>
                        <th>ID</th>
                        <th>Admin</th>
                        <th>Customer</th>
                        <th>Created At</th>
                        <th>Total Payment</th>
                        <th>Total Product</th>
                    </tr>";

            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['id'] . "</td>
                        <td>" . $row['username'] . "</td>
                        <td>" . $row['nama'] . "</td>
                        <td>" . $row['created_at'] . "</td>
                        <td class='total-payment'>" . $row['total_payment'] . "</td>
                        <td>" . $row['total_product'] . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<div class='empty-message'>Tidak ada data.</div>";
        }

        // Menutup koneksi
        $conn->close();
        ?>
        </div>
    </div>
</div>

</body>
</html>
