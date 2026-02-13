<?php
session_start();
include("polaczenie.php");

if (!isset($_GET['id'])) {
    header("Location: admin.php");
    exit;
}

$reservationId = intval($_GET['id']);


$stmt = $conn->prepare("SELECT id FROM users WHERE username=?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$stmt->bind_result($userId);
$stmt->fetch();
$stmt->close();


$stmt = $conn->prepare("DELETE FROM rezerwacje WHERE id=? AND idUser=?");
$stmt->bind_param("ii", $reservationId, $userId);
$stmt->execute();
$stmt->close();

header("Location: admin.php");
exit;
