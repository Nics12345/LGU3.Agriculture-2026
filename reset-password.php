<?php
error_reporting(E_ALL);

include 'db.php';

// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$message = "";
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token    = trim($_POST['token'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm  = trim($_POST['confirm'] ?? '');

    if ($token === '' || $password === '' || $confirm === '') {
        $message = "All fields are required";
    } elseif ($password !== $confirm) {
        $message = "Passwords do not match";
    } else {
        // Find user by token
        $stmt = $conn->prepare("SELECT id, email, reset_expires FROM users WHERE reset_token=?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Check if token expired
            if (strtotime($row['reset_expires']) < time()) {
                $message = "Reset token has expired";
            } else {
                // Update password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $update = $conn->prepare("UPDATE users SET password=?, reset_token=NULL, reset_expires=NULL WHERE id=?");
                $update->bind_param("si", $hashedPassword, $row['id']);
                if ($update->execute()) {
                    $success = true;
                    $message = "Password reset successful. Redirecting to login...";

                    // Send confirmation email
                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'bustamante.emerson.royo@gmail.com';     // your Gmail
                        $mail->Password = 'wrap bovs zrvh vqds';                   // Gmail App Password
                        $mail->SMTPSecure = 'tls';
                        $mail->Port = 587;

                        $mail->setFrom('LGU3-UNIT3@gmail.com', 'LGU 3 - Unit 3');
                        $mail->addAddress($row['email']);

                        $mail->isHTML(true);
                        $mail->Subject = 'Password Reset Confirmation';
                        $mail->Body    = "Hello,<br><br>Your password has been successfully reset.<br>
                                          If you did not request this change, please contact support immediately.";

                        $mail->send();
                    } catch (Exception $e) {
                        error_log("Mailer Error: {$mail->ErrorInfo}");
                    }
                } else {
                    $message = "Database error: " . $update->error;
                }
                $update->close();
            }
        } else {
            $message = "Invalid reset token";
        }
        $stmt->close();
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <link rel="stylesheet" href="landing.css?v=<?php echo time(); ?>">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f6f9;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .reset-container {
      background: #fff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      width: 350px;
      text-align: center;
    }
    .reset-container h2 {
      margin-bottom: 20px;
    }
    .reset-container input {
      width: 100%;
      padding: 10px;
      margin: 8px 0;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .reset-container button {
      width: 100%;
      padding: 10px;
      background: #28a745;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .reset-container .message {
      margin: 10px 0;
      color: #d9534f;
    }
  </style>
</head>
<body>
  <div class="reset-container">
    <h2>Reset Password</h2>
    <?php if ($message !== ""): ?>
      <div class="message"><?php echo htmlspecialchars($message); ?></div>
      <?php if ($success): ?>
        <script>
          setTimeout(() => {
            window.location.href = "landing-page.php";
          }, 2000);
        </script>
      <?php endif; ?>
    <?php endif; ?>
    <?php if (!$success): ?>
      <form action="reset-password.php" method="post">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">
        <input type="password" name="password" placeholder="New Password" required>
        <input type="password" name="confirm" placeholder="Confirm Password" required>
        <button type="submit">Reset Password</button>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>