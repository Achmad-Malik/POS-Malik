<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Categories</title>
    <link rel="icon" href="../edit/ngawilogo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        /* Global styles */
        body {
            background: linear-gradient(135deg, #e7e9f2, #f4f4f9);
            font-family: 'Roboto', sans-serif;
            color: #444;
            margin: 0;
            padding: 0;
        }

        /* Header styles */
        header {
            background: linear-gradient(135deg, #6c757d, #343a40);
            color: #ffffff;
            padding: 30px 0; /* Reduced padding */
            text-align: center;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }

        header h2 {
            font-size: 2.5rem; /* Adjusted font size */
            margin: 0;
            font-weight: 700;
        }

        .back-button {
            display: inline-block;
            text-decoration: none;
            color: #fff;
            background-color: #17a2b8; /* Changed to a teal color */
            padding: 10px 20px; /* Reduced padding */
            border-radius: 8px;
            font-size: 16px; /* Adjusted font size */
            text-align: center;
            margin: 20px auto; /* Centered margin */
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .back-button:hover {
            background-color: #138496; /* Darker teal on hover */
            transform: translateY(-2px);
        }

        /* Table container styles */
        .table-container {
            margin: 40px auto; /* Adjusted margin */
            max-width: 70%; /* Reduced max-width */
            padding: 10px; /* Adjusted padding */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            background: #ffffff;
        }

        /* Table styles */
        table {
            width: auto; /* Keep width auto for content fitting */
            border-collapse: collapse;
        }

        th, td {
            padding: 5px 10px; /* Reduced padding for better content fitting */
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #f8f9fa; /* Lighter header background */
            font-weight: 700;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Button styles */
        .btn, .action-button {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: #ffffff;
            padding: 8px 15px; /* Reduced padding for buttons */
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            font-size: 0.875rem; /* Adjusted font size */
            margin: 0 5px;
        }

        .btn:hover, .action-button:hover {
            background: linear-gradient(135deg, #0056b3, #003d80);
            transform: translateY(-2px);
        }

        .action-button {
            min-width: 80px; /* Set a smaller minimum width for the buttons */
        }

        .action-button.edit {
            background: #28a745; /* Green for edit */
        }

        .action-button.edit:hover {
            background: #218838;
        }

        .action-button.delete {
            background: #dc3545; /* Red for delete */
        }

        .action-button.delete:hover {
            background: #c82333;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .table-container {
                max-width: 95%; /* Adjusted max-width for smaller screens */
                padding: 10px;
            }

            .btn, .action-button {
                padding: 8px 10px; /* Adjusted padding for smaller screens */
                font-size: 0.8rem; /* Adjusted font size for smaller screens */
            }

            header h2 {
                font-size: 1.75rem; /* Adjusted font size for smaller screens */
            }
        }
    </style>
</head>
<body>
    <!-- Header section -->
    <header>
        <h2>Data Categories Product</h2>
    </header>
    
    <!-- Back button positioned centrally -->
    <div class="text-center">
        <a href="../datap.php" class="back-button">Return</a>
    </div>

    <!-- Table section -->
    <section class="container table-container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                include 'koneksic.php';
                $no = 1;
                $data = mysqli_query($koneksi, "SELECT * FROM categories");
                while ($d = mysqli_fetch_array($data)) {
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($d['nama']); ?></td>
                    <td>
                        <a href="editc.php?id=<?php echo $d['id']; ?>" class="action-button edit">Edit</a>
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
