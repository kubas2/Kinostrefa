<?php
require('polaczenie.php');
include 'header.php';
include 'popular-section.php';
?>

<div class="container">
    <div class="rating-section">
        <h2>Dodaj recenzję</h2>

        <div class="placeholder-box">
            <form method="POST">
                <label for="name">Imię:</label>
                <input type="text" id="name" name="name" required>

                <label for="lastname">Nazwisko:</label>
                <input type="text" id="lastname" name="lastname" required>

                <label for="movie">Wybierz film:</label>
                <select id="movie" name="movie" required>
                    <?php
                    $stmt = $conn->prepare("SELECT id, tytul FROM filmy");
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()) {
                        echo "<option value=\"" . htmlspecialchars($row['id']) . "\">"
                           . htmlspecialchars($row['tytul']) . "</option>";
                    }
                    ?>
                </select>

                <label for="rating">Wybierz ocenę:</label>
                <select id="rating" name="rating" required>
                    <?php
                    for ($i = 1; $i <= 10; $i++) {
                        echo "<option value=\"$i\">$i</option>";
                    }
                    ?>
                </select>

                <label for="opinia">Twoja opinia:</label>
                <textarea id="opinia" name="opinia" rows="4" placeholder="Napisz swoją opinię o filmie..."></textarea>

                <input type="submit" value="Dodaj recenzję">
            </form>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $imie = trim($_POST['name']);
                $nazwisko = trim($_POST['lastname']);
                $film_id = (int)$_POST['movie'];
                $ocena = (int)$_POST['rating'];
                $opinia = trim($_POST['opinia']);

                if ($imie && $nazwisko && $film_id && $ocena && $opinia) {
                    // Sprawdź czy użytkownik istnieje
                    $stmt = $conn->prepare("SELECT id FROM users WHERE imie = ? AND nazwisko = ?");
                    $stmt->bind_param("ss", $imie, $nazwisko);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($row = $result->fetch_assoc()) {
                        $uzytkownik_id = $row['id'];
                    } else {
                        // Utwórz nowego użytkownika
                        $stmt = $conn->prepare("INSERT INTO users (imie, nazwisko) VALUES (?, ?)");
                        $stmt->bind_param("ss", $imie, $nazwisko);
                        $stmt->execute();
                        $uzytkownik_id = $stmt->insert_id;
                    }

                    
                    $stmt = $conn->prepare("INSERT INTO recenzje (idUser, idFilmu, ocena, opis) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("iiis", $uzytkownik_id, $film_id, $ocena, $opinia);
                    $stmt->execute();

                    echo "<p style='color:lime;'>Recenzja została dodana!</p>";
                } else {
                    echo "<p style='color:red;'>Uzupełnij wszystkie pola!</p>";
                }
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>
