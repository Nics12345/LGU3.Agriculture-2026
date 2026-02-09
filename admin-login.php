<?php
session_start();
header("Content-Type: application/json");
include 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

// ✅ Handle OTP verification first
if (isset($_POST['otp'])) {
    $otp = trim($_POST['otp']);
    if (!isset($_SESSION['otp'], $_SESSION['pending_admin_id'])) {
        echo json_encode(["status" => "error", "message" => "No OTP session found"]);
        exit;
    }
    if (time() > $_SESSION['otp_expires']) {
        echo json_encode(["status" => "error", "message" => "OTP expired"]);
        unset($_SESSION['otp'], $_SESSION['pending_admin_id'], $_SESSION['pending_admin_name'], $_SESSION['pending_admin_email'], $_SESSION['otp_expires']);
        exit;
    }
    if ($otp == $_SESSION['otp']) {
        $_SESSION['admin_id']    = $_SESSION['pending_admin_id'];
        $_SESSION['admin_name']  = $_SESSION['pending_admin_name'];
        $_SESSION['admin_email'] = $_SESSION['pending_admin_email'];

        unset($_SESSION['otp'], $_SESSION['pending_admin_id'], $_SESSION['pending_admin_name'], $_SESSION['pending_admin_email'], $_SESSION['otp_expires']);

        echo json_encode(["status" => "success", "message" => "Admin login successful", "redirect" => "admin-dashboard.php"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid OTP"]);
    }
    exit;
}

// ✅ Handle email/password login
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
$stmt = $conn->prepare("SELECT id, fullname, email, password FROM admins WHERE email=? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password'])) {
        // ✅ Generate OTP
        $otp = rand(100000, 999999);
        $_SESSION['pending_admin_id']    = $row['id'];
        $_SESSION['pending_admin_name']  = $row['fullname'];
        $_SESSION['pending_admin_email'] = $row['email'];
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_expires'] = time() + 300; // 5 minutes expiry

        // ✅ Send OTP via Gmail SMTP
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'yourgmail@gmail.com';       // your Gmail
            $mail->Password = 'your-app-password';        // Gmail App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('yourgmail@gmail.com', 'LGU 3 System');
            $mail->addAddress($row['email'], $row['fullname']);

            $mail->isHTML(true);
            $mail->Subject = 'Your Admin Login OTP Code';
            $mail->Body    = "Hello " . htmlspecialchars($row['fullname']) . ",<br>Your OTP code is: <b>$otp</b><br>This code will expire in 5 minutes.";

            $mail->send();
            echo json_encode(["status" => "otp_required", "message" => "OTP sent to your email"]);
        } catch (Exception $e) {
            echo json_encode(["status" => "otp_required", "message" => "OTP generated but email failed: {$mail->ErrorInfo}"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid password"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Admin not found"]);
}

$stmt->close();
$conn->close();
?>