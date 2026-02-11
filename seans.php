<?php
include 'header.php'; 
session_start();
require_once 'polaczenie.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['selected_seats']) && isset($_POST['total_price']) && isset($_POST['idSeansu'])) {
        $selectedSeats = $_POST['selected_seats'];
        $totalPrice = $_POST['total_price'];
        $idSeansu = $_POST['idSeansu'];
 
        $selectedSeatsArray = explode(',', $selectedSeats);
        foreach ($selectedSeatsArray as $seatId) {
            if ($_SESSION['loggedIn'] === true && isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
                $stmt->bind_param('s', $username);
                $stmt->execute();
                $stmt->bind_result($userId);
                if ($stmt->fetch()) {
                    $stmt->close();
                    $stmt = $conn->prepare("INSERT INTO rezerwacje(idSeansu, idSiedzenia, status, idUser) VALUES (?, ?, 'zarezerwowana', ?)");
                    $stmt->bind_param('iii', $idSeansu, $seatId, $userId);
                    try {
                        $stmt->execute();
                    } catch (Exception $e) {
                        header('Location: seans.php?id=' . urlencode($idSeansu) . '&error=' . urlencode('Nie można zarezerwować miejsca. Spróbuj ponownie.'));
                        exit;
                    }
                }
            }
        }
    }
}

$seansId = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;

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

$reserved = [];

if ($seansId) {
    $stmt = $conn->prepare("SELECT idSiedzenia, status FROM rezerwacje WHERE idSeansu = ?");
    $stmt->bind_param('i', $seansId);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res === false) {
        echo "<pre>Błąd pobierania rezerwacji: " . $conn->error . "</pre>";
    } else {

        while ($r = $res->fetch_assoc()) {
       
            if ($r['status'] == 'zarezerwowana' || $r['status'] == 'oplacona') {
                $reserved[(int)$r['idSiedzenia']] = true;
               
            }
        }
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
.seat{width:38px;height:38px;margin:3px;border-radius:6px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-weight:600;transition:background 0.2s,border 0.2s}
.seat.available{background:#e6ffe6;border:2px solid #2ecc71;color:#116b2c}
.seat.reserved{background:#ffe6e6;border:2px solid #e74c3c;color:#8b1f1f;cursor:not-allowed}
.seat.selected{background:#cce0ff;border:2px solid #2980f3;color:#174a7c}
.legend{display:flex;gap:18px;align-items:center;margin-top:12px}
.legend .box{width:20px;height:20px;border-radius:4px;display:inline-block;margin-right:6px}
.info{margin-top:10px;color:#444}
.seat small{font-size:11px}
.price{margin-top:18px;font-size:18px;font-weight:600;color:#f5c518}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const seats = document.querySelectorAll('.seat.available');
    const selectedInfo = document.getElementById('selected-info');
    const form = document.getElementById('reserve-form');
    const selectedInput = document.getElementById('selected-seats');
    const totalInput = document.getElementById('total-price');
    const pricePerSeat = 29.99;
    let selectedCount = 0;

    function updateInfo() {
        const selectedSeats = Array.from(document.querySelectorAll('.seat.selected')).map(s => s.dataset.seatId);
        selectedCount = selectedSeats.length;
        const total = (selectedCount * pricePerSeat).toFixed(2);
        selectedInfo.innerHTML = `Ilość wybranych miejsc: <b>${selectedCount}</b> | Cena całkowita: <b>${total} zł</b>`;
        selectedInput.value = selectedSeats.join(',');
        totalInput.value = total;
        document.getElementById('reserve-btn').disabled = selectedCount === 0;
    }

    seats.forEach(seat => {
        seat.addEventListener('click', function() {
            if (this.classList.contains('reserved')) return;
            this.classList.toggle('selected');
            updateInfo();
        });
    });
    updateInfo();
});
</script>
</head>
<?php
    if (isset($_GET['error'])) {
        echo "<script>alert('" . htmlspecialchars($_GET['error']) . "');</script>";
    }
?>
<body>
<div class="container">
    <form id="reserve-form" method="POST" action="">
        <input type="hidden" name="idSeansu" value="<?php echo htmlspecialchars($seansId); ?>">
        <div id="selected-info" style="margin-bottom:18px;font-size:18px;"></div>
        <input type="hidden" name="selected_seats" id="selected-seats" value="">
        <input type="hidden" name="total_price" id="total-price" value="">
        <button type="submit" class="button" id="reserve-btn" disabled>Zarezerwuj</button>
    </form>
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
            <div><span class="box" style="background:#ffe6e6;border:2px solid #e74c3c"></span> Zajęte</div>
            <div><span class="box" style="background:#cce0ff;border:2px solid #2980f3"></span> Wybrane</div>
        </div>
        <div class="price">Cena biletu: 29,99 zł</div>
       
    <?php endif; ?>
</div>
</body>
</html>