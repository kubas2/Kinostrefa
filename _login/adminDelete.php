<?php
session_start();
include("connect.php");
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['id'])){
        $stmt = $conn->prepare('DELETE FROM users WHERE id = ?');
        $stmt->bind_param("i", $_GET['id']);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            header("Location: admin.php");
        }
    }
}

?>