<?php
session_start();
header('Content-Type: application/json');

// Only allow logged-in admins
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["status" => "error", "message" => "Access denied"]);
    exit;
}

include 'db.php'; // $conn = new mysqli("localhost","root","","lgu3_platform");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Collect POST data
    $id       = intval($_POST['id'] ?? 0);
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $address  = trim($_POST['address'] ?? '');

    if ($id <= 0 || empty($fullname) || empty($email)) {
        echo json_encode(["status" => "error", "message" => "Invalid input"]);
        exit;
    }

    // ✅ Check if email is already used by another user
    $stmt = $conn->prepare("SELECT id FROM users WHERE email=? AND id<>?");
    $stmt->bind_param("si", $email, $id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email already in use by another user"]);
        exit;
    }

    // ✅ Update query
    $stmt = $conn->prepare("UPDATE users SET fullname=?, email=?, phone=?, address=? WHERE id=?");
    $stmt->bind_param("ssssi", $fullname, $email, $phone, $address, $id);
    $stmt->execute();

    echo json_encode(["status" => "success"]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}