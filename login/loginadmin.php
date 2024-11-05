<?php
session_start(); // Mulai session
include 'db.php';

$error_message = '';

// Cek apakah sudah login
if (isset($_SESSION['user_id'])) {
    header('Location: ../home.php'); // Arahkan ke home jika sudah login
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $pw = $_POST['pw'];

    // Prepare statement to prevent SQL Injection
    $stmt = $conn->prepare("SELECT id, pw FROM admins WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($pw, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            header('Location: ../home.php');
            exit();
        } else {
            $error_message = "Password salah.";
        }
    } else {
        $error_message = "Email tidak ditemukan.";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Akun</title>
    <link rel="icon" href="../ngawilogo.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
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
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        h2 {
            color: #333;
            font-weight: 500;
            margin-bottom: 25px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 8px;
            font-size: 14px;
            color: #666;
        }
        input[type="email"],
        input[type="password"] {
            margin-bottom: 20px;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #007BFF;
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
        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-bottom: 20px;
        }
        p {
            margin-top: 15px;
            font-size: 14px;
        }
        a {
            color: #007BFF;
            text-decoration: none;
            font-weight: 500;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login Account</h2>
        <?php if ($error_message): ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <label for="email"></label>
            <input type="email" id="email" name="email" required placeholder="Input Email">
            
            <label for="pw"></label>
            <input type="password" id="pw" name="pw" required placeholder="Input Password">
            
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
