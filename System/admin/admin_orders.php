<?php
session_start();
include '../db.php';

// Handle order status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_ref'], $_POST['new_status'])) {
    $stmt = $conn->prepare("UPDATE orders SET order_phase = ?, date_updated = NOW() WHERE order_ref = ?");
    $stmt->bind_param("ss", $_POST['new_status'], $_POST['order_ref']);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_orders.php");
    exit();
}

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Orders - ORIGATO Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/admin_orders.css" />
</head>
<body>
<header>
  <div class="logo">
    <img src="../photos/logo.jpeg" alt="Origato Logo">
    <span>ORIGATO</span>
  </div>
  <div class="user-info">
    <div class="user-icon"><i class="bi bi-person-circle"></i></div>
    <div><b>YaBoiAdmin</b><br><small>(Admin)</small></div>
  </div>
</header>

<div class="sidebar">
  <div class="logo">ORIGATO</div>
  <a href="admin_products.php"><i class="bi bi-basket"></i> Products</a>
  <a href="admin_orders.php" class="active"><i class="bi bi-truck"></i> Orders</a>
  <a href="admin_homepage.php"><i class="bi bi-clipboard-data"></i> Reports</a>
  <a href="?logout=true"><i class="bi bi-box-arrow-left"></i> Log Out</a>
</div>

<div class="main">
  <h3>Orders</h3>
  <div class="orders-list">
    <h5 style="font-size: 1.5rem; font-weight: bold;">Order List</h5>

    <?php
    $sql = "
      SELECT 
        o.order_ref,
        o.user_info_id,
        u.user_name,
        o.order_phase,
        o.date_added,
        p.prdct_dsgn_id,
        p.item_name,
        p.item_brand,
        p.item_type,
        p.item_price,
        p.item_description,
        p.item_photo,
        o.order_qty
      FROM orders o
      JOIN user_info u ON o.user_info_id = u.user_info_id
      JOIN product_design p ON o.prdct_dsgn_id = p.prdct_dsgn_id
      ORDER BY o.date_added DESC
    ";

    $result = mysqli_query($conn, $sql);

    $grouped_orders = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $ref = $row['order_ref'];
        if (!isset($grouped_orders[$ref])) {
            $grouped_orders[$ref] = [
                'user_info_id' => $row['user_info_id'],
                'customer_name' => $row['user_name'],
                'order_phase' => $row['order_phase'],
                'date_added' => $row['date_added'],
                'products' => []
            ];
        }
        $grouped_orders[$ref]['products'][] = [
            'item_name' => $row['item_name'],
            'item_brand' => $row['item_brand'],
            'item_type' => $row['item_type'],
            'item_price' => $row['item_price'],
            'item_description' => $row['item_description'],
            'item_photo' => $row['item_photo'],
            'quantity' => $row['order_qty']
        ];
    }
    ?>

    <?php foreach ($grouped_orders as $order_ref => $order_data): ?>
    <div class="card mb-4">
      <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <div>
          <strong>Order Ref:</strong> <?= htmlspecialchars($order_ref) ?> | 
          <strong>Customer:</strong> <?= htmlspecialchars($order_data['customer_name']) ?> | 
          <strong>Date:</strong> <?= htmlspecialchars($order_data['date_added']) ?>
        </div>
        <form method="post" class="d-flex align-items-center">
          <input type="hidden" name="order_ref" value="<?= htmlspecialchars($order_ref) ?>">
          <select name="new_status" class="form-select form-select-sm me-2">
            <option value="Pending" <?= $order_data['order_phase'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Shipping" <?= $order_data['order_phase'] == 'Shipping' ? 'selected' : '' ?>>Shipping</option>
            <option value="Completed" <?= $order_data['order_phase'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
          </select>
          <button type="submit" class="btn btn-light btn-sm">Update</button>
        </form>
      </div>
      <div class="card-body">
        <table class="table table-bordered">
          <thead class="table-light">
            <tr>
              <th>Photo</th>
              <th>Name</th>
              <th>Category</th>
              <th>Brand</th>
              <th>Qty</th>
              <th>Price</th>
              <th>Total</th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody>
            <?php 
              $order_total = 0; 
              foreach ($order_data['products'] as $product): 
              $total = $product['item_price'] * $product['quantity'];
              $order_total += $total;
            ?>
              <tr>
                <td><img src="../photos/<?= htmlspecialchars($product['item_photo']) ?>" alt="Photo" width="50"></td>
                <td><?= htmlspecialchars($product['item_name']) ?></td>
                <td><?= htmlspecialchars($product['item_type']) ?></td>
                <td><?= htmlspecialchars($product['item_brand']) ?></td>
                <td><?= htmlspecialchars($product['quantity']) ?></td>
                <td>₱<?= number_format($product['item_price'], 2) ?></td>
                <td>₱<?= number_format($total, 2) ?></td>
                <td><?= htmlspecialchars($product['item_description']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <div class="text-end fw-bold">Order Total: ₱<?= number_format($order_total, 2) ?></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

</body>
</html>
