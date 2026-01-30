<?php
try{
    $conn = new mysqli('localhost', 'root','','loginapp');
    
} catch (Exception $e) {
    die("Connect error.");
}
?>