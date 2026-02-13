<?php

$servername = "localhost";
$username   = "lgu3_lgu3";
$password   = "lgu123";
$dbname     = "lgu3_lgu3";

// $servername = "localhost";
// $username   = "root";
// $password   = "";
// $dbname     = "lgu3_platform";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    header("Content-Type: application/json");
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// ✅ Set charset to utf8mb4 for emoji and special character support
$conn->set_charset("utf8mb4");
?>