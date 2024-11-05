<?php 
session_start(); // Memulai session
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php'); // Arahkan ke halaman login jika belum login
    exit(); // Menghentikan eksekusi script
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Product</title>
    <link rel="icon" href="../ngawilogo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        /* Global styles */
        body {
            background: linear-gradient(135deg, #f9f9f9, #e3e4e8);
            font-family: 'Roboto', sans-serif;
            color: #444;
            margin: 0;
            padding: 0;
        }

        /* Header styles */
        header {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: #ffffff;
            padding: 60px 0;
            text-align: center;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }

        header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://www.example.com/your-background-image.jpg') no-repeat center center;
            background-size: cover;
            opacity: 0.2;
            z-index: 0;
        }

        header h2 {
            font-size: 3rem;
            margin: 0;
            font-weight: 700;
            z-index: 1;
            position: relative;
        }

        .back-buttons {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .back-button {
            text-decoration: none;
            color: #fff;
            background-color: #6c757d;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 18px;
            text-align: center;
            margin: 0 10px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .back-button:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        /* Table container styles */
        .table-container {
            margin: 50px auto;
            max-width: 95%;
            padding: 20px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            background: #ffffff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #f1f1f1;
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        /* Button styles */
        .btn, .action-button {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: #ffffff;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            font-size: 0.9rem;
        }

        .btn:hover, .action-button:hover {
            background: linear-gradient(135deg, #5a6268, #343a40);
            transform: translateY(-2px);
        }

        .action-button {
            max-width: 120px;
            margin: 0 5px;
            font-size: 0.8rem;
        }

        .action-button.edit {
            background: #28a745;
        }

        .action-button.edit:hover {
            background: #218838;
        }

        .action-button.delete {
            background: #dc3545;
        }

        .action-button.delete:hover {
            background: #c82333;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .table-container {
                padding: 10px;
            }

            .btn, .action-button {
                padding: 10px 20px;
                font-size: 0.8rem;
            }

            header h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header section -->
    <header>
        <h2>Data Product</h2>
    </header>
    <div class="back-buttons">
        <a href="editc/datac.php" class="back-button">Manage Categories</a>
        <a href="../order/order.php" class="back-button">Return</a>
    </div>

    <!-- Table section -->
    <section class="container table-container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                include 'koneksip.php';
                $no = 1;
                $data = mysqli_query($koneksi, "SELECT * FROM products");
                while ($d = mysqli_fetch_array($data)) {
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($d['name']); ?></td>
                    <td><?php echo htmlspecialchars($d['category_id']); ?></td>
                    <td><?php echo htmlspecialchars($d['price']); ?></td>
                    <td><?php echo htmlspecialchars($d['stock']); ?></td>
                    <td><?php echo htmlspecialchars($d['image']); ?></td>
                    <td>
                        <a href="../order/editp.php?id=<?php echo $d['id']; ?>" class="action-button edit">Edit</a>
                        <a href="hapus.php?id=<?php echo $d['id']; ?>" class="action-button delete" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                    </td>
                </tr>
                <?php 
                }
                ?>
            </tbody>
        </table>
    </section>
</body>
</html>
