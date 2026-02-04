<?php
session_start();

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
        <h1>Admin Panel</h1>
        <div>Logged in as: <?php echo htmlspecialchars($currentUser); ?></div><a href="logout.php"><button class="btn btn-delete" style="margin-left:10px">Log-out</button></a></div>
            <a href="delete.php"><button class="btn btn-delete" style="margin-left:10px">Delete account</button></a>
    </header>
    <div class='container'>
    <div class="rating-section">
    <div class="placeholder-box">
    
    <?php
    if ($isAdmin) { 
        echo"<h2>Użytkownicy</h2>";
        echo'<table class="movies-table">';

        $stmt = $conn->prepare("SELECT id, username, email from users");
        $stmt->execute();
        $r = $stmt->get_result();
           
        echo'<thead>
            <th>ID</th><th>NAZWA</th><th>EMAIL</th><th>USUŃ</th>
        </thead><tbody>';
        while ($row = $r->fetch_assoc()) {

            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['username']}</td>
                    <td>{$row['email']}</td>";

            if ($row['username'] != $_SESSION['username']) {
                echo "<td><a href='adminDelete.php?id={$row['id']}'><button class='button' id='{$row['id']}'>USUŃ</button></a></td></tr>";
            }
        }
    } else {
        echo "<h2>Moje seanse</h2>";
         /*$stmt = $conn->prepare("SELECT id, title, date FROM sessions WHERE username=?");
         $stmt->bind_param('s', $_SESSION['username']);
         $stmt->execute();
         $r = $stmt->get_result();*/
    }
                    
?></div>

    </tbody>
    </table>
    </div>
    </div>
    </div>
</body>
</html>
