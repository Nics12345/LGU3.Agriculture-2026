<?php
session_start();
header("Content-Type: application/json");
include 'db.php';

$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (strpos($contentType, 'application/json') === 0) {
    $data = json_decode(file_get_contents("php://input"), true) ?? [];
    $email = trim($data['email'] ?? '');
    $password = trim($data['password'] ?? '');
} else {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
}

if (empty($email) || empty($password)) {
    echo json_encode([
        "status" => "error",
        "message" => "Email and password are required",
        "forgot_link" => "forgot-password.php"
    ]);
    exit;
}

$stmt = $conn->prepare("SELECT id, fullname, email, password, phone FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password'])) {
        $_SESSION['user_id']   = $row['id'];
        $_SESSION['fullname']  = $row['fullname'];
        $_SESSION['email']     = $row['email'];

        echo json_encode([
            "status"   => "success",
            "message"  => "Login successful",
            "user"     => $row['fullname'],
            "redirect" => "dashboard.php"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid password",
            "forgot_link" => "forgot-password.php"
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "User not found",
        "forgot_link" => "forgot-password.php"
    ]);
}

$stmt->close();
$conn->close();
?>