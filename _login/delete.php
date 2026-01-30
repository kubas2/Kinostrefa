<?php
include "connect.php";
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['loggedIn'])) {
    $stmt = $conn->prepare("DELETE FROM users WHERE username = ?");
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    session_destroy();
    header("Location: index.php");
}

?>