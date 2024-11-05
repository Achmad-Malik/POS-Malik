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

// Handle form submission for categories
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['category_name'])) {
    $categoryName = htmlspecialchars(trim($_POST['category_name'])); // Sanitasi input

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO categories (nama) VALUES (?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $categoryName);

    // Execute the statement
    if ($stmt->execute()) {
        $message = "Kategori berhasil ditambahkan!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Fetch categories
$sql = "SELECT id, nama FROM categories";
$result = $conn->query($sql);
if ($result === false) {
    die("Error fetching categories: " . $conn->error);
}
$categories = [];

while($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

// Fetch products
$product_sql = "SELECT p.id, p.name, p.price, p.stock, p.image, c.nama AS category_name FROM products p JOIN categories c ON p.category_id = c.id";
$product_result = $conn->query($product_sql);
if ($product_result === false) {
    die("Error fetching products: " . $conn->error);
}
$products = [];

while($row = $product_result->fetch_assoc()) {
    $products[] = $row;
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Head content tetap sama -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Restro POS</title>
    <link rel="icon" href="../ngawilogo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Open+Sans:wght@300;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
    /* CSS tetap sama */
    body {
        background-color: #f7fafc; /* Light gray background */
        font-family: 'Open Sans', sans-serif;
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
    .product-card {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 400px; /* Ensure uniform product card height */
        transition: all 0.3s ease;
        border-radius: 0.5rem; /* Rounded corners */
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        background-color: #fff; /* White background */
    }
    .product-card:hover {
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        transform: translateY(-5px);
        border: 1px solid #63b3ed; /* Border on hover */
    }
    .product-card img {
        object-fit: cover;
        width: 100%;
        height: 200px; /* Fixed height for image */
        border-radius: 0.5rem; /* Rounded corners */
    }
    .product-price {
        color: #2b6cb0; /* Blue color for price */
        font-weight: 700; /* Bold for emphasis */
        font-size: 1.2rem; /* Font size for price */
    }
    .product-stock {
        color: red; /* Red color for out of stock */
    }.flex-grow {
    margin-top: 20px; /* Memberikan jarak antara kategori button dan produk */
}

.order-detail {
    margin-top: 20px; /* Memberikan jarak atas untuk order detail */
    padding: 1rem; /* Penambahan padding untuk estetik */
        background-color: #fff; /* White background for order details */
        border-radius: 0.5rem; /* Rounded corners */
        padding: 1rem; /* Increased padding for better spacing */
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        overflow-y: auto; /* Add scrollbar if needed */
        max-height: 1000px; /* Set a maximum height */
        transition: all 0.3s; /* Transition effect */
    }
    .quantity-control {
        display: flex;
        align-items: center;
        margin-top: 10px;
    }
    .quantity-button {
        cursor: pointer;
        background-color: #63b3ed;
        color: white;
        border: none;
        border-radius: 0.25rem;
        padding: 0.5rem;
        margin: 0 0.25rem;
        transition: background-color 0.3s; /* Transition effect */
    }
    .quantity-button:hover {
        background-color: #3182ce; /* Darker blue on hover */
    }
    .disabled {
        opacity: 0.5;
        pointer-events: none; /* Disable clicking */
    }
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .total-price {
        font-size: 1.5rem; /* Larger font for total price */
        font-weight: bold;
        color: #2b6cb0; /* Color for total */
    }
</style>
<body class="font-sans">
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
                <a href="../add/add.php" class="hover:bg-gray-800 hover:text-white">
                    <i class="fas fa-plus-circle mr-3 text-lg"></i> Add
                </a>
            </li>
            <li>
                <a href="../edit/datap.php" class="hover:bg-gray-800 hover:text-white">
                    <i class="fas fa-cogs mr-3 text-lg"></i> Manage Products
                </a>
            </li>
            <li>
                <a href="order.php" class="hover:bg-gray-800 hover:text-white">
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

    <!-- Main content -->
    <div class="flex-1 ml-64 p-6">
    <h1 class="text-3xl font-semibold mb-4">Product List</h1>
      <!-- Category Buttons -->
      <div class="mt-8 flex space-x-4 justify-center">
        <button class="category-button bg-gray-500 text-white py-2 px-6 rounded-full shadow-md hover:bg-gray-600 focus:outline-none"
                data-category="all">
          Show All
        </button>
        <?php foreach ($categories as $category): ?>
          <button class="category-button bg-blue-500 text-white py-2 px-6 rounded-full shadow-md hover:bg-blue-600 focus:outline-none"
                  data-category="<?php echo htmlspecialchars($category['nama']); ?>">
            <?php echo htmlspecialchars($category['nama']); ?>
          </button>
        <?php endforeach; ?>
      </div>

    <!-- Main Content -->
    <!-- Hapus ml-64 dari kelas flex di sini -->
    <div class="flex-grow p-6 flex">
    <!-- Product List -->
    <div class="w-full">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6 mt-4">
            <?php foreach ($products as $product): ?>
                <div class="border rounded-lg p-4 bg-white shadow-md product-card" 
                     data-category="<?php echo htmlspecialchars($product['category_name']); ?>"
                     data-id="<?php echo $product['id']; ?>"
                     data-name="<?php echo htmlspecialchars($product['name']); ?>"
                     data-price="<?php echo $product['price']; ?>"
                     data-stock="<?php echo $product['stock']; ?>">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="mb-4">
                    <h4 class="text-xl font-semibold"><?php echo htmlspecialchars($product['name']); ?></h4>
                    <p class="product-category">Category: <?php echo htmlspecialchars($product['category_name']); ?></p>
                    <p class="product-price">Harga: Rp<?php echo number_format($product['price'], 2, ',', '.'); ?></p>
                    <p class="product-stock" id="productStock_<?php echo $product['id']; ?>">Stok: <?php echo htmlspecialchars($product['stock']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Order Detail Section -->
    <div class="w-1/2 ml-6 mt-15">
        <div class="order-detail">
            <h2 class="text-xl font-semibold">Order Details</h2>
            <form id="orderForm">
                <div class="mb-4">
                    <label for="admin_email" class="block text-gray-700 font-semibold mb-2"></label>
                    <input type="email" id="admin_email" name="admin_email" required 
                           class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Admin Email"/>
                </div>
                <div class="mb-4">
                    <label for="customer_phone" class="block text-gray-700 font-semibold mb-2"></label>
                    <input type="text" id="customer_phone" name="customer_phone" required 
                           class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Customer Phone" />
                </div>
                <div id="orderSummary" class="mb-4"></div>
                <button type="submit" 
                    class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600 transition duration-200">Submit Order</button>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const categoryButtons = document.querySelectorAll('.category-button');
    const products = document.querySelectorAll('.product-card');

    // Add click event listeners to product cards
    products.forEach(product => {
        product.addEventListener('click', () => {
            const productId = product.getAttribute('data-id');
            const productName = product.getAttribute('data-name');
            const productPrice = parseFloat(product.getAttribute('data-price'));
            const productStock = parseInt(product.getAttribute('data-stock'));

            addProduct(productId, productName, productPrice, productStock);
        });
    });

    // Existing category button click functionality
    categoryButtons.forEach(button => {
        button.addEventListener('click', () => {
            const selectedCategory = button.getAttribute('data-category');

            products.forEach(product => {
                const productCategory = product.getAttribute('data-category');
                if (selectedCategory === 'all' || productCategory === selectedCategory) {
                    product.style.display = 'block';
                } else {
                    product.style.display = 'none';
                }
            });

            categoryButtons.forEach(btn => {
                if (btn.getAttribute('data-category') === selectedCategory) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        });
    });
});

let orderItems = [];

function addProduct(productId, productName, productPrice, productStock) {
    if (productStock <= 0) {
        Swal.fire({
            icon: 'error',
            title: 'Produk tidak tersedia',
            text: 'Stok produk ini habis.',
            confirmButtonText: 'OK'
        });
        return;
    }

    const existingItem = orderItems.find(item => item.id === productId);
    if (existingItem) {
        if (existingItem.quantity < productStock) {
            existingItem.quantity++;
            productStock--;
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Stok sudah mencapai batas maksimum!',
                confirmButtonText: 'OK'
            });
        }
    } else {
        orderItems.push({
            id: productId,
            name: productName,
            price: productPrice,
            quantity: 1,
            stock: productStock
        });
        productStock--;
    }

    updateProductStockDisplay(productId, productStock);
    updateOrderSummary();
}

function updateProductStockDisplay(productId, newStock) {
    const stockElement = document.getElementById(`productStock_${productId}`);
    if (stockElement) {
        stockElement.textContent = `Stok: ${newStock}`;
    }
}

function updateOrderSummary() {
    const summaryDiv = document.getElementById('orderSummary');
    summaryDiv.innerHTML = '';

    let totalAmount = 0;

    orderItems = orderItems.filter(item => item.quantity > 0); // Remove items with quantity 0

    orderItems.forEach(item => {
        const totalPrice = item.price * item.quantity;
        totalAmount += totalPrice;

        summaryDiv.innerHTML += `
            <div class="flex justify-between items-center mb-2">
                <span>${item.quantity} x ${item.name}</span>
                <span class="product-price">Rp${totalPrice.toLocaleString('id-ID', { minimumFractionDigits: 2 })}</span>
                <div>
                    <button onclick="changeQuantity('${item.id}', 1)" class="bg-green-500 text-white rounded px-2 py-1">+</button>
                    <button onclick="changeQuantity('${item.id}', -1)" class="bg-red-500 text-white rounded px-2 py-1">-</button>
                </div>
            </div>
        `;
    });

    const totalDiv = document.createElement('div');
    totalDiv.className = 'total-price';
    totalDiv.innerHTML = `Total: Rp${totalAmount.toLocaleString('id-ID', { minimumFractionDigits: 2 })}`;
    summaryDiv.appendChild(totalDiv);
}

function changeQuantity(productId, change) {
    const item = orderItems.find(item => item.id === productId);
    if (item) {
        // Cek apakah perubahan jumlah melebihi stok yang tersisa
        if (change > 0 && item.quantity >= item.stock) {
            Swal.fire({
                icon: 'warning',
                title: 'Stok sudah mencapai batas maksimum!',
                confirmButtonText: 'OK'
            });
            return; // Hentikan eksekusi jika batas maksimum tercapai
        }
        
        // Update jumlah pesanan
        item.quantity += change;
        if (item.quantity < 1) {
            orderItems = orderItems.filter(item => item.id !== productId); // Hapus item jika jumlah kurang dari 1
        }
        updateOrderSummary(); // Perbarui ringkasan pesanan setelah perubahan
    }
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('orderForm').addEventListener('submit', function (event) {
        event.preventDefault();

        const adminEmail = document.getElementById('admin_email').value;
        const customerPhone = document.getElementById('customer_phone').value;

        if (orderItems.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Tidak ada pesanan',
                text: 'Silakan tambahkan produk terlebih dahulu.',
                confirmButtonText: 'OK'
            });
            return;
        }
        const totalProduct = orderItems.reduce((sum, item) => sum + item.quantity, 0);
        const totalPayment = orderItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);

        const orderedProducts = orderItems.map(item => ({
            product_id: item.id,
            quantity: item.quantity
        }));

        fetch('submit_order', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                admin_email: adminEmail,
                customer_phone: customerPhone,
                total_payment: totalPayment,
                total_product: totalProduct,
                ordered_products: orderedProducts
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.message === "Order berhasil ditempatkan") {
                Swal.fire({
                    icon: 'success',
                    title: 'Pesanan berhasil diproses!',
                    text: data.message,
                    confirmButtonText: 'OK'
                }).then(() => {
                    document.getElementById('orderForm').reset();
                    orderItems = [];
                    updateOrderSummary();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan!',
                    text: data.message,
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Terjadi kesalahan!',
                text: 'Gagal mengirim data.',
                confirmButtonText: 'OK'
            });
        });
    });
});
</script>
