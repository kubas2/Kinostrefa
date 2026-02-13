<?php
session_start();
include("polaczenie.php");

// Sprawdzenie czy podano ID
if (!isset($_GET['id'])) {
    header("Location: admin.php");
    exit;
}

$userId = intval($_GET['id']);


$stmtUser = $conn->prepare("SELECT username FROM users WHERE id = ?");
$stmtUser->bind_param("i", $userId);
$stmtUser->execute();
$stmtUser->bind_result($selectedUsername);
$stmtUser->fetch();
$stmtUser->close();


if (!$selectedUsername) {
    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Seanse - <?php echo htmlspecialchars($selectedUsername); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include("headersimple.php"); ?>

<header>
    <h1>Seanse - <?php echo htmlspecialchars($selectedUsername); ?></h1>
    <a href="admin.php">
        <button class="button" style="margin-left:10px">⬅ Powrót</button>
    </a>
</header>

<div class="container">
<div class="rating-section">
<div class="placeholder-box">

<h2>Rezerwacje użytkownika</h2>

<table class="movies-table">
<thead>
<tr>
    <th>Plakat</th>
    <th>Tytuł</th>
    <th>Data i godzina</th>
    <th>Sala</th>
    <th>Siedzenia</th>
    <th>Status</th>
    <th>Akcje</th>
</tr>
</thead>
<tbody>

<?php
$stmt = $conn->prepare("
    SELECT r.id, s.data_start, f.tytul, p.sciezka, s.sala, r.status
    FROM rezerwacje r
    JOIN seanse s ON r.idSeansu = s.id
    JOIN filmy f ON s.idFilmu = f.id
    LEFT JOIN plakaty p ON f.id = p.idFilmu
    WHERE r.idUser = ?
    ORDER BY s.data_start DESC
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<tr><td colspan='6'>Brak rezerwacji</td></tr>";
}

while ($row = $result->fetch_assoc()) {

    
    $stmt2 = $conn->prepare("
        SELECT siedzenia.rzad, siedzenia.numer
        FROM rezerwacje
        JOIN siedzenia ON rezerwacje.idSiedzenia = siedzenia.id
        WHERE rezerwacje.id = ?
    ");
    $stmt2->bind_param("i", $row['id']);
    $stmt2->execute();
    $seatResult = $stmt2->get_result();

    $siedzenia = [];
    while ($seat = $seatResult->fetch_assoc()) {
        $siedzenia[] = "Rząd: {$seat['rzad']}, Miejsce: {$seat['numer']}";
    }
    $stmt2->close();

    $plakatPath = $row['sciezka']
        ? 'img/' . htmlspecialchars($row['sciezka'])
        : 'img/avatar.png';

    echo "<tr>";
    echo "<td><img src='{$plakatPath}' style='width:60px; border-radius:6px;'></td>";
    echo "<td>" . htmlspecialchars($row['tytul']) . "</td>";
    echo "<td>" . htmlspecialchars($row['data_start']) . "</td>";
    echo "<td>" . htmlspecialchars($row['sala']) . "</td>";
    echo "<td>" . implode("<br>", $siedzenia) . "</td>";
    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
    echo "<td>";
    if ($row['status'] != 'oplacona') {
        echo "<a href='adminPayReservation.php?id={$row['id']}'>
                <button class='button' style='background:green;'>OPŁAĆ</button>
            </a> ";
    }
        echo "<a href='adminCancelReservation.php?id={$row['id']}'
                onclick=\"return confirm('Na pewno anulować seans?')\">
                <button class='button' style='background:red;'>ANULUJ</button>
            </a>";

        echo "</td>";

        echo "</tr>";

        }

$stmt->close();
?>

</tbody>
</table>

</div>
</div>
</div>

</body>
</html>
