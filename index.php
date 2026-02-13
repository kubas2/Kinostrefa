<?php include 'header.php'; ?>
<?php include 'popular-section.php'; ?>

<div class="container">
    <div class="rating-section">
        <h2>Repertuar</h2>
        <div class="placeholder-box">
            <?php
            require('polaczenie.php');
            $sql = "SELECT f.id, f.tytul, f.rokWydania, f.rezyser, f.gatunek, f.opis, f.czas_trwania, p.sciezka AS `plakat`, 
                    ROUND((SELECT AVG(r.ocena) FROM recenzje AS r WHERE r.idFilmu = f.id), 1) AS `srednia_ocena`,
                    (SELECT GROUP_CONCAT(CONCAT(u.username, '*', r.ocena, '*', COALESCE(r.opis, '')) SEPARATOR '|')
                    FROM recenzje AS r INNER JOIN users AS u ON r.idUser = u.id WHERE r.idFilmu = f.id) AS `wszystkie_opinie`
                    FROM filmy AS f LEFT JOIN plakaty AS p ON f.id = p.idFilmu 
                    ORDER BY `srednia_ocena` DESC, f.tytul ASC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            ?>
            <table class="movies-table" id="movies-table">
                <thead>
                    <tr>
                        <th>Ocena</th>
                        <th>Plakat</th>
                        <th>Tytuł</th>
                        <th>Rok wydania</th>
                        <th>Reżyser</th>
                        <th>Gatunek</th>
                        <th>Opis</th>
                        <th>Czas trwania</th>
                        
                    </tr>
                </thead>
                <tbody>
            <?php
            while ($row = $result->fetch_assoc()) {
                // Przetwarzanie opinii tylko jeśli istnieją
                $rates = [];
                if (!empty($row['wszystkie_opinie'])) {
                    $rawRates = explode('|', $row['wszystkie_opinie']);
                    foreach ($rawRates as $rate) {
                        if (!empty($rate)) {
                            $parts = explode('*', $rate, 3); // username*ocena*opis
                            if (count($parts) === 3) {
                                $rates[] = [
                                    'username' => trim($parts[0]),
                                    'ocena' => $parts[1],
                                    'opis' => $parts[2]
                                ];
                            }
                        }
                    }
                }

                
                

                echo "<tr id='film-".htmlspecialchars(str_replace(' ', '', $row['tytul']))."' onclick=\"window.location='film.php?id=".$row['id']."'\" style='cursor:pointer;'>";
                echo "<td><h2>".($row['srednia_ocena'])."</h2></td>";
                echo "<td><img class='movie-poster' style='width: 150px; height: auto; border-radius: 8px;' src='img/". htmlspecialchars($row['plakat']). "'></td>"; 
                echo "<td>".htmlspecialchars($row['tytul'])."</td>";
                echo "<td>".htmlspecialchars($row['rokWydania'])."</td>";
                echo "<td>".htmlspecialchars($row['rezyser'])."</td>";
                echo "<td>".htmlspecialchars($row['gatunek'])."</td>";
                echo "<td>".htmlspecialchars($row['opis'])."</td>";
                echo "<td>".htmlspecialchars($row['czas_trwania'])."</td>";
                echo "</tr>";
                

                foreach ($rates as $rate) {
                    echo "<tr class='opinion-row'>";
                    echo "<td colspan='8' class='opinion-cell'>";
                    echo "<strong>".htmlspecialchars($rate['username'])."</strong> ";
                    echo "(" . htmlspecialchars($rate['ocena']) . "/10): ";
                    echo htmlspecialchars($rate['opis']);
                    echo "</td>";
                    echo "</tr>";
                }
            }
            ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</body>
</html>
