<?php

//$conn = new mysqli('localhost','root', '','kino2');
$conn = new mysqli('localhost','root', '','sendziak');
if ($conn->connect_error) {
  die("Połączenie nieudane: " . $conn->connect_error);
}

?>