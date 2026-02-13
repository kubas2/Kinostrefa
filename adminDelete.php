<?php
session_start();
include("polaczenie.php");

if (!isset($_GET['id'])) {
    header("Location: admin.php");
    exit;
}

$id = intval($_GET['id']);


$stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();

if ($username === $_SESSION['username']) {
    header("Location: admin.php");
    exit;
}


$stmt = $conn->prepare("DELETE FROM rezerwacje WHERE idUser = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();


$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header("Location: admin.php");
exit;
