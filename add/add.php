<?php
session_start(); // Memulai session
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php'); // Arahkan ke halaman login jika belum login
    exit(); // Menghentikan eksekusi script
} 

$servername = "localhost";
$username = "root";
$password = ""; // Sesuaikan dengan password database Anda
$dbname = "week18"; // Ganti dengan nama database Anda

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Add Category or Product</title>
  <link rel="icon" href="../ngawilogo.png" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Open+Sans:wght@300;600&display=swap" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #f0f4f8, #d9e2ec); /* Smooth gradient background */
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0;
      font-family: 'Roboto', sans-serif;
    }

    .overlay {
      background-color: rgba(255, 255, 255, 0.85);
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
      text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
    }

    .sidebar ul {
      margin-top: 2rem;
      padding: 0;
      list-style: none;
      width: 100%;
    }

    .sidebar ul li {
      width: 100%;
    }

    .sidebar ul li a {
      display: flex;
      align-items: center;
      padding: 12px 24px;
      width: 100%;
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

    .add-button {
      display: inline-block;
      background-color: #48bb78;
      color: #fff;
      padding: 12px 24px;
      border-radius: 8px;
      font-family: 'Open Sans', sans-serif;
      font-weight: 600;
      transition: all 0.3s ease;
      box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
      text-align: center;
      margin-top: 1rem;
    }

    .add-button:hover {
      background-color: #38a169;
      transform: scale(1.05);
      box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.2);
    }

    .section-heading {
      font-family: 'Roboto', sans-serif;
      font-weight: 700;
      font-size: 1.75rem;
      color: #2f855a;
      margin-bottom: 1rem;
      text-align: center;
    }

    .form-card {
      background-color: #fff;
      border-radius: 12px;
      padding: 24px;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
      text-align: center;
    }

    .form-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    }

    .container {
      max-width: 900px;
      margin: auto;
      display: grid;
      grid-template-columns: repeat(2, 1fr); /* 2 columns */
      gap: 20px; /* Spacing between cards */
    }
  </style>
</head>
<body>
<div class="flex">
    <!-- Sidebar -->
    <div class="w-64 h-screen sidebar flex flex-col items-center justify-start fixed top-0 left-0">
  <h2 class="text-center mb-6">Ngawi Resto</h2>
  <ul class="space-y-4 w-full">
    <li>
      <a href="../home.php" class="hover:bg-gray-800 hover:text-white">
        <i class="fas fa-home mr-3 text-lg"></i> Home
      </a>
    </li>
    <li>
      <a href="../dashboard.php" class="hover:bg-gray-800 hover:text-white">
        <i class="fas fa-user-cog mr-3 text-lg"></i> Dashboard
      </a>
    </li>
    <li>
      <a href="add.php" class="hover:bg-gray-800 hover:text-white">
        <i class="fas fa-plus-circle mr-3 text-lg"></i> Add
      </a>
    </li>
    <li>
      <a href="../edit/datap.php" class="hover:bg-gray-800 hover:text-white">
        <i class="fas fa-cogs mr-3 text-lg"></i> Manage Products
      </a>
    </li>
    <li>
      <a href="../order/order.php" class="hover:bg-gray-800 hover:text-white">
        <i class="fas fa-shopping-cart mr-3 text-lg"></i> Transaction
      </a>
    </li>
    <li>
      <a href="../salesdetail/salesdetail.php" class="hover:bg-gray-800 hover:text-white">
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

    <div class="flex-grow ml-64 p-6 flex items-center justify-center">
      <div class="container">
        <!-- Add Product Section -->
        <div class="form-card">
          <h3 class="section-heading">Add Product</h3>
          <p class="text-gray-600 mb-4">Add new products to the menu with ease.</p>
          <a href="../order/addp.php" class="add-button">
            <i class="fas fa-plus-circle mr-2"></i>Add Product
          </a>
        </div>

        <!-- Add Category Section -->
        <div class="form-card">
          <h3 class="section-heading text-blue-600">Add Category</h3>
          <p class="text-gray-600 mb-4">Create new product categories for better organization.</p>
          <a href="addc.php" class="add-button bg-blue-600 hover:bg-blue-700">
            <i class="fas fa-plus-circle mr-2"></i>Add Category
          </a>
        </div>

        <!-- Add Admin Section -->
        <div class="form-card">
          <h3 class="section-heading text-green-600">Add Admins</h3>
          <p class="text-gray-600 mb-4">Add new Admins to the menu with ease.</p>
          <a href="adda.php" class="add-button">
            <i class="fas fa-plus-circle mr-2"></i>Add Admins
          </a>
        </div>

        <!-- Add Customer Section -->
        <div class="form-card">
          <h3 class="section-heading text-yellow-600">Add Customers</h3>
          <p class="text-gray-600 mb-4">Register new customers quickly.</p>
          <a href="addcu.php" class="add-button bg-yellow-500 hover:bg-yellow-600">
            <i class="fas fa-plus-circle mr-2"></i>Add Customers
          </a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
