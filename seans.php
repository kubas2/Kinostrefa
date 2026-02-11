<?php
require_once 'polaczenie.php';

// Get session (seans) id from query string to show reserved seats for that session
$seansId = isset($_GET['seans']) && is_numeric($_GET['seans']) ? (int)$_GET['seans'] : null;

// Fetch all seats
$seatsSql = "SELECT id, rzad, numer FROM siedzenia ORDER BY rzad, numer";
$result = $conn->query($seatsSql);
if (!$result) {
    die('Błąd zapytania: ' . $conn->error);
}

$rows = [];
while ($row = $result->fetch_assoc()) {
    $r = (int)$row['rzad'];
    if (!isset($rows[$r])) $rows[$r] = [];
    $rows[$r][] = $row;
}

// If seans id provided, get reserved seats for that seans (excluding anulowana)
$reserved = [];
if ($seansId) {
    $stmt = $conn->prepare("SELECT idSiedzenia FROM rezerwacje WHERE idSeansu = ? AND status != 'anulowana'");
    $stmt->bind_param('i', $seansId);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($r = $res->fetch_assoc()) {
        $reserved[(int)$r['idSiedzenia']] = true;
    }
    $stmt->close();
}

?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="utf-8">
<title>Siatka siedzeń</title>
<link rel="stylesheet" href="style.css">
<style>
.container{max-width:900px;margin:24px auto;padding:12px}
.screen{background:#ccc;padding:8px;text-align:center;border-radius:4px;margin-bottom:12px}
.row{display:flex;justify-content:center;margin:6px 0}
.seat{width:38px;height:38px;margin:3px;border-radius:6px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-weight:600}
.seat.available{background:#e6ffe6;border:2px solid #2ecc71;color:#116b2c}
.seat.reserved{background:#ffe6e6;border:2px solid #e74c3c;color:#8b1f1f;cursor:not-allowed}
.legend{display:flex;gap:12px;align-items:center;margin-top:12px}
.legend .box{width:20px;height:20px;border-radius:4px;display:inline-block;margin-right:6px}
.info{margin-top:10px;color:#444}
.seat small{font-size:11px}
</style>
</head>
<body>
<div class="container">
    <h2>Siatka siedzeń</h2>
    <div class="screen">Ekran</div>

    <?php if (empty($rows)): ?>
        <p>Brak zdefiniowanych siedzeń w tabeli.</p>
    <?php else: ?>
        <?php ksort($rows); ?>
        <?php foreach ($rows as $rzad => $seats): ?>
            <div class="row" aria-label="Rząd <?php echo $rzad ?>">
                <?php foreach ($seats as $s):
                    $id = (int)$s['id'];
                    $isReserved = isset($reserved[$id]);
                ?>
                    <div class="seat <?php echo $isReserved ? 'reserved' : 'available' ?>" data-seat-id="<?php echo $id ?>" title="Rząd <?php echo $rzad ?>, Miejsce <?php echo $s['numer'] ?>">
                        <div>
                            <div><?php echo $s['numer'] ?></div>
                            <small>R<?php echo $rzad ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>

        <div class="legend">
            <div><span class="box" style="background:#e6ffe6;border:2px solid #2ecc71"></span> Dostępne</div>
            <div><span class="box" style="background:#ffe6e6;border:2px solid #e74c3c"></span> Zarezerwowane</div>
        </div>
        <p class="info">Podaj parametr <code>?seans=ID</code> w URL, aby zobaczyć które miejsca są już zarezerwowane dla danego seansu.</p>
    <?php endif; ?>
</div>
</body>
</html>