<?php
session_start();
$host = "localhost";
$user = "u8gr0sjr9p4p4";
$pass = "9yxuqyo3mt85";
$dbname = "dbyowykdvtk52b";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>
