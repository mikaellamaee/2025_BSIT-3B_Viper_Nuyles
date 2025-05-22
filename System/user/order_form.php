<?php
include '../db.php';
session_start();

function generateOrderReference($length = 6) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return 'ORD-' . $randomString;
  }
  $orderReference = generateOrderReference();
// Check session
$user_id = $_SESSION['user_info_id'] ?? null;
if (!$user_id) {
    header("Location: ../login.php");
    exit;
}

// Fetch user info
$user_query = "SELECT user_name, e_mail, add_ress, contact_no FROM user_info WHERE user_info_id = $user_id";
$user_result = mysqli_query($conn, $user_query);

if ($user_result && mysqli_num_rows($user_result) === 1) {
    $user = mysqli_fetch_assoc($user_result);
    $username = $user['user_name'];
    $email = $user['e_mail'];
    $address = $user['add_ress'];
    $contact = $user['contact_no'];
} else {
    echo "❌ Error fetching user data.";
    exit;
}

// Check if form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and assign POST data
    $order_ref = mysqli_real_escape_string($conn, $_POST['order_ref']);
    $name     = mysqli_real_escape_string($conn, $_POST['uname']);
    $email     = mysqli_real_escape_string($conn, $_POST['email']);
    $address   = mysqli_real_escape_string($conn, $_POST['address']);
    $contact   = mysqli_real_escape_string($conn, $_POST['contact']);
    $date_now  = date('Y-m-d H:i:s');

    $update_query = "
    UPDATE user_info
    SET 
        user_name = '$name',
        e_mail = '$email',
        add_ress = '$address',
        contact_no = '$contact',
        date_updated = '$date_now'
    WHERE user_info_id = $user_id
    ";

    if (mysqli_query($conn, $update_query)) {
        header("Location: order_success.php?ref=$order_ref");
        exit;
    } else {
        echo "❌ Error updating user info: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Form - ORIGATO</title>
  <link rel="stylesheet" href="../css/home.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #4f6edb;
      margin: 0;
      padding: 0;
    }
    header {
      background: #fff;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 30px;
      border-bottom: 1px solid #ccc;
    }
    .logo img {
      height: 40px;
    }
    .logo span {
      font-size: 24px;
      font-weight: bold;
      margin-left: 10px;
    }
    .header-icons {
      display: flex;
      align-items: center;
    }
    .container {
      display: flex;
      flex-wrap: wrap;
      padding: 30px;
      gap: 30px;
    }
    form {
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 15px;
    }
    label {
      display: flex;
      flex-direction: column;
      color: white;
    }
    input {
      padding: 8px;
      font-size: 16px;
    }
    .Yorder {
      flex: 1;
      background-color: white;
      border: 1px solid #ccc;
      padding: 20px;
      border-radius: 8px;
    }
    .Yorder table {
      width: 100%;
      border-collapse: collapse;
    }
    .Yorder th, .Yorder td {
      padding: 10px;
      border-bottom: 1px solid #ddd;
    }
    .center {
      display: block;
      margin: 20px auto;
    }
    button {
      padding: 10px;
      background-color: #d90479;
      color: white;
      border: none;
      cursor: pointer;
    }
    button:hover {
      background-color: #b30361;
    }
  </style>
</head>
<body>

<header>
  <div class="logo d-flex align-items-center">
  <a href="home.php"><img src="../photos/logo.jpeg" alt="Origato Logo" /></a>
    <span>ORIGATO</span>
  </div>
  <div class="header-icons">
    <a href="cart.php" class="btn btn-sm" style="background-color: #d90479; color: white;">Cart</a>
    <div class="user-icon ms-3 text-dark">
      <i class="bi bi-person-circle"></i> <?= htmlspecialchars($username) ?>
    </div>
  </div>
</header>

<div class="container" style="margin-top: 50px;">
  <!-- Customer Info Form -->
  <form action="process_order.php" method="post">
    <label>
      <span>Username *</span>
      <input type="text" name="uname" value="<?= htmlspecialchars($username) ?>" required>
    </label>
    <label>
      <span>Address *</span>
      <input type="text" name="address" value="<?= htmlspecialchars($address) ?>" required>
    </label>
    <label>
      <span>Contact No *</span>
      <input type="tel" name="contact" value="<?= htmlspecialchars($contact) ?>" required>
    </label>
    <label>
      <span>Email *</span>
      <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
    </label>
    <label>
      <span>Order Reference</span>
      <input type="text" name="order_ref" value="<?= $orderReference ?>" readonly>
    </label>
    <label>
      <span>Scan to Pay</span>
    </label>
    <img src="../photos/gcash.jpg" alt="GCash QR" class="center" width="300">

    <input type="hidden" name="user_info_id" value="<?= $user_id ?>">
    <button type="submit">Place Order</button>
  </form>

  <!-- Order Summary -->
  <div class="Yorder">
    <table>
      <tr><th colspan="4">Your Order</th></tr>
      <?php
$total = 0;
$cart_query = "
  SELECT 
    c.cart_id, 
    c.item_qty AS cart_qty, 
    pd.item_name, 
    pd.item_price, 
    pd.item_photo
  FROM cart c
  JOIN product_design pd ON c.prdct_dsgn_id = pd.prdct_dsgn_id
  WHERE c.user_info_id = $user_id
";
$cart_result = mysqli_query($conn, $cart_query);

while ($row = mysqli_fetch_assoc($cart_result)) {
  $subtotal = $row['item_price'] * $row['cart_qty'];
  $total += $subtotal;
  echo "<tr>
    <td><img src='../photos/{$row['item_photo']}' width='40px'></td>
    <td>{$row['item_name']}</td>
    <td>{$row['cart_qty']}x</td>
    <td>Php " . number_format($subtotal, 2) . "</td>
  </tr>";
}
?>
<tr>
  <td colspan="3"><strong>Total</strong></td>
  <td><strong>Php <?= number_format($total, 2) ?></strong></td>
</tr>
<tr>
  <td colspan="3">Shipping</td>
  <td>Free</td>
</tr>
    </table>
  </div>
</div>

</body>
</html>
<script>
     const userIcon = document.getElementById('userIcon');
  const userDropdown = document.getElementById('userDropdown');

  userIcon.addEventListener('click', () => {
    userDropdown.classList.toggle('show');
    const expanded = userIcon.getAttribute('aria-expanded') === 'true';
    userIcon.setAttribute('aria-expanded', !expanded);
  });

  window.addEventListener('click', (e) => {
    if (!userIcon.contains(e.target) && !userDropdown.contains(e.target)) {
      userDropdown.classList.remove('show');
      userIcon.setAttribute('aria-expanded', false);
    }
  });

  window.addEventListener('keydown', (e) => {
    if (e.key === "Escape") {
      userDropdown.classList.remove('show');
      userIcon.setAttribute('aria-expanded', false);
    }
  });
</script>
