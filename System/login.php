<?php
session_start();
// Enable full error reporting (for development only)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start session and include database files

include_once "db.php";        // Main user DB
include_once "log_db.php";    // Login log DB

// Security headers
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self'");

// Initialize error variable
$error = '';

// Check DB connections
if (!$conn) {
    die("Main database connection failed.");
}
if (!$log_conn) {
    die("Log database connection failed.");
}

// Process login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'])) {
    $uname = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $pword = $_POST['password'];
    $login_time = date('Y-m-d H:i:s');

    // Query user
    $stmt = $conn->prepare("SELECT user_info_id, user_type, user_status, add_ress, 
                            user_name, contact_no, pass_word, e_mail 
                            FROM user_info WHERE e_mail = ?");
    $stmt->bind_param("s", $uname);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
      $row = $result->fetch_assoc();

        // Verify password (hashed by default)
        if (password_verify($pword, $row['pass_word'])) {
            // Set session
            $_SESSION['user_info_id'] = $row['user_info_id'];
            $_SESSION['user_info_username'] = $row['user_name'];
            $_SESSION['user_info_password'] = $row['pass_word'];
            $_SESSION['user_info_address'] = $row['add_ress'];
            $_SESSION['user_info_contact_no'] = $row['contact_no'];
            $_SESSION['user_info_user_type'] = $row['user_type'];
            $_SESSION['user_info_email'] = $row['e_mail'];

            // Log successful login
            $log_stmt = $log_conn->prepare("INSERT INTO login_logs 
                (user_info_id, e_mail, date_login) 
                VALUES (?, ?, ?)");
            $log_stmt->bind_param("iss", $row['user_info_id'], $row['e_mail'], $login_time);
            $log_stmt->execute();

            // Redirect based on user type
            if ($row['user_type'] === 'A') {
                header("Location: admin/admin_homepage.php");
            } elseif ($row['user_type'] === 'U') {
                header("Location: user/home.php");
            } else {
                $error = "Invalid user type.";
            }
            exit();
        }
    }

    // Log failed login attempt
    $log_stmt = $log_conn->prepare("INSERT INTO login_logs 
        (user_info_id, e_mail, date_login) 
        VALUES (NULL, ?, ?)");
    $log_stmt->bind_param("ss", $uname, $login_time);
    $log_stmt->execute();

    $error = "Invalid email or password.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Login - ORIGATO</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="css/login.css" />
</head>
<body>
  <header class="header">
    <div class="logo">
      <img src="photos/logo.jpeg" alt="Origato Logo" />
      <span>ORIGATO</span>
    </div>
  </header>

  <div class="container">
    <div class="left-panel">
      <div class="birds-wrapper">
        <!-- Bird SVGs -->
        <svg class="bird bird1" viewBox="0 0 64 64" width="100" height="100"><polygon points="10,10 32,4 30,20" /><polygon points="32,4 40,12 30,20" /><polygon points="30,20 40,12 44,32" /><polygon points="10,10 30,20 20,28" /><polygon points="20,28 44,32 30,40" /></svg>
        <svg class="bird bird2" viewBox="0 0 64 64" width="200" height="200"><polygon points="10,15 32,10 30,26" /><polygon points="32,10 40,18 30,26" /><polygon points="30,26 40,18 44,38" /><polygon points="10,15 30,26 20,34" /><polygon points="20,34 44,38 30,46" /></svg>
        <svg class="bird bird3" viewBox="0 0 64 64" width="300" height="300"><polygon points="10,5 32,0 30,16" /><polygon points="32,0 40,8 30,16" /><polygon points="30,16 40,8 44,28" /><polygon points="10,5 30,16 20,24" /><polygon points="20,24 44,28 30,36" /></svg>
      </div>
      <h2>Hello there!</h2>
    </div>

    <div class="right-wrapper">
      <div class="right-panel">
        <h1>Log In</h1>
        <?php if (!empty($error)) echo "<p class='error-msg' style='color:red;'>$error</p>"; ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
          <input type="email" name="email" placeholder="Enter Email" required autocomplete="email" />
          <div class="password-wrapper">
            <input type="password" name="password" placeholder="Enter Password" required autocomplete="current-password" />
            <span class="toggle-password" onclick="togglePassword()"></span>
          </div>
          <button type="submit">LOG IN</button>
          <div class="options"><a href="#">Forgot Password?</a></div>
          <div class="bottom-text">New to ORIGATO? <a href="signup.php">Sign Up</a></div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function togglePassword() {
      const pwField = document.querySelector('input[name="password"]');
      pwField.type = pwField.type === "password" ? "text" : "password";
    }
  </script>
</body>
</html>
