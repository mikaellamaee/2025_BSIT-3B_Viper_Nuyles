<?php
session_set_cookie_params(['path' => '/']);
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_info_id'])) {
    die("User is not logged in.");
}

$host = "localhost";
$username = "root";
$password = "";
$database = "origato_b2b";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_info_id'];
$update_success = false;

// Fetch current profile picture and data for fallback
$current_pic = null;
$stmt = $conn->prepare("SELECT profile_picture FROM user_info WHERE user_info_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $current_data = $result->fetch_assoc();
    $current_pic = $current_data['profile_picture'] ?? null;
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_new = $_POST['username'] ?? '';
    $email_new = $_POST['email'] ?? '';
    $phone_new = $_POST['phone'] ?? '';
    $address_new = $_POST['address'] ?? '';
    $profile_pic_new = $current_pic;

    // Handle profile picture upload
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == UPLOAD_ERR_OK) {
        $allowed_ext = ['png', 'jpeg', 'jpg'];
        $file_name = $_FILES['profile_pic']['name'];
        $file_tmp = $_FILES['profile_pic']['tmp_name'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if (in_array($ext, $allowed_ext) && $_FILES['profile_pic']['size'] <= 1 * 1024 * 1024) {
            $new_file_name = '../photos/' . $user_id . '.' . $ext;
            move_uploaded_file($file_tmp, $new_file_name);
            $profile_pic_new = $new_file_name;
        }
    }

    // Update user info (excluding password)
    $stmt = $conn->prepare("UPDATE user_info SET user_name=?, e_mail=?, contact_no=?, add_ress=?, profile_picture=? WHERE user_info_id=?");
    $stmt->bind_param("sssssi", $username_new, $email_new, $phone_new, $address_new, $profile_pic_new, $user_id);

    if (!$stmt->execute()) {
        die("Update failed: " . $stmt->error);
    }

    $update_success = true;
}

// Fetch updated user info
$sql = "SELECT user_name, e_mail, contact_no, add_ress, profile_picture FROM user_info WHERE user_info_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $username = htmlspecialchars($user['user_name']);
    $email = htmlspecialchars($user['e_mail']);
    $phone = htmlspecialchars($user['contact_no']);
    $address = htmlspecialchars($user['add_ress']);
    $user_profile_pic = $user['profile_picture'] ?: 'https://cdn-icons-png.flaticon.com/512/149/149071.png';
} else {
    $username = $email = $phone = $address = "";
    $user_profile_pic = 'https://cdn-icons-png.flaticon.com/512/149/149071.png';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>User Account - ORIGATO</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/profile.css" />
</head>
<body>

<header>
  <div class="logo">
  <a href="home.php"><img src="../photos/logo.jpeg" alt="Origato Logo" /></a>
    <span>ORIGATO</span>
  </div>
  <div class="header-icons" style="gap: 20px;">
    <button title="Cart" style="background: none; border: none; cursor: pointer; color: #d90479;" onclick="window.location.href='cart.php';">
      <i class="bi bi-cart" style="font-size: 1.4rem;"></i>
    </button>
    <div class="dropdown">
      <button class="btn btn-pink rounded-circle p-0" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="width: 40px; height: 40px; background-color: transparent; border: none; padding: 0; overflow: hidden;">
        <img src="<?php echo $user_profile_pic; ?>" alt="Profile Picture" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%; border: 2px solid #d90479;" />
      </button>
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
        <li><a class="dropdown-item btn btn-pink text-white mb-1" href="user/home.php" style="background-color: #d90479; border-radius: 5px;">Homepage</a></li>
        <li><a class="dropdown-item btn btn-pink text-white" href="../logout.php" style="background-color: #d90479; border-radius: 5px;">Logout</a></li>
      </ul>
    </div>
  </div>
</header>

<div class="profile-container">
  <div class="sidebar">
    <img src="<?php echo $user_profile_pic; ?>" alt="Profile" />
    <h4><?php echo $username; ?></h4>
    <hr />
    <a href="profile.php" class="active"><i class="bi bi-person"></i>Account</a>
    <a href="order_details.php"><i class="bi bi-bag"></i>My Purchase</a>
  </div>

  <div class="main-content">
    <h2>My Profile</h2>

    <?php if ($update_success): ?>
      <div class="alert alert-success">Profile updated successfully!</div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
      <div class="profile-pic">
        <img src="<?php echo $user_profile_pic; ?>" alt="Avatar" />
        <div>
          <input type="file" name="profile_pic" accept=".png,.jpeg,.jpg" />
          <br />
          <span class="upload-instructions">File Size: max 1MB<br />Extensions: PNG, JPEG, JPG</span>
        </div>
      </div>
      <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" value="<?php echo $username; ?>" required />
        <i class="bi bi-pencil edit-icon"></i>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="<?php echo $email; ?>" required />
        <i class="bi bi-pencil edit-icon"></i>
      </div>
      <div class="form-group">
        <label>Address</label>
        <input type="text" name="address" value="<?php echo $address; ?>" required />
        <i class="bi bi-pencil edit-icon"></i>
      </div>
      <div class="form-group">
        <label>Phone Number</label>
        <input type="text" name="phone" value="<?php echo $phone; ?>" pattern="\d{10,15}" title="Enter a valid phone number" />
        <i class="bi bi-pencil edit-icon"></i>
      </div>
      <button class="save-btn" type="submit">Save</button>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
