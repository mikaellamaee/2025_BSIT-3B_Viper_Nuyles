<?php
session_start();

if (!isset($_SESSION['user_info_id'])) {
    header('Location: ../login.php');
    exit;
}

$userId = $_SESSION['user_info_id'];

// Database connection
$pdo = new PDO("mysql:host=localhost;dbname=origato_b2b;charset=utf8", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get user details
$stmt = $pdo->prepare("SELECT user_name, e_mail FROM user_info WHERE user_info_id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

$username = $user['user_name'];
$userEmail = $user['e_mail'];

// Get cart items
$stmt = $pdo->prepare("
    SELECT c.cart_id, c.item_qty, c.date_added, 
           p.item_name, p.item_price
    FROM cart c
    JOIN product_design p ON c.prdct_dsgn_id = p.prdct_dsgn_id
    WHERE c.user_info_id = ?
");
$stmt->execute([$userId]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Compute totals
$itemCount = 0;
$total = 0;
foreach ($cartItems as $item) {
    $itemCount += $item['item_qty'];
    $total += $item['item_price'] * $item['item_qty'];
}

$isLoggedIn = true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Your Cart - ORIGATO</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #4f6edb;
      color: #000;
    }
    header {
      background: white;
      padding: 10px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: bold;
      font-size: 24px;
      color: #1c1c1c;
    }
    .logo img {
      height: 40px;
    }
    .header-icons {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    .header-icons button {
      background-color: #d90479;
      color: white;
      border: none;
      padding: 5px 15px;
      border-radius: 5px;
      cursor: pointer;
    }
    .cart-container {
      background-color: white;
      border-radius: 30px;
      margin: 30px 60px;
      padding: 30px;
    }
    .cart-container h2 {
      margin-bottom: 20px;
      font-size: 28px;
    }
    .cart-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #ddd;
      padding: 15px 20px;
      border-radius: 8px;
      margin-bottom: 10px;
    }
    .cart-summary {
      background-color: white;
      margin: 0 60px 30px 60px;
      padding: 20px 30px;
      border-radius: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .btn-checkout {
      background-color: #d90479;
      color: white;
      padding: 10px 25px;
      border: none;
      border-radius: 10px;
      font-weight: bold;
      cursor: pointer;
    }
    .user-icon {
      width: 35px;
      height: 35px;
      border-radius: 50%;
      background-color: #e0e0e0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      cursor: pointer;
    }
    .remove-btn {
      border: none;
      background: none;
      font-size: 20px;
      cursor: pointer;
      color: red;
    }
    .dropdown-menu {
      display: none;
      position: absolute;
      background-color: white;
      border: 1px solid #ccc;
      border-radius: 5px;
      right: 0;
      top: 50px;
      z-index: 10;
    }
    .dropdown-menu.show {
      display: block;
    }
    .dropdown-btn {
      display: block;
      padding: 10px 20px;
      color: #000;
      text-decoration: none;
    }
    .dropdown-btn:hover {
      background-color: #f0f0f0;
    }
  </style>
</head>
<body>

<!-- HEADER -->
<header>
  <div class="logo">
    <a href="home.php"><img src="../photos/logo.jpeg" alt="Origato Logo" /></a>
    <span>ORIGATO</span>
  </div>
  <div class="header-icons">
    <?php if ($isLoggedIn): ?>
      <div class="position-relative">
        <div class="user-icon" id="userIcon" tabindex="0" aria-haspopup="true" aria-expanded="false" aria-controls="userDropdown">
          <i class="bi bi-person-circle"></i>
        </div>
        <div class="dropdown-menu" id="userDropdown" role="menu" aria-label="User menu">
          <a href="profile.php" class="dropdown-btn"><?= htmlspecialchars("Hello, $username") ?></a>
          <a href="../logout.php" class="dropdown-btn">Logout</a>
        </div>
      </div>
    <?php endif; ?>
  </div>
</header>

<!-- CART CONTENT -->
<div class="cart-container">
  <h2>Your Cart</h2>
  <?php if ($itemCount > 0): ?>
    <?php foreach ($cartItems as $item): ?>
      <div class="cart-item">
        <div>
          <strong><?= htmlspecialchars($item['item_name']) ?></strong><br>
          Quantity: <?= $item['item_qty'] ?><br>
          Price per item: ₱<?= number_format($item['item_price'], 2) ?><br>
          Subtotal: ₱<?= number_format($item['item_price'] * $item['item_qty'], 2) ?>
        </div>
        <form method="POST" action="remove_from_cart.php">
          <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
          <button type="submit" class="remove-btn" title="Remove item">&times;</button>
        </form>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p>Your cart is empty.</p>
  <?php endif; ?>
</div>

<!-- SUMMARY -->
<div class="cart-summary">
  <div>Total (<?= $itemCount ?> item<?= $itemCount !== 1 ? 's' : '' ?>): <strong>₱<?= number_format($total, 2) ?></strong></div>
  <button class="btn-checkout" onclick="window.location.href='order_form.php'">Check Out</button>
</div>

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

</body>
</html>
