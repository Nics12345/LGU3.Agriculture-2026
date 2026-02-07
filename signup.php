<?php
error_reporting(E_ALL);

header("Content-Type: application/json");
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Support JSON body
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (strpos($contentType, 'application/json') === 0) {
        $data = json_decode(file_get_contents("php://input"), true) ?? [];
        $fullname = trim($data['fullname'] ?? '');
        $email    = trim($data['email'] ?? '');
        $password = trim($data['password'] ?? '');
        $phone    = trim($data['phone'] ?? '');
        $address  = trim($data['address'] ?? '');
    } else {
        $fullname = trim($_POST['fullname'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $phone    = trim($_POST['phone'] ?? '');
        $address  = trim($_POST['address'] ?? '');
    }

    if ($fullname === '' || $email === '' || $password === '' || $phone === '' || $address === '') {
        echo json_encode(["status" => "error", "message" => "All fields are required"]);
        exit;
    }

    $checkStmt = $conn->prepare("SELECT id, email, phone FROM users WHERE email = ? OR phone = ?");
    $checkStmt->bind_param("ss", $email, $phone);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $checkStmt->bind_result($id, $existingEmail, $existingPhone);
        $checkStmt->fetch();

        if ($existingEmail === $email) {
            echo json_encode(["status" => "error", "message" => "Email already exists"]);
        } elseif ($existingPhone === $phone) {
            echo json_encode(["status" => "error", "message" => "Phone number already exists"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Duplicate entry"]);
        }
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (fullname, email, password, phone, address) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sssss", $fullname, $email, $hashedPassword, $phone, $address);
            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "User registered successfully"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
        }
    }
    $checkStmt->close();
    $conn->close();
}
?>