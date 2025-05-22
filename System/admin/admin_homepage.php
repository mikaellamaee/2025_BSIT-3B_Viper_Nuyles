<?php
session_start();
include '../db.php';
if (isset($_GET['logout'])) {
  session_unset();
  session_destroy();
  header("Location: ../login.php");
  exit();
}

$user_id = $_SESSION['user_info_id'];

if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
}

// Fetch admin profile
try {
    $select_profile = $conn->prepare("SELECT * FROM `user_info` WHERE user_info_id = ?");
    $select_profile->bind_param("i", $user_id);
    $select_profile->execute();
    $result = $select_profile->get_result();

    if ($fetch_profile = $result->fetch_assoc()) {
        // Profile fetched
    } else {
        echo "Profile not found.";
    }
    $select_profile->close();
} catch (mysqli_sql_exception $e) {
    echo "Error: " . $e->getMessage();
}

// Visits in last 30 days
$visits = 0;
$select_login = $conn->prepare("SELECT COUNT(*) AS total FROM `login_logs` WHERE `date_login` > NOW() - INTERVAL 30 DAY");
$select_login->execute();
$result = $select_login->get_result();
if ($row = $result->fetch_assoc()) {
    $visits = $row['total'];
}

// Total items/products
$items = 0;
$select_items = $conn->prepare("SELECT COUNT(*) AS total FROM `product_design`");
$select_items->execute();
$items_result = $select_items->get_result();
if ($row = $items_result->fetch_assoc()) {
    $items = $row['total'];
}

// Total orders and revenue
$total_orders = 0;
$total_revenue = 0.00;
$revenue_query = $conn->prepare("
    SELECT COUNT(*) AS total_orders, SUM(o.order_qty * p.item_price) AS total_revenue
    FROM orders o
    JOIN product_design p ON o.prdct_dsgn_id = p.prdct_dsgn_id
");
$revenue_query->execute();
$revenue_result = $revenue_query->get_result();
if ($row = $revenue_result->fetch_assoc()) {
    $total_orders = $row['total_orders'];
    $total_revenue = $row['total_revenue'] ?? 0;
}

// Monthly sales for bar chart
$monthly_labels = [];
$monthly_totals = [];
$monthly_query = $conn->query("SELECT DATE_FORMAT(o.date_added, '%b') AS month, SUM(o.order_qty * p.item_price) AS revenue
    FROM orders o
    JOIN product_design p ON o.prdct_dsgn_id = p.prdct_dsgn_id
    GROUP BY MONTH(o.date_added), month
    ORDER BY MONTH(o.date_added)");
while ($row = $monthly_query->fetch_assoc()) {
    $monthly_labels[] = $row['month'];
    $monthly_totals[] = $row['revenue'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - ORIGATO</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="../css/admin_home.css" />
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #c6d6fc;
    }
    .charts {
      display: flex;
      gap: 20px;
      margin-top: 30px;
      flex-wrap: wrap;
    }
    .chart-box {
      background: white;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      flex: 1 1 45%;
    }
  </style>
</head>
<body>
<header>
  <div class="logo">
    <img src="../photos/logo.jpeg" alt="Origato Logo">
    <span>ORIGATO</span>
  </div>
  <div class="header-icons">
    <div class="user-info">
      <div class="user-icon"><i class="bi bi-person-circle"></i></div>
      <div><b><?= $fetch_profile['user_name'] ?></b><br /><small>(Admin)</small></div>
    </div>
  </div>
</header>

<div class="sidebar">
  <div class="logo">ORIGATO</div>
  <a href="admin_products.php"><i class="bi bi-basket"></i> Products</a>
  <a href="admin_orders.php"><i class="bi bi-truck"></i> Orders</a>
  <a href="#" class="active"><i class="bi bi-clipboard-data"></i> Reports</a>
  <a href="admin_orders.php?logout=true"><i class="bi bi-box-arrow-left"></i> Log Out</a>
</div>

<div class="main">
  <h3>Welcome to Dashboard, <b><?= $fetch_profile['user_name'] ?></b>!</h3>
  <div class="stat-cards">
    <div class="stat-card">
      <h6 style="color: green;">Visits</h6>
      <h3><?= $visits ?></h3>
    </div>
    <div class="stat-card">
      <h6 style="color: orange;">Revenue</h6>
      <h3>₱<?= number_format($total_revenue, 2) ?></h3>
    </div>
    <div class="stat-card">
      <h6 style="color: skyblue;">Orders</h6>
      <h3><?= $total_orders ?></h3>
    </div>
    <div class="stat-card">
      <h6 style="color: red;">Items</h6>
      <h3><?= $items ?></h3>
    </div>
  </div>

  <div class="charts">
    <div class="chart-box">
      <h5>Summary Pie Chart</h5>
      <canvas id="pieChart"></canvas>
    </div>
    <div class="chart-box">
      <h5>Monthly Sales</h5>
      <canvas id="barChart"></canvas>
    </div>
  </div>
</div>

<script>
  const ctxPie = document.getElementById('pieChart').getContext('2d');
  new Chart(ctxPie, {
    type: 'pie',
    data: {
      labels: ['Visits', 'Orders', 'Items'],
      datasets: [{
        data: [<?= $visits ?>, <?= $total_orders ?>, <?= $items ?>],
        backgroundColor: ['green', 'skyblue', 'red']
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { position: 'top' } }
    }
  });

  const ctxBar = document.getElementById('barChart').getContext('2d');
  new Chart(ctxBar, {
    type: 'bar',
    data: {
      labels: <?= json_encode($monthly_labels) ?>,
      datasets: [{
        label: 'Revenue (₱)',
        data: <?= json_encode($monthly_totals) ?>,
        backgroundColor: 'rgba(255, 159, 64, 0.7)',
        borderColor: 'orange',
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: { beginAtZero: true }
      },
      responsive: true
    }
  });
</script>

</body>
</html>
<?php $conn->close(); ?>
