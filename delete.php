<?php
require_once 'polaczenie.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['name'])) {
    if ($_GET['name'] === $_SESSION['username']) {
     
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param('s', $_GET['name']);
        $stmt->execute();
        $stmt->bind_result($userId);
        $stmt->fetch();
        $stmt->close();
        
      
        $stmt = $conn->prepare("DELETE FROM rezerwacje WHERE idUser = ?");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->close();
        

        $stmt = $conn->prepare("DELETE FROM recenzje WHERE idUser = ?");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->close();
   
        $stmt = $conn->prepare("DELETE FROM users WHERE username = ?");
        $stmt->bind_param('s', $_GET['name']);
        $stmt->execute();
        $stmt->close();
        session_destroy();
        header("Location: index.php");
        exit();
    } else {
        header("Location: admin.php");
        exit();
    }
} 
?>
<html>
<head>
    <title>Usuwanie konta</title>
    <link rel="stylesheet" href="style.css">

<script>
    function confirmDelete() {
        if (confirm("Czy na pewno chcesz usunąć konto?")) {
           
            window.location.href = "delete.php?name=<?= htmlspecialchars($_SESSION['username']) ?>";  
        } else {
            window.location.href = "admin.php";
        }
    }

    confirmDelete();


</script>
</head>