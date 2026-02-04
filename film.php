<?php
require 'polaczenie.php';

/* ===== SPRAWDZENIE ID ===== */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Brak ID filmu");
}

$id = (int)$_GET['id'];

/* ===== POBRANIE FILMU ===== */
$sqlFilm = "SELECT * FROM filmy WHERE id = $id";
$resultFilm = $conn->query($sqlFilm);

if ($resultFilm->num_rows === 0) {
    die("Film nie istnieje");
}

$film = $resultFilm->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($film['tytul']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="movie-page">

    <div class="movie-left">
        <img src="<?= htmlspecialchars($film['plakat']) ?>" class="movie-big-poster">
    </div>

    <div class="movie-right">
        <h1><?= htmlspecialchars($film['tytul']) ?></h1>

        <p class="movie-desc">
            <?= nl2br(htmlspecialchars($film['opis'])) ?>
        </p>

        <div class="movie-meta">
            <p><strong>Rok:</strong> <?= (int)$film['rokWydania'] ?></p>
            <p><strong>Reżyser:</strong> <?= htmlspecialchars($film['rezyser']) ?></p>
            <p><strong>Gatunek:</strong> <?= htmlspecialchars($film['gatunek']) ?></p>
            <p><strong>Czas trwania:</strong> <?= (int)$film['czas_trwania'] ?> min</p>
        </div>

        <a href="index.php" class="btn">← Powrót do listy filmów</a>
    </div>

</div>

</body>
</html>
