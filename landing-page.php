<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LGU 3: AI-Powered Rice Yield Forecasting</title>
  <link rel="stylesheet" href="landing.css?v=<?php echo time(); ?>">
</head>
<body>

<header>
  <img src="LOGO.jpg" alt="LGU 3 Logo" class="logo">
  <div class="header-text">
    <h1>Local Government Unit 3</h1>
    <p>AI-Powered Predictive Model for Rice Yield Forecasting and Pest Management</p>
  </div>
</header>

<nav>
  <button class="menu-toggle" onclick="toggleMenu()">☰ Menu</button>
  <ul>
    <li><button onclick="loadContent('about.php')">About</button></li>
    <li><button onclick="loadContent('features.php')">Features</button></li>
    <li><button onclick="loadContent('team.php')">Team</button></li>
    <li><button onclick="loadContent('contact.php')">Contact</button></li>
  </ul>
  <div class="auth-buttons">
    <button class="signup-btn" onclick="openModal('signupModal')">Sign Up</button>
    <button class="login-btn" onclick="openModal('loginModal')">User LogIn</button>
    <button class="admin-btn" onclick="openModal('adminLoginModal')">Admin</button>
  </div>
</nav>

<!-- Chatbot -->
<div id="chatbot">
  <div id="chatbot-header">
    🤖 Chatbot AI
    <button id="chatbot-toggle">–</button>
  </div>
  <div id="chatbot-messages"></div>
  <div id="chatbot-input">
    <input type="text" id="chatbot-text" placeholder="Type a message...">
    <button onclick="sendMessage()">Send</button>
  </div>
</div>

<!-- Sign Up Modal -->
<div id="signupModal" class="modal">
  <div class="modal-content">
    <h2>Sign Up</h2>
    <input type="text" id="signup-name" placeholder="Full Name" required>
    <input type="email" id="signup-email" placeholder="Email" required>
    <input type="password" id="signup-password" placeholder="Password" required>
    <input type="text" id="signup-phone" placeholder="Phone Number" required>
    <input type="text" id="signup-address" placeholder="Address" required>
    <button onclick="submitSignup()">Submit</button>
    <button class="close" onclick="closeModal('signupModal')">Close</button>
  </div>
</div>


<!-- User Login Modal -->
<div id="loginModal" class="modal">
  <div class="modal-content">
    <h2>User Log In</h2>
    <input type="email" id="login-email" placeholder="Email">
    <input type="password" id="login-password" placeholder="Password">

    <!-- Forgot Password link above buttons -->
    <a href="#" onclick="openModal('forgotPasswordModal')" class="forgot-link">Forgot Password?</a>

    <!-- Buttons side by side -->
    <div class="button-row">
      <button onclick="submitLogin()">Log In</button>
      <button class="close" onclick="closeModal('loginModal')">Close</button>
    </div>
  </div>
</div>

<!-- Forgot Password Modal -->
<div id="forgotPasswordModal" class="modal">
  <div class="modal-content">
    <h2>Forgot Password</h2>
    <p>Enter your email or phone number to receive a reset link:</p>
    <input type="text" id="forgot-identifier" placeholder="Email or Phone" required>
    <button onclick="submitForgotPassword()">Send Reset Link</button>
    <button class="close" onclick="closeModal('forgotPasswordModal')">Close</button>
  </div>
</div>

<!-- Admin Login Modal -->
<div id="adminLoginModal" class="modal">
  <div class="modal-content">
    <h2>Admin Log In</h2>
    <input type="email" id="admin-email" placeholder="Admin Email">
    <input type="password" id="admin-password" placeholder="Password">
    <button onclick="submitAdminLogin()">Log In</button>
    <button class="close" onclick="closeModal('adminLoginModal')">Close</button>
  </div>
</div>

<!-- Admin OTP Modal -->
<div id="adminOtpModal" class="modal">
  <div class="modal-content">
    <h2>Enter Admin OTP</h2>
    <input type="text" id="admin-otp-code" placeholder="6-digit code">
    <div class="button-row">
      <button onclick="submitAdminOtp()">Confirm</button>
      <button class="close" onclick="closeModal('adminOtpModal')">Close</button>
    </div>
  </div>
</div>

<!-- OTP Modal -->
<div id="otpModal" class="modal">
  <div class="modal-content">
    <h2>Enter OTP</h2>
    <input type="text" id="otp-code" placeholder="6-digit code">
    <div class="button-row">
      <button onclick="submitOtp()">Confirm</button>
      <button class="close" onclick="closeModal('otpModal')">Close</button>
    </div>
  </div>
</div>

<!-- Weather Section -->
<section id="weather" class="weather-section">
  <h2>🌤 Weather Prediction AI</h2>
  <p>Enter your city to get a 5‑day forecast:</p>
  <input type="text" id="city" placeholder="e.g. Quezon City">
  <button onclick="getForecast()">Get Forecast</button>
  <div id="weather-result"></div>
</section>

<!-- Dynamic content container -->
<section id="dynamic-content"></section>

<footer class="footer">
  <p>&copy; <?php echo date("Y"); ?> LGU 3 - AI Rice Yield Forecasting Project</p>
</footer>

<script src="landing.js?v=<?php echo time(); ?>" defer></script>
<div id="toast" class="toast"></div>
</body>
</html>