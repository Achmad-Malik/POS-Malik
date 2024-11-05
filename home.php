<?php 
session_start(); // Memulai session
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); // Arahkan ke halaman login jika belum login
    exit(); // Menghentikan eksekusi script
}

$servername = "localhost";
$username = "root";
$password = ""; // Sesuaikan dengan password database kamu
$dbname = "week18"; // Sesuaikan dengan nama database kamu

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch total products
$total_products_sql = "SELECT COUNT(*) AS total_products FROM products";
$total_products_result = $conn->query($total_products_sql);
$total_products = $total_products_result->fetch_assoc()['total_products'];

// Fetch total stock
$total_stock_sql = "SELECT SUM(stock) AS total_stock FROM products";
$total_stock_result = $conn->query($total_stock_sql);
$total_stock = $total_stock_result->fetch_assoc()['total_stock'];

// Fetch total sales price
$total_sales_sql = "SELECT SUM(total_payment) AS total_sales FROM orders";
$total_sales_result = $conn->query($total_sales_sql);
$total_sales = $total_sales_result->fetch_assoc()['total_sales'];

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Restro POS</title>
  <link rel="icon" href="ngawilogo.png" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Open+Sans:wght@300;600&display=swap" rel="stylesheet">
  <style>
    body {
      background-color: #d3d3d3; /* Light gray background */
    }
    .overlay {
      background-color: rgba(255, 255, 255, 0.9); /* Slightly transparent white */
    }
    .category-button {
      transition: all 0.3s ease;
      font-family: 'Open Sans', sans-serif;
      font-weight: 600;
    }
    .category-button:hover {
      transform: scale(1.05);
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
    .stat-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      background-color: #f0f0f0; /* Light gray for stat cards to match background */
    }
    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }
    .text-highlight {
      color: #000000; /* Hitam untuk warna judul */
    }
  </style>
</head>
<body class="font-sans">
  
  <div class="flex">
    <!-- Sidebar -->
    <div class="w-64 h-screen sidebar flex flex-col items-center justify-start fixed top-0 left-0">
      <h2 class="text-center mb-6">Ngawi Resto</h2>
      <ul class="space-y-4 w-full">
        <li>
          <a href="home.php" class="hover:bg-gray-800 hover:text-white">
            <i class="fas fa-home mr-3 text-lg"></i> Home
          </a>
        </li>
        <li>
          <a href="dashboard.php" class="hover:bg-gray-800 hover:text-white">
            <i class="fas fa-user-cog mr-3 text-lg"></i> Dashboard
          </a>
        </li>
        <li>
          <a href="add/add.php" class="hover:bg-gray-800 hover:text-white">
            <i class="fas fa-plus-circle mr-3 text-lg"></i> Add
          </a>
        </li>
        <li>
          <a href="edit/datap.php" class="hover:bg-gray-800 hover:text-white">
            <i class="fas fa-cogs mr-3 text-lg"></i> Manage Products
          </a>
        </li>
        <li>
          <a href="order/order.php" class="hover:bg-gray-800 hover:text-white">
            <i class="fas fa-shopping-cart mr-3 text-lg"></i> Transaction
          </a>
        </li>
        <li>
          <a href="salesdetail/salesdetail.php" class="hover:bg-gray-800 hover:text-white">
            <i class="fas fa-chart-pie mr-3 text-lg"></i> Sales Detail
          </a>
        </li>
        <?php if (isset($_SESSION['user_id'])): ?>
        <li>
            <a href="logout.php" class="hover:bg-gray-800 hover:text-white">
                <i class="fas fa-sign-out-alt mr-3 text-lg"></i> Logout
            </a>
        </li>
    <?php endif; ?>
      </ul>
    </div>

    <!-- Main content -->
    <div class="flex-1 ml-64 p-8">
      <div class="text-center overlay p-8 rounded-lg shadow-lg mb-8">
        <h2 class="text-4xl font-bold mb-2 text-highlight">Welcome Admins</h2>
        <p class="text-gray-700 italic">"Don't Be Weird If You Don't Want To Take It Out"</p>
      </div>

      <!-- Display totals -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="stat-card p-4 border rounded-lg shadow-lg bg-blue-200 hover:bg-blue-300 transition duration-300">
          <h3 class="text-xl font-bold text-blue-800">Total Products</h3>
          <p class="text-3xl font-semibold text-blue-900"><?php echo $total_products; ?></p>
        </div>
        <div class="stat-card p-4 border rounded-lg shadow-lg bg-green-200 hover:bg-green-300 transition duration-300">
          <h3 class="text-xl font-bold text-green-800">Total Stock</h3>
          <p class="text-3xl font-semibold text-green-900"><?php echo $total_stock; ?></p>
        </div>
        <div class="stat-card p-4 border rounded-lg shadow-lg bg-yellow-200 hover:bg-yellow-300 transition duration-300">
          <h3 class="text-xl font-bold text-yellow-800">Total Sales Price</h3>
          <p class="text-3xl font-semibold text-yellow-900">Rp. <?php echo number_format($total_sales, 0, ',', '.'); ?></p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
