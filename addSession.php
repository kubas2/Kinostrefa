<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include("polaczenie.php");


if (!isset($_SESSION['username']) || $_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit;
}


$stmt = $conn->prepare('SELECT isadmin FROM users WHERE username=?');
$stmt->bind_param('s', $_SESSION['username']);
$stmt->execute();
$stmt->bind_result($isAdmin);
$stmt->fetch();
$stmt->close();

if (!$isAdmin) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $movie = $_POST['movie'] ?? null;
    $date = $_POST['date'] ?? null;
    $sala = $_POST['sala'] ?? null;


    if (!$movie || !$date || !$sala) {
        $_SESSION['error'] = "Wszystkie pola są wymagane";
        header("Location: admin.php");
        exit;
    }


    $stmt = $conn->prepare("INSERT INTO seanse (idFilmu, data_start, sala) VALUES (?, ?, ?)");
    $stmt->bind_param('isi', $movie, $date, $sala);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Seans został dodany pomyślnie";
        header("Location: admin.php");
    } else {
        $_SESSION['error'] = "Błąd podczas dodawania seansu: " . $stmt->error;
        header("Location: admin.php");
    }
    $stmt->close();
} else {
    header("Location: admin.php");
}
?>