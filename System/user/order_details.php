<?php
session_start();

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

function getOrdersByPhase($conn, $user_id, $phase) {
    $stmt = $conn->prepare("
        SELECT o.order_id, o.order_ref, o.order_qty, o.date_added,
               p.item_name, p.item_photo, p.item_price
        FROM orders o
        JOIN product_design p ON o.prdct_dsgn_id = p.prdct_dsgn_id
        WHERE o.user_info_id = ? AND o.order_phase = ?
    ");
    $stmt->bind_param("is", $user_id, $phase);
    $stmt->execute();
    return $stmt->get_result();
}

$orders_to_ship = getOrdersByPhase($conn, $user_id, "Pending");
$orders_to_receive = getOrdersByPhase($conn, $user_id, "Shipping");
$orders_completed = getOrdersByPhase($conn, $user_id, "Completed");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Order Details - ORIGATO</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/order_details.css" />
  <style>
    .tabs button {
      margin-right: 10px;
      padding: 10px 20px;
      border: none;
      background-color: #ddd;
      border-radius: 5px;
      cursor: pointer;
    }
    .tabs button.active {
      background-color: #d90479;
      color: white;
    }
    .tab-content {
      display: none;
      margin-top: 20px;
    }
    .tab-content.active {
      display: block;
    }
    .order-card {
      border: 1px solid #ccc;
      padding: 15px;
      margin-bottom: 10px;
      border-radius: 10px;
      background-color: #f9f9f9;
      display: flex;
      gap: 15px;
    }
    .order-card img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 10px;
    }
    .no-orders img {
      width: 100px;
    }
  </style>
</head>
<body>

<header>
  <div class="logo">
  <a href="home.php"><img src="../photos/logo.jpeg" alt="Origato Logo" /></a>
    <span>ORIGATO</span>
  </div>
  <div class="header-icons">
    <a href="cart.php"><button title="Cart"><i class="bi bi-cart"></i></button></a>
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
    <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="Profile" />
    <h4>Welcome</h4>
    <hr />
    <a href="profile.php"><i class="bi bi-person"></i>Account</a>
    <a href="order_details.php" class="active"><i class="bi bi-bag"></i>My Purchase</a>
  </div>

  <div class="main-content">
    <div class="tabs">
      <button class="tab-btn active" data-tab="to-ship">To Ship</button>
      <button class="tab-btn" data-tab="to-receive">To Receive</button>
      <button class="tab-btn" data-tab="completed">Completed</button>
    </div>

    <!-- To Ship -->
    <div id="to-ship" class="tab-content active">
      <?php if ($orders_to_ship->num_rows > 0): ?>
        <?php while ($order = $orders_to_ship->fetch_assoc()): ?>
          <div class="order-card">
            <img src="../photos/<?= htmlspecialchars($order['item_photo']) ?>" alt="<?= htmlspecialchars($order['item_name']) ?>">
            <div>
              <strong>Order Ref:</strong> <?= htmlspecialchars($order['order_ref']) ?><br>
              <strong>Product:</strong> <?= htmlspecialchars($order['item_name']) ?><br>
              <strong>Quantity:</strong> <?= $order['order_qty'] ?><br>
              <strong>Price per Unit:</strong> ₱<?= number_format($order['item_price'], 2) ?><br>
              <strong>Total:</strong> ₱<?= number_format($order['item_price'] * $order['order_qty'], 2) ?><br>
              <small><strong>Date Ordered:</strong> <?= date("M d, Y", strtotime($order['date_added'])) ?></small>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="no-orders text-center">
          <img src="https://cdn-icons-png.flaticon.com/512/2748/2748558.png" alt="No Orders" />
          <p>No orders to ship</p>
        </div>
      <?php endif; ?>
    </div>

    <!-- To Receive -->
    <div id="to-receive" class="tab-content">
      <?php if ($orders_to_receive->num_rows > 0): ?>
        <?php while ($order = $orders_to_receive->fetch_assoc()): ?>
          <div class="order-card">
            <img src="../photos/<?= htmlspecialchars($order['item_photo']) ?>" alt="<?= htmlspecialchars($order['item_name']) ?>">
            <div>
              <strong>Order Ref:</strong> <?= htmlspecialchars($order['order_ref']) ?><br>
              <strong>Product:</strong> <?= htmlspecialchars($order['item_name']) ?><br>
              <strong>Quantity:</strong> <?= $order['order_qty'] ?><br>
              <strong>Price per Unit:</strong> ₱<?= number_format($order['item_price'], 2) ?><br>
              <strong>Total:</strong> ₱<?= number_format($order['item_price'] * $order['order_qty'], 2) ?><br>
              <small><strong>Date Ordered:</strong> <?= date("M d, Y", strtotime($order['date_added'])) ?></small>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="no-orders text-center">
          <img src="https://cdn-icons-png.flaticon.com/512/2748/2748558.png" alt="No Orders" />
          <p>No orders to receive</p>
        </div>
      <?php endif; ?>
    </div>

    <!-- Completed -->
    <div id="completed" class="tab-content">
      <?php if ($orders_completed->num_rows > 0): ?>
        <?php while ($order = $orders_completed->fetch_assoc()): ?>
          <div class="order-card">
            <img src="../photos/<?= htmlspecialchars($order['item_photo']) ?>" alt="<?= htmlspecialchars($order['item_name']) ?>">
            <div>
              <strong>Order Ref:</strong> <?= htmlspecialchars($order['order_ref']) ?><br>
              <strong>Product:</strong> <?= htmlspecialchars($order['item_name']) ?><br>
              <strong>Quantity:</strong> <?= $order['order_qty'] ?><br>
              <strong>Price per Unit:</strong> ₱<?= number_format($order['item_price'], 2) ?><br>
              <strong>Total:</strong> ₱<?= number_format($order['item_price'] * $order['order_qty'], 2) ?><br>
              <small><strong>Date Ordered:</strong> <?= date("M d, Y", strtotime($order['date_added'])) ?></small>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="no-orders text-center">
          <img src="https://cdn-icons-png.flaticon.com/512/2748/2748558.png" alt="No Orders" />
          <p>No completed orders</p>
        </div>
      <?php endif; ?>
    </div>

  </div>
</div>

<script>
  const tabButtons = document.querySelectorAll(".tab-btn");
  const tabContents = document.querySelectorAll(".tab-content");

  tabButtons.forEach(btn => {
    btn.addEventListener("click", () => {
      tabButtons.forEach(b => b.classList.remove("active"));
      tabContents.forEach(tc => tc.classList.remove("active"));

      btn.classList.add("active");
      document.getElementById(btn.dataset.tab).classList.add("active");
    });
  });
</script>

</body>
</html>
