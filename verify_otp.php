<?php
session_start();
header("Content-Type: application/json");

$otp = trim($_POST['otp'] ?? '');

if (!isset($_SESSION['otp'], $_SESSION['pending_user_id'])) {
    echo json_encode(["status" => "error", "message" => "No OTP session found"]);
    exit;
}

if (time() > $_SESSION['otp_expires']) {
    echo json_encode(["status" => "error", "message" => "OTP expired"]);
    unset($_SESSION['otp'], $_SESSION['pending_user_id'], $_SESSION['pending_fullname'], $_SESSION['pending_email'], $_SESSION['otp_expires']);
    exit;
}

if ($otp == $_SESSION['otp']) {
    $_SESSION['user_id'] = $_SESSION['pending_user_id'];
    $_SESSION['fullname'] = $_SESSION['pending_fullname'];
    $_SESSION['email'] = $_SESSION['pending_email'];

    unset($_SESSION['otp'], $_SESSION['pending_user_id'], $_SESSION['pending_fullname'], $_SESSION['pending_email'], $_SESSION['otp_expires']);

    echo json_encode(["status" => "success", "message" => "Login successful", "redirect" => "dashboard.php"]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid OTP"]);
}
?>