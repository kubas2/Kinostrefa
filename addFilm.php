<?php
session_start();

include("polaczenie.php");

// Check if user is logged in and is admin
if (!isset($_SESSION['username']) || $_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit;
}

// Check if user is admin
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

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tytul = $_POST['tytul'] ?? null;
    $rokWydania = $_POST['rokWydania'] ?? null;
    $rezyser = $_POST['rezyser'] ?? null;
    $gatunek = $_POST['gatunek'] ?? null;
    $opis = $_POST['opis'] ?? null;
    $czas_trwania = $_POST['czas_trwania'] ?? null;
    $plakat = $_POST['plakat'] ?? null;

    // Validate input
    if (!$tytul || !$rokWydania || !$rezyser || !$gatunek || !$opis || !$czas_trwania || !$plakat) {
        $_SESSION['error'] = "Wszystkie pola są wymagane";
        header("Location: admin.php");
        exit;
    }

    // Insert film into database
    $stmt = $conn->prepare("INSERT INTO filmy (tytul, rokWydania, rezyser, gatunek, opis, czas_trwania) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('sisssi', $tytul, $rokWydania, $rezyser, $gatunek, $opis, $czas_trwania);

    if ($stmt->execute()) {
        // Get the last inserted film ID
        $filmId = $stmt->insert_id;
        $stmt->close();

        // Insert poster into plakaty table
        $stmtPoster = $conn->prepare("INSERT INTO plakaty (idFilmu, sciezka) VALUES (?, ?)");
        $stmtPoster->bind_param('is', $filmId, $plakat);

        if ($stmtPoster->execute()) {
            $_SESSION['success'] = "Film został dodany pomyślnie";
            header("Location: admin.php");
        } else {
            $_SESSION['error'] = "Błąd podczas dodawania plakatu: " . $stmtPoster->error;
            header("Location: admin.php");
        }
        $stmtPoster->close();
    } else {
        $_SESSION['error'] = "Błąd podczas dodawania filmu: " . $stmt->error;
        header("Location: admin.php");
        $stmt->close();
    }
} else {
    header("Location: admin.php");
}
?>
