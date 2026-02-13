<?php
session_start();
include("polaczenie.php");

if (!isset($_GET['id'])) {
    header("Location: admin.php");
    exit;
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("UPDATE rezerwacje SET status='oplacona' WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
