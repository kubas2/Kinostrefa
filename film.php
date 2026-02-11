<?php
require 'polaczenie.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Brak ID filmu");
}

$id = (int)$_GET['id'];

$sql = "
SELECT 
    f.id,
    f.tytul,
    f.rokWydania,
    f.rezyser,
    f.gatunek,
    f.opis,
    f.czas_trwania,
    p.sciezka AS plakat
FROM filmy f
LEFT JOIN plakaty p ON p.idFilmu = f.id
WHERE f.id = $id
";

$result = $conn->query($sql);

if ($result->num_rows === 0) {
    die("Film nie istnieje");
}

$film = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($film['tytul']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'headersimple.php'; ?>

<div class="movie-page">

    <div class="movie-left">
        <img 
            src="img/<?= htmlspecialchars($film['plakat']) ?>" 
            class="movie-big-poster"
            alt="<?= htmlspecialchars($film['tytul']) ?>"
        >
    </div>

    <div class="movie-right">
        <h1><?= htmlspecialchars($film['tytul']) ?></h1>

        <p class="movie-desc">
            <?= htmlspecialchars($film['opis']) ?>
        </p>

        <div class="movie-meta">
            <p><strong>Rok:</strong> <?= $film['rokWydania'] ?></p>
            <p><strong>Reżyser:</strong> <?= htmlspecialchars($film['rezyser']) ?></p>
            <p><strong>Gatunek:</strong> <?= htmlspecialchars($film['gatunek']) ?></p>
            <p><strong>Czas trwania:</strong> <?= $film['czas_trwania'] ?> min</p>
        </div>

        <?php
        $stmt = $conn->prepare("SELECT id, data_start, sala FROM seanse WHERE idFilmu = ? ORDER BY data_start ASC");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $seanse = $stmt->get_result();
        if ($seanse->num_rows > 0) {
echo "<h2>Nadchodzące seanse:</h2>";
echo "<div class='seanse-container'>";

while ($seans = $seanse->fetch_assoc()) {
    echo "
    <div class='seans-box'>
        <div class='seans-info'>
            <p class='seans-date'>" . htmlspecialchars($seans['data_start']) . "</p>
            <p class='seans-sala'>Sala: " . htmlspecialchars($seans['sala']) . "</p>
        </div>
        <div class='seans-action'>
            <button class='button-small' onclick=\"window.location.href='seans.php?id=" . $seans['id'] . "'\">
                Wybierz seans
            </button>
        </div>
    </div>
    ";
}

echo "</div>";
        } else {
            echo "<p>Brak nadchodzących seansów.</p>";
        } ?>

        <a href="index.php" class="btn">← Powrót do listy filmów</a>
    </div>

</div>

</body>
</html>
