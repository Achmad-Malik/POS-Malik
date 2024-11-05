<?php
// Include database connection
include 'db_connection.php';

// Get the JSON input
$data = json_decode(file_get_contents('php://input'), true);

// Retrieve values from the JSON input
$admin_email = $data['admin_email'] ?? null;
$customer_phone = $data['customer_phone'] ?? null;
$total_payment = $data['total_payment'] ?? 0;
$total_product = $data['total_product'] ?? 0;

// Assuming you have an array of ordered products with product_id and quantity
$ordered_products = $data['ordered_products'] ?? []; // This should be an array of products

// Prepare to get admin ID
$stmt = $conn->prepare("SELECT id FROM admins WHERE email = ?");
$stmt->bind_param("s", $admin_email);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$admin_id = $admin['id'] ?? null;
$stmt->close();

// Prepare to get customer ID
$stmt = $conn->prepare("SELECT id FROM customers WHERE phone = ?");
$stmt->bind_param("s", $customer_phone);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();
$customer_id = $customer['id'] ?? null;
$stmt->close();

// Check if customer ID and admin ID exist
if (!$customer_id || !$admin_id) {
    echo json_encode(['message' => 'Admin atau pelanggan tidak ditemukan']);
    exit;
}

// Prepare SQL statement to insert the order
$stmt = $conn->prepare("INSERT INTO orders (admin_id, customer_id, total_payment, total_product) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iidi", $admin_id, $customer_id, $total_payment, $total_product);

// Execute the statement and check for errors
if ($stmt->execute()) {
    // Get the last inserted order ID
    $order_id = $stmt->insert_id;

    // Insert into order_products table and update stock for each ordered product
    foreach ($ordered_products as $product) {
        $product_id = $product['product_id'];
        $quantity = $product['quantity'];

        // Insert into order_products
        $order_product_stmt = $conn->prepare("INSERT INTO order_products (order_id, product_id, quantity, total_price) VALUES (?, ?, ?, ?)");
        $total_price = $total_payment; // Assuming total_price is same as total_payment for this order
        $order_product_stmt->bind_param("iiii", $order_id, $product_id, $quantity, $total_price);
        
        // Execute the insert statement
        if (!$order_product_stmt->execute()) {
            // Log or handle error
            error_log("Error inserting order product for product ID $product_id: " . $order_product_stmt->error);
        }

        // Update the product stock in the products table
        $update_stmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $update_stmt->bind_param("ii", $quantity, $product_id);
        
        // Execute the update statement
        if (!$update_stmt->execute()) {
            // Log or handle error
            error_log("Error updating stock for product ID $product_id: " . $update_stmt->error);
        }

        // Close the update statement
        $update_stmt->close();
        // Close the order product insert statement
        $order_product_stmt->close();
    }

    echo json_encode(['message' => 'Order berhasil ditempatkan']);
} else {
    echo json_encode(['message' => 'Terjadi kesalahan saat memproses pesanan: ' . $stmt->error]);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
