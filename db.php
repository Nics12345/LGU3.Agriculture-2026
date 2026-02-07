<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "lgu3_platform";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    header("Content-Type: application/json");
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]);
    exit;
}
?>