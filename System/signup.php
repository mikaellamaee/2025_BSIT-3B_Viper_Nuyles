<?php
require 'db.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $uname = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.');</script>";
        exit;
    }

    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match.');</script>";
        exit;
    }

    // Check if email exists
    $stmt = $conn->prepare("SELECT user_info_id FROM user_info WHERE e_mail = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "<script>alert('Email is already registered.');</script>";
        exit;
    }
    $stmt->close();

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $default_user_type = 'U';

    // Insert into user_info (with minimal fields)
    $stmt = $conn->prepare("INSERT INTO user_info (e_mail, user_name, pass_word, user_type, date_registered) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $email, $uname, $hashed_password, $default_user_type);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Signup successful! Redirecting to login...'); window.location.href = 'login.php';</script>";
    exit;
}
?>
  
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title> Signup - ORIGATO</title>
  <style>
    /* Reset and base styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', 'Segoe UI', sans-serif;
    }

    body {
      background-color: #4a6ed1;
      color: #333;
    }

    /* Header */
    .header {
      background-color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 2rem;
      height: 60px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .header .logo {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-weight: 700;
      font-size: 1.25rem;
      color: #1c1c1c;
    }

    .header .logo img {
      height: 40px;
    }

    .header .help-link {
      color: #476cdf;
      text-decoration: none;
      font-weight: 600;
      font-size: 1rem;
    }

    .header .help-link:hover {
      text-decoration: underline;
    }

    /* Container for panels */
    .container {
      display: flex;
      min-height: calc(100vh - 60px);
    }

    /* Left panel with birds and text */
    .left-panel {
      flex: 1;
      background-color: #4a6ed1;
      color: white;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 50px;
      text-align: center;
    }

    /* Bird SVG container and animations */
    .birds-wrapper {
      position: relative;
      width: 240px;
      height: 150px;
      margin-bottom: 2rem;
    }

    .bird {
      position: absolute;
      fill: white;
      opacity: 0.9;
      filter: drop-shadow(1px 1px 2px rgba(0,0,0,0.15));
    }

    .bird1 { top: 10px; left: 30px; animation: float 4s ease-in-out infinite, flap 1.5s ease-in-out infinite; }
    .bird2 { top: 100px; left: 10px; animation: float 5s ease-in-out infinite, driftLeft 6s ease-in-out infinite; }
    .bird3 { top: -10px; left: 100px; animation: float 6s ease-in-out infinite, flap 2s ease-in-out infinite; }

    /* Left panel heading */
    .left-panel h2 {
      font-size: 28px;
      font-weight: 700;
      margin-top: 100px;
    }

    /* Right panel container */
    .right-wrapper {
      flex: 1;
      display: flex;
      justify-content: flex-end;
      align-items: center;
      padding-right: 50px;
    }

    /* Right sign-up panel */
    .right-panel {
      background-color: white;
      padding: 100px 30px;
      border-top-left-radius: 50px;
      border-bottom-left-radius: 50px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      max-width: 400px;
      width: 100%;
      text-align: center;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    .right-panel h1 {
      font-size: 28px;
      font-weight: 900;
      margin-bottom: 30px;
      color: #000;
    }

    /* Form styles */
    form {
      width: 100%;
      display: flex;
      flex-direction: column;
      gap: 18px;
      align-items: center;
    }

    input[type="email"],
    input[type="password"],
    input[type="text"] {
      width: 100%;
      padding: 14px 15px;
      font-size: 14px;
      border-radius: 6px;
      border: none;
      background-color: #bbcafb;
      color: #000;
      font-weight: 500;
      padding-right: 45px; /* for eye icon space */
      outline: none;
    }

    input::placeholder {
      color: #000;
    }

    /* Wrapper for password inputs to position eye icons */
    .password-wrapper {
      position: relative;
      width: 100%;
    }

    /* Eye icon */
    .toggle-password {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 18px;
      color: #4a6ed1;
      cursor: pointer;
      user-select: none;
      transition: color 0.3s ease;
    }

    .toggle-password:hover {
      color: #d50061;
    }

    /* Submit button */
    button {
      width: 100%;
      padding: 14px;
      background-color: #d50061;
      color: white;
      font-weight: 800;
      font-size: 15px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      letter-spacing: 0.05em;
      margin-top: 14px;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #b40057;
    }

    /* Disclaimer below button */
    .disclaimer {
      font-size: 11px;
      color: #3b4fbb;
      margin-top: 12px;
      user-select: text;
      max-width: 260px;
    }

    .disclaimer a {
      color: #d50061;
      font-weight: 600;
      text-decoration: none;
    }

    .disclaimer a:hover {
      text-decoration: underline;
    }

    /* Login prompt below disclaimer */
    .login-prompt {
      margin-top: 22px;
      font-size: 13px;
      color: #8ca1e1;
    }

    .login-prompt a {
      color: #d50061;
      font-weight: 600;
      text-decoration: none;
      margin-left: 6px;
    }

    .login-prompt a:hover {
      text-decoration: underline;
    }

    /* Responsive for smaller screens */
    @media screen and (max-width: 768px) {
      .container {
        flex-direction: column;
      }

      .right-wrapper {
        justify-content: center;
        padding: 0 20px;
      }

      .right-panel {
        border-radius: 20px;
        margin-top: 30px;
      }

      .left-panel {
        padding: 30px;
      }
    }

    /* Animations for birds */
    @keyframes float {
      0%   { transform: translateY(0px) rotate(0deg); }
      50%  { transform: translateY(-10px) rotate(2deg); }
      100% { transform: translateY(0px) rotate(0deg); }
    }

    @keyframes flap {
      0%, 100% { transform: scaleY(1); }
      50%      { transform: scaleY(0.9); }
    }

    @keyframes driftLeft {
      0%   { transform: translateX(0) translateY(0); }
      50%  { transform: translateX(-10px) translateY(-5px); }
      100% { transform: translateX(0) translateY(0); }
    }
  </style>
</head>
<body>

  <header class="header">
    <div class="logo">
      <img src="photos/logo.jpeg" alt="Origato Logo" />
      <span>ORIGATO</span>
    </div>
    <a href="#" class="help-link">Need Help?</a>
  </header>

  <div class="container">
    <div class="left-panel">
      <div class="birds-wrapper">
        <!-- Bird 1 -->
        <svg class="bird bird1" viewBox="0 0 64 64" width="100" height="100" xmlns="http://www.w3.org/2000/svg">
          <polygon points="10,10 32,4 30,20" />
          <polygon points="32,4 40,12 30,20" />
          <polygon points="30,20 40,12 44,32" />
          <polygon points="10,10 30,20 20,28" />
          <polygon points="20,28 44,32 30,40" />
        </svg>
        <!-- Bird 2 -->
        <svg class="bird bird2" viewBox="0 0 64 64" width="150" height="150" xmlns="http://www.w3.org/2000/svg">
          <polygon points="15,15 42,10 38,34" />
          <polygon points="42,10 55,24 38,34" />
          <polygon points="38,34 55,24 61,55" />
          <polygon points="15,15 38,34 27,45" />
          <polygon points="27,45 61,55 38,60" />
        </svg>
        <!-- Bird 3 -->
        <svg class="bird bird3" viewBox="0 0 64 64" width="200" height="200" xmlns="http://www.w3.org/2000/svg">
          <polygon points="12,12 38,8 35,28" />
          <polygon points="38,8 50,20 35,28" />
          <polygon points="35,28 50,20 55,42" />
          <polygon points="12,12 35,28 25,35" />
          <polygon points="25,35 55,42 35,48" />
        </svg>
      </div>
      <h2>Start your journey with Origato!</h2>
    </div>

    <div class="right-wrapper">
      <div class="right-panel">
        <h1>Sign Up</h1>
        <form method="POST" action="">
  <input type="email" name="email" placeholder="Enter Email" required />

  <input type="text" name="username" placeholder="Enter Username" required />

  <div class="password-wrapper">
    <input type="password" name="password" id="password1" placeholder="Enter Password" required />
    <span class="toggle-password" onclick="togglePassword('password1')">👁️</span>
  </div>

  <div class="password-wrapper">
    <input type="password" name="confirm_password" id="password2" placeholder="Re-Enter Password" required />
    <span class="toggle-password" onclick="togglePassword('password2')">👁️</span>
  </div>

  <button type="submit">SIGN UP</button>
</form>


        <p class="disclaimer">
          By signing up, you agree to ORIGATO’s <a href="#">Terms of Service</a> &amp; <a href="#">Privacy Policy</a>
        </p>

        <p class="login-prompt">
          Have an account? <a href="login.php">Log In</a>
        </p>
      </div>
    </div>
  </div>

  <script>
    function togglePassword(id) {
      const input = document.getElementById(id);
      if (!input) return;
      input.type = input.type === 'password' ? 'text' : 'password';
    }
  </script>

</body>
</html>
