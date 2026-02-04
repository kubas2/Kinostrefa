<?php 
    session_start();
    if (isset($_SESSION['username']) && isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true) {
        header("Location: admin.php");
    }
?>

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
    require "polaczenie.php";
    include 'header.php';
    ?>

    <nav class="btn-container">
        <p>
            <a class='button' href="login.php">Zaloguj się</a>
        </p>
        <p>
            <a class='button' href="register.php">Zarejestruj się</a>
        </p> 
    </nav>
</body>
</html>
