<?php
header("Content-Type: application/json");
include 'db.php';

// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$identifier = trim($_POST['identifier'] ?? '');

if ($identifier === '') {
    echo json_encode(["status" => "error", "message" => "Email is required"]);
    exit;
}

// Find user by email
$stmt = $conn->prepare("SELECT id, email FROM users WHERE email=?");
$stmt->bind_param("s", $identifier);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Generate reset token
    $token = bin2hex(random_bytes(16));
    $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

    // Save token in DB
    $update = $conn->prepare("UPDATE users SET reset_token=?, reset_expires=? WHERE id=?");
    $update->bind_param("ssi", $token, $expires, $row['id']);
    $update->execute();

    $resetLink = "http://lgu3.unit3-local.com/reset-password.php?token=" . $token;

    // Send email via PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';              // SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'bustamante.emerson.royo@gmail.com';     // your Gmail address
        $mail->Password = 'wrap bovs zrvh vqds';       // Gmail App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('LGU3-UNIT3@gmail.com', 'LGU 3 - Unit 3');
        $mail->addAddress($row['email']);            // recipient

        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = "Hello,<br><br>Click the following link to reset your password:<br>
                          <a href='$resetLink'>$resetLink</a><br><br>
                          This link will expire in 1 hour.";

        $mail->send();
        echo json_encode(["status" => "success", "message" => "Reset link sent to your email"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Mailer Error: {$mail->ErrorInfo}"]);
    }
} else {
    echo json_encode(["status" => "success", "message" => "If this email exists, a reset link has been sent"]);
}

$stmt->close();
$conn->close();
?>