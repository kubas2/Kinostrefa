<?php

$conn = new mysqli('localhost','root', '','kino2');
if ($conn->connect_error) {
  die("Połączenie nieudane: " . $conn->connect_error);
}

?>