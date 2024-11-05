<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data</title>
    <link rel="icon" href="../edit/ngawilogo.png" type="image/x-icon">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-size: cover;
            background-position: center;
            background-blend-mode: multiply;
            margin: 0;
            padding: 0;
            height: 100vh;
            width: 100vw;
        }

        .container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 500px;
            width: 100%;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            box-sizing: border-box;
        }

        h2, h3 {
            color: #333;
            text-align: center;
        }

        .back-button {
            display: inline-block;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
            margin-bottom: 20px;
            display: block;
            width: fit-content;
            margin-left: auto;
            margin-right: auto;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #0056b3;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 18px;
            color: #666;
        }

        .form-group input[type="text"],
        .form-group select {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-group input[type="file"] {
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 5px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Data</h2>
        <a href="datac.php" class="back-button">Kembali</a>

        <?php
        include 'koneksic.php';
        $id = intval($_GET['id']);
        $data = mysqli_query($koneksi, "SELECT * FROM categories WHERE id='$id'");
        if ($d = mysqli_fetch_array($data)) {
        ?>
        <form method="post" action="update" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($d['nama']); ?>">
            </div>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($d['id']); ?>">
            <input type="submit" value="Simpan">
        </form>
        <?php 
        }
        ?>
    </div>
</body>
</html>
