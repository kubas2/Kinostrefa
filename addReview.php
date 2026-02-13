<?php

require_once 'polaczenie.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();

}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $filmId = $_POST['film'] ?? '';
    $rating = $_POST['rating'] ?? '';
    $comment = $_POST['comment'] ?? '';

    // Get user ID from username
    $stmt = $conn->prepare('SELECT id FROM users WHERE username = ?');
    $stmt->bind_param('s', $_SESSION['username']);
    $stmt->execute();
    $stmt->bind_result($userId);
    $stmt->fetch();
    $stmt->close();

    if (!$filmId || !$userId) {
        $_SESSION['error'] = "Błąd: Brak wybranego filmu lub błąd użytkownika.";
        header("Location: admin.php");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO recenzje (idFilmu, idUser, ocena, opis) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('iiis', $filmId, $userId, $rating, $comment);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Recenzja dodana pomyślnie!";
        header("Location: admin.php");
        exit();
    } else {
        $_SESSION['error'] = "Błąd podczas dodawania recenzji: " . $stmt->error;
        header("Location: admin.php");
        exit();
    }

}
?>