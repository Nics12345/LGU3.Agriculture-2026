<?php
error_reporting(E_ALL);

header("Content-Type: application/json");
include 'db.php';

if (!$conn) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit;
}

$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');

if ($email === '' && $phone === '') {
    echo json_encode(["status" => "error", "message" => "Email or phone required"]);
    exit;
}

$checkStmt = $conn->prepare("SELECT email, phone FROM users WHERE email = ? OR phone = ?");
$checkStmt->bind_param("ss", $email, $phone);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($row = $result->fetch_assoc()) {
    if ($row['email'] === $email) {
        echo json_encode(["status" => "error", "message" => "Email already exists"]);
    } elseif ($row['phone'] === $phone) {
        echo json_encode(["status" => "error", "message" => "Phone number already exists"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Duplicate entry"]);
    }
} else {
    echo json_encode(["status" => "success", "message" => "No duplicates"]);
}

$checkStmt->close();
$conn->close();
?>