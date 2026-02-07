<?php
session_start();
header("Content-Type: application/json");
include 'db.php';

// Support both JSON and form POST
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (strpos($contentType, 'application/json') === 0) {
    $data = json_decode(file_get_contents("php://input"), true) ?? [];
    $email    = trim($data['email'] ?? '');
    $password = trim($data['password'] ?? '');
} else {
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
}

if ($email === '' || $password === '') {
    echo json_encode(["status" => "error", "message" => "Email and password are required"]);
    exit;
}

// Query the admins table
$stmt = $conn->prepare("SELECT id, fullname, email, password FROM admins WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password'])) {
        $_SESSION['admin_id']    = $row['id'];
        $_SESSION['admin_name']  = $row['fullname'];
        $_SESSION['admin_email'] = $row['email'];

        echo json_encode([
            "status"   => "success",
            "message"  => "Admin login successful",
            "redirect" => "admin-dashboard.php"
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid password"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Admin not found"]);
}

$stmt->close();
$conn->close();
?>