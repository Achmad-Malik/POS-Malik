<?php
// index.php

// Optionally, you can include some HTML content or logic before redirecting
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
</head>
<body>
    <p>Redirecting to the login page...</p>
    <p>If you are not redirected automatically, <a href="loginadmin.php">click here</a>.</p>

    <?php
    // Redirect to loginadmin.php
    header("Location: login/loginadmin.php");
    exit(); // Ensure no further code is executed after redirection
    ?>
</body>
</html>
