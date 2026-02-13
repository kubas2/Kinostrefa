<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("polaczenie.php");
// symulacja logowania, jeśli nie ma sesji
if (!isset($_SESSION['username']) || $_SESSION['loggedIn'] !== true) {
    $_SESSION['username'] = 'AdminUser'; // tymczasowy użytkownik
    header("Location: index.php"); exit;
}
$stmt = $conn->prepare('SELECT isadmin From users Where username=?');
$stmt->bind_param('s', $_SESSION['username']);
$stmt->execute();
$stmt->bind_result($isAdmin);
$stmt->fetch();
$stmt->close();


$currentUser = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Admin Panel</title>
</head>
<body>
    <?php include("headersimple.php"); ?>
    <header>
        <?php
        if ($isAdmin) { 
        echo"<h1>Admin Panel</h1>";
        } else {
            echo "<h1>Moje konto</h1>"; }?>
        <div>Zalogowano jako: <?php echo htmlspecialchars($currentUser); ?></div><a href="logout.php"><button class='button' style="margin-left:10px">Wyloguj się</button></a></div>
            <a href="delete.php"><button class='button' style="margin-left:10px">Usuń konto</button></a>
    </header>
    <div class='container'>
    <div class="rating-section">
    <div class="placeholder-box">
    
    <?php
    if ($isAdmin) { // Jeśli użytkownik jest adminem
        if (isset($_SESSION['error'])) {
            echo "<div class='error-message'>" . htmlspecialchars($_SESSION['error']) . "</div>";
            unset($_SESSION['error']);
        } elseif (isset($_SESSION['success'])) {
            echo "<div class='success-message'>" . htmlspecialchars($_SESSION['success']) . "</div>";
            unset($_SESSION['success']);
        }
        echo "<h2>Dodaj seans</h2>";
        echo '<form method="POST" action="addSession.php">
                <label for="movie">Wybierz film:</label>
                <select id="movie" name="movie" required>';
        $stmt = $conn->prepare("SELECT id, tytul FROM filmy");
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            echo "<option value=\"" . htmlspecialchars($row['id']) . "\">"
               . htmlspecialchars($row['tytul']) . "</option>";
        }
        echo '</select>

                <label for="date">Data i godzina seansu:</label>
                <input type="datetime-local" id="date" name="date" required>

                <label for="sala">Numer sali:</label>
                <input type="number" min="1" max="5" id="sala" name="sala" required>

                <input type="submit" value="Dodaj seans">
              </form>';

        echo "</div></div></div><div class='container'><div class='rating-section'><div class='placeholder-box'>";
        echo "<h2>Dodaj film</h2>";
        echo '<form method="POST" action="addFilm.php">
                <label for="tytul">Tytuł:</label>
                <input type="text" id="tytul" name="tytul" required>

                <label for="rokWydania">Rok wydania:</label>
                <input type="number" id="rokWydania" name="rokWydania" min="1900" max="2100" required>

                <label for="rezyser">Reżyser:</label>
                <input type="text" id="rezyser" name="rezyser" required>

                <label for="gatunek">Gatunek:</label>
                <input type="text" id="gatunek" name="gatunek" required>

                <label for="opis">Opis:</label>
                <textarea id="opis" name="opis" rows="4" required></textarea>

                <label for="czas_trwania">Czas trwania (minuty):</label>
                <input type="number" id="czas_trwania" name="czas_trwania" min="1" required>

                <label for="plakat">Plakat (nazwa pliku np. matrix.png):</label>
                <input type="text" id="plakat" name="plakat" required>

                <input type="submit" value="Dodaj film">
              </form>';

        echo "</div></div></div><div class='container'><div class='rating-section'><div class='placeholder-box'>";
        echo"<h2>Użytkownicy</h2>";
        echo'<table class="movies-table">';

        $stmt = $conn->prepare("SELECT id, username, email from users");
        $stmt->execute();
        $r = $stmt->get_result();
           
        echo'<thead>
            <th>ID</th>
            <th>NAZWA</th>
            <th>EMAIL</th>
            <th>USUŃ</th>
            <th>SZCZEGÓŁY</th>            
        </thead><tbody>';
        while ($row = $r->fetch_assoc()) {

    echo "<tr>";
    echo "<td>{$row['id']}</td>";
    echo "<td>{$row['username']}</td>";
    echo "<td>{$row['email']}</td>";

    
    
    if ($row['username'] != $_SESSION['username']) {

        echo "<td>
                <a href='adminDelete.php?id={$row['id']}'
                onclick=\"return confirm('Na pewno usunąć użytkownika?')\">
                    <button class='button'>USUŃ</button>
                </a>
            </td>";

    } else {

        echo "<td>-</td>";
    }
    echo "<td>
            <a href='adminUserDetails.php?id={$row['id']}'>
                <button class='button' style='background:#444;'>SZCZEGÓŁY</button>
            </a>
        </td>";

        echo "</tr>";
    }


        
    } else { // nie jest adminem, pokazujemy seanse
        echo "<h2>Dodaj opinię</h2>";
        
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param('s', $_SESSION['username']);
        $stmt->execute();
        $stmt->bind_result($userId);
        $stmt->fetch();
        $stmt->close();
        $_SESSION['userId'] = $userId;
        
        $stmt = $conn->prepare("
            SELECT DISTINCT f.id, f.tytul
            FROM filmy f
            JOIN seanse s ON f.id = s.idFilmu
            JOIN rezerwacje r ON s.id = r.idSeansu
            WHERE r.idUser = ? AND r.status = 'oplacona'
            ORDER BY f.tytul
        ");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            echo "<p style='color: #999;'>Brak filmów do recenzji. Musisz mieć opłaconą rezerwację, aby dodać opinię.</p>";
        } else {
            echo '<form method="POST" action="addReview.php">
                    <label for="film">Wybierz film:</label>
                    <select id="film" name="film" required>';
            while ($row = $result->fetch_assoc()) {
                echo "<option value=\"" . htmlspecialchars($row['id']) . "\">"
                   . htmlspecialchars($row['tytul']) . "</option>";
            }
            echo '</select>

                    <label for="rating">Ocena (1-10):</label>
                    <input type="number" id="rating" name="rating" min="1" max="10" required>

                    <label for="comment">Komentarz:</label>
                    <textarea id="comment" name="comment" rows="4"></textarea>

                    <input type="submit" value="Dodaj opinię">
                  </form>';
        }
              
        echo "</div></div></div><div class='container'><div class='rating-section'><div class='placeholder-box'>";
        echo "<h2>Moje seanse</h2>";

        
        $stmt = $conn->prepare("
            SELECT r.id, s.data_start, f.tytul, f.id as film_id, p.sciezka as plakat, s.sala, s.cena, s.id as seans_id
            FROM rezerwacje r
            JOIN seanse s ON r.idSeansu = s.id
            JOIN filmy f ON s.idFilmu = f.id
            LEFT JOIN plakaty p ON f.id = p.idFilmu
            WHERE r.idUser = ?
            GROUP BY r.id, s.data_start, f.tytul, f.id, p.sciezka, s.sala, s.cena, s.id
            ORDER BY s.data_start DESC
        ");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            echo "<p>Brak rezerwacji.</p>";
        } else {
            echo "<table class='movies-table'><thead><tr><th>Plakat</th><th>Tytuł</th><th>Data i godzina</th><th>Sala</th><th>Siedzenia</th><th>Status</th><th>Akcje</th></tr></thead><tbody>";
            while ($row = $result->fetch_assoc()) {
                
                $stmt2 = $conn->prepare("
                    SELECT siedzenia.rzad, siedzenia.numer
                    FROM rezerwacje
                    JOIN siedzenia ON rezerwacje.idSiedzenia = siedzenia.id
                    WHERE rezerwacje.id = ?
                ");
                $stmt2->bind_param('i', $row['id']);
                $stmt2->execute();
                $siedzeniaResult = $stmt2->get_result();
                $siedzeniaArr = [];
                while ($siedzenie = $siedzeniaResult->fetch_assoc()) {
                    $siedzeniaArr[] = "Rząd: " . $siedzenie['rzad'] . ", Miejsce: " . $siedzenie['numer'];
                }
                $stmt2->close();
                $siedzeniaStr = implode('<br>', $siedzeniaArr);
                $plakatPath = $row['plakat'] ? 'img/' . htmlspecialchars($row['plakat']) : 'img/avatar.png';
                echo "<tr>";
                echo "<td><img src='" . $plakatPath . "' alt='plakat' style='width:60px; border-radius:6px;'></td>";
                echo "<td>" . htmlspecialchars($row['tytul']) . "</td>";
                echo "<td>" . htmlspecialchars($row['data_start']) . "</td>";
                echo "<td>" . htmlspecialchars($row['sala']) . "</td>";
                echo "<td>" . $siedzeniaStr . "</td>";
                
                $stmt3 = $conn->prepare("SELECT status FROM rezerwacje WHERE id = ?");
                $stmt3->bind_param('i', $row['id']);
                $stmt3->execute();
                $stmt3->bind_result($status);
                $stmt3->fetch();
                $stmt3->close();
                echo "<td>" . htmlspecialchars($status) . "</td>";
                echo "<td>
                        <a href='cancelReservation.php?id={$row['id']}'
                        onclick=\"return confirm('Na pewno anulować seans?')\">
                            <button class='button' style='background:red;'>ANULUJ</button>
                        </a>
                    </td>";

                echo "</tr>";
            }
            echo "</tbody></table>";
        }
    }
                    
?></div>

    </tbody>
    </table>
    </div>
    </div>
    </div>
</body>
</html>
