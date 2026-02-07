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
    $id = intval($_POST['id'] ?? 0);
    if ($id <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid user ID"]);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    echo json_encode(["status" => "success"]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}