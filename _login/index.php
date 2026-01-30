<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
    session_start();
    require "connect.php";
    ?>
    <header>
    <h1>Homepage</h1>
</header>
    <nav class="btn-container">
        <p>
            <a href="login.php">Log in</a>
        </p>
        <p>
            <a href="register.php">Register</a>
        </p> 
    </nav>
</body>
</html>
