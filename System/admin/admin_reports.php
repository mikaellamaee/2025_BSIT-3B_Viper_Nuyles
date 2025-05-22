<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Reports Dashboard - ORIGATO</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #a6b0ff; /* similar to your blue */
    }
    header {
      background-color: white;
      padding: 10px 30px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: bold;
      font-size: 24px;
      color: #1c1c1c;
      cursor: pointer;
    }
    .logo img {
      height: 40px;
    }
    .search-bar {
      flex-grow: 1;
      max-width: 500px;
      margin: 0 20px;
      display: flex;
    }
    .search-bar input {
      width: 100%;
      padding: 10px;
      font-size: 16px;
      border-radius: 5px 0 0 5px;
      border: none;
      outline: none;
    }
    .search-bar button {
      background-color: #d90479;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 0 5px 5px 0;
      cursor: pointer;
      font-size: 1.2rem;
      display: flex;
      align-items: center;
      justify-content: center;
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
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
    }
    .user-info {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .user-icon {
      width: 35px;
      height: 35px;
      background-color: #e0e0e0;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.3rem;
      color: #666;
    }

    .sidebar {
      background-color: white;
      width: 200px;
      height: 100vh;
      position: fixed;
      padding: 30px 20px;
    }
    .sidebar .logo {
      font-weight: bold;
      font-size: 22px;
      margin-bottom: 30px;
    }
    .sidebar a {
      text-decoration: none;
      display: block;
      padding: 10px;
      margin-bottom: 10px;
      color: black;
      font-weight: 500;
      border-radius: 8px;
    }
    .sidebar a.active {
      background-color: #5b7cff;
      color: white;
    }

    .main {
      margin-left: 220px;
      padding: 30px;
      color: #111;
    }

    h3 {
      font-weight: 700;
      margin-bottom: 20px;
    }

    /* Reports cards container */
    .reports-cards {
      display: flex;
      gap: 20px;
      margin-bottom: 30px;
      flex-wrap: wrap;
    }

    .card-report {
      background: white;
      border-radius: 15px;
      padding: 20px 30px;
      width: 250px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      gap: 10px;
    }

    .card-report .top {
      display: flex;
      align-items: center;
      gap: 15px;
      font-size: 28px;
      font-weight: 700;
    }

    .card-report .top i {
      font-size: 38px;
      flex-shrink: 0;
    }

    .card-report .number {
      font-weight: 900;
      font-size: 32px;
    }

    .card-report .title {
      font-weight: 600;
      font-size: 16px;
      margin-top: -5px;
    }

    .card-report .bottom {
      display: flex;
      align-items: center;
      gap: 8px;
      color: #4caf50;
      font-weight: 600;
      font-size: 14px;
    }
    /* Colors for each card */
    .happy-customers .top i {
      color: #f6b84f;
    }
    .happy-customers .title {
      color: #f6b84f;
    }

    .new-customers .top i {
      color: #009973;
    }
    .new-customers .title {
      color: #009973;
    }

    .new-registers .top i {
      color: #4367d6;
    }
    .new-registers .title {
      color: #4367d6;
    }

    /* Chart containers */
    .charts-container {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
    }

    .chart-box {
      background: white;
      border-radius: 15px;
      padding: 20px 25px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      flex: 1 1 400px;
      min-width: 300px;
    }

    .chart-box h5 {
      text-align: center;
      margin-bottom: 15px;
      font-weight: 700;
      color: #222;
    }

  </style>
</head>
<body>

<header>
  <div class="logo">
    <img src="photos/logo.jpeg" alt="Origato Logo" />
    <span>ORIGATO</span>
  </div>
  <div class="search-bar">
    <input type="text" placeholder="search for products" />
    <button><i class="bi bi-search"></i></button>
  </div>
  <div class="header-icons">
    <button title="Messages"><i class="bi bi-chat-dots"></i></button>
    <button title="Notifications"><i class="bi bi-bell"></i></button>
    <div class="user-info" title="YaBoiAdmin (Admin)">
      <div class="user-icon">
        <i class="bi bi-person-circle"></i>
      </div>
      <div><b>YaBoiAdmin</b><br /><small>(Admin)</small></div>
    </div>
  </div>
</header>

<div class="sidebar">
  <div class="logo">ORIGATO</div>
  <a href="admin_products.php"><i class="bi bi-basket"></i> Products</a>
  <a href="admin_orders.php"><i class="bi bi-truck"></i> Orders</a>
  <a href="admin_reports.php" class="active"><i class="bi bi-clipboard-data"></i> Reports</a>
</div>

<div class="main">
  <h3>Reports</h3>

  <div class="reports-cards">
    <div class="card-report happy-customers">
      <div class="top">
        <i class="bi bi-emoji-smile"></i>
        <div class="number">64k</div>
      </div>
      <div class="title">Happy Customers</div>
      <div class="bottom">
        <i class="bi bi-arrow-up-right"></i> +50%
      </div>
      <canvas id="happyCustomersSparkline" height="40"></canvas>
    </div>

    <div class="card-report new-customers">
      <div class="top">
        <i class="bi bi-people-fill"></i>
        <div class="number">5k</div>
      </div>
      <div class="title">New Customers</div>
      <div class="bottom">
        <i class="bi bi-arrow-up-right"></i> +40%
      </div>
      <canvas id="newCustomersSparkline" height="40"></canvas>
    </div>

    <div class="card-report new-registers">
      <div class="top">
        <i class="bi bi-r-circle"></i>
        <div class="number">800</div>
      </div>
      <div class="title">New Registers</div>
      <div class="bottom">
        <i class="bi bi-arrow-up-right"></i> +30%
      </div>
      <canvas id="newRegistersSparkline" height="40"></canvas>
    </div>
  </div>

  <div class="charts-container">
    <div class="chart-box">
      <h5>Sales Overview</h5>
      <canvas id="salesOverviewChart" height="200"></canvas>
    </div>
    
    <div class="chart-box">
      <h5>Leads Overview</h5>
      <canvas id="leadsOverviewChart" height="200"></canvas>
      <div style="display: flex; justify-content: center; flex-wrap: wrap; gap: 15px; margin-top: 15px; font-size: 14px;">
        <div style="display: flex; align-items: center; gap: 6px; color: #304FFE;"><div style="width: 15px; height: 15px; background:#304FFE; border-radius: 3px;"></div>Facebook</div>
        <div style="display: flex; align-items: center; gap: 6px; color: #29b6f6;"><div style="width: 15px; height: 15px; background:#29b6f6; border-radius: 3px;"></div>Bluesky</div>
        <div style="display: flex; align-items: center; gap: 6px; color: #fbc02d;"><div style="width: 15px; height: 15px; background:#fbc02d; border-radius: 3px;"></div>LinkedIn</div>
        <div style="display: flex; align-items: center; gap: 6px; color: #d81b60;"><div style="width: 15px; height: 15px; background:#d81b60; border-radius: 3px;"></div>Instagram</div>
        <div style="display: flex; align-items: center; gap: 6px; color: #388e3c;"><div style="width: 15px; height: 15px; background:#388e3c; border-radius: 3px;"></div>Twitter</div>
        <div style="display: flex; align-items: center; gap: 6px; color: #e53935;"><div style="width: 15px; height: 15px; background:#e53935; border-radius: 3px;"></div>Threads</div>
      </div>
    </div>
  </div>
  <div style="background: #ccd7ff; padding: 20px; border-radius: 15px; max-width: 1000px; margin-top: 30px;">
  <div style="background: white; border-radius: 15px; padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; font-weight: 700; font-size: 18px; color: #111;">
      <div>Buyer Report</div>
      <div style="font-weight: 400; font-size: 14px; color: #111;">
        Show 
        <select style="border: 1px solid #d90479; border-radius: 6px; padding: 2px 6px; font-weight: 700; color: #d90479; font-size: 14px; margin: 0 6px;" name="entries">
          <option value="5" selected>5</option>
          <option value="10">10</option>
          <option value="15">15</option>
        </select> 
        Entries
      </div>
    </div>
    <div style="overflow-x:auto;">
      <table style="width: 100%; border-collapse: collapse; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 14px; color: #555;">
        <thead>
          <tr style="background-color: #d90479; color: white; text-align: left;">
            <th style="padding: 12px 10px; border-right: 1px solid #d90479;">Order ID</th>
            <th style="padding: 12px 10px; border-right: 1px solid #d90479;">Date of Order</th>
            <th style="padding: 12px 10px; border-right: 1px solid #d90479;">Customer</th>
            <th style="padding: 12px 10px; border-right: 1px solid #d90479;">Prices</th>
            <th style="padding: 12px 10px; border-right: 1px solid #d90479;">Date of Delivery</th>
            <th style="padding: 12px 10px; border-right: 1px solid #d90479;">Status</th>
            <th style="padding: 12px 10px;">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr style="border-bottom: 1px solid #d90479;">
            <td style="padding: 10px 8px; border-right: 1px solid #d90479;">000001</td>
            <td style="padding: 10px 8px; border-right: 1px solid #d90479;">05-07-25</td>
            <td style="padding: 10px 8px; border-right: 1px solid #d90479;">Ezra Dims</td>
            <td style="padding: 10px 8px; border-right: 1px solid #d90479;">₱1000.00</td>
            <td style="padding: 10px 8px; border-right: 1px solid #d90479;">05-14-25</td>
            <td style="padding: 10px 8px; border-right: 1px solid #d90479; color: #3abff8; font-weight: 600; cursor: default;">Processing</td>
            <td style="padding: 10px 8px; white-space: nowrap;">
              <button style="background: #94a3b8; color: white; border: none; border-radius: 8px; padding: 4px 10px; font-size: 12px; cursor: pointer; margin-right: 5px;">View Details</button>
              <button title="Download" style="background: transparent; border: none; color: #3abff8; cursor: pointer; font-size: 16px; vertical-align: middle;">
                <i class="bi bi-download"></i>
              </button>
            </td>
          </tr>
          <tr style="border-bottom: 1px solid #d90479;">
            <td style="padding: 10px 8px; border-right: 1px solid #d90479;">000001</td>
            <td style="padding: 10px 8px; border-right: 1px solid #d90479;">05-07-25</td>
            <td style="padding: 10px 8px; border-right: 1px solid #d90479;">Kelly Arts</td>
            <td style="padding: 10px 8px; border-right: 1px solid #d90479;">₱1000.00</td>
            <td style="padding: 10px 8px; border-right: 1px solid #d90479;">05-14-25</td>
            <td style="padding: 10px 8px; border-right: 1px solid #d90479; color: #fbbf24; font-weight: 600; cursor: default;">Pending</td>
            <td style="padding: 10px 8px; white-space: nowrap;">
              <button style="background: #94a3b8; color: white; border: none; border-radius: 8px; padding: 4px 10px; font-size: 12px; cursor: pointer; margin-right: 5px;">View Details</button>
              <button title="Download" style="background: transparent; border: none; color: #fbbf24; cursor: pointer; font-size: 16px; vertical-align: middle;">
                <i class="bi bi-download"></i>
              </button>
            </td>
          </tr>
          <tr style="border-bottom: 1px solid #d90479;">
            <td style="padding: 10px 8px; border-right: 1px solid #d90479;">000001</td>
            <td style="padding: 10px 8px; border-right: 1px solid #d90479;">05-07-25</td>
            <td style="padding: 10px 8px; border-right: 1px solid #d90479;">ColorCharts</td>
            <td style="padding: 10px 8px; border-right: 1px solid #d90479;">₱1000.00</td>
            <td style="padding: 10px 8px; border-right: 1px solid #d90479;">05-14-25</td>
            <td style="padding: 10px 8px; border-right: 1px solid #d90479; color: #10b981; font-weight: 600; cursor: default;">Delivered</td>
            <td style="padding: 10px 8px; white-space: nowrap;">
              <button style="background: #94a3b8; color: white; border: none; border-radius: 8px; padding: 4px 10px; font-size: 12px; cursor: pointer; margin-right: 5px;">View Details</button>
              <button title="Download" style="background: transparent; border: none; color: #10b981; cursor: pointer; font-size: 16px; vertical-align: middle;">
                <i class="bi bi-download"></i>
              </button>
            </td>
          </tr>
          <tr style="border-bottom: 1px solid #d90479;">
            <td style="padding: 10px 8px; border-right: 1px solid #d90479;">000001</td>
            <td style="padding: 10px 8px; border-right: 1px solid #d90479;">05-07-25</td>
            <td style="padding: 10px 8px; border-right: 1px solid #d90479;">Paints and Prints</td>
            <td style="padding: 10px 8px; border-right: 1px solid #d90479;">₱1000.00</td>
            <td style="padding: 10px 8px; border-right: 1px solid #d90479;">05-14-25</td>
            <td style="padding: 10px 8px; border-right: 1px solid #d90479; color: #ef4444; font-weight: 600; cursor: default;">Cancelled</td>
            <td style="padding: 10px 8px; white-space: nowrap;">
              <button style="background: #94a3b8; color: white; border: none; border-radius: 8px; padding: 4px 10px; font-size: 12px; cursor: pointer; margin-right: 5px;">View Details</button>
              <button title="Download" style="background: transparent; border: none; color: #ef4444; cursor: pointer; font-size: 16px; vertical-align: middle;">
                <i class="bi bi-download"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>

<script>
  // Helper for sparkline chart config
  function sparklineConfig(data, color) {
    return {
      type: 'line',
      data: {
        labels: data.map((_,i)=>i+1),
        datasets: [{
          data,
          borderColor: color,
          backgroundColor: color,
          fill: false,
          tension: 0.3,
          pointRadius: 0,
          borderWidth: 2,
        }]
      },
      options: {
        responsive: false,
        plugins: { legend: { display: false } },
        scales: {
          x: { display: false },
          y: { display: false }
        }
      }
    }
  }

  // Sparkline data from screenshot shape
  const happyCustomersData = [12,16,20,15,22,18,20,23,19,21,22,24,20,19,21,23,25];
  const newCustomersData = [8,12,14,13,15,14,16,18,16,15,17,19,20,18,17,19,21];
  const newRegistersData = [5,7,9,8,10,9,11,13,11,10,12,14,13,12,13,14,15];

  new Chart(document.getElementById('happyCustomersSparkline').getContext('2d'), sparklineConfig(happyCustomersData, '#3a8ee6'));
  new Chart(document.getElementById('newCustomersSparkline').getContext('2d'), sparklineConfig(newCustomersData, '#3a8ee6'));
  new Chart(document.getElementById('newRegistersSparkline').getContext('2d'), sparklineConfig(newRegistersData, '#3a8ee6'));

  // Sales Overview bar chart
  const salesCtx = document.getElementById('salesOverviewChart').getContext('2d');
  const salesOverviewChart = new Chart(salesCtx, {
    type: 'bar',
    data: {
      labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday'],
      datasets: [
        {
          label: 'Dataset 1',
          data: [90, 74, 64, 83, 46, 90, 82, 64, 83, 46],
          backgroundColor: '#FF9900'
        },
        {
          label: 'Dataset 2',
          data: [60, 83, 50, 67, 55, 60, 67, 50, 67, 55],
          backgroundColor: '#4ecdc4'
        },
        {
          label: 'Dataset 3',
          data: [74, 64, 64, 83, 46, 82, 67, 64, 83, 46],
          backgroundColor: '#FF3366'
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'top' },
        title: { display: false }
      },
      scales: {
        y: { beginAtZero: true, max: 100 }
      }
    }
  });

  // Leads Overview pie chart
  const leadsCtx = document.getElementById('leadsOverviewChart').getContext('2d');
  const leadsOverviewChart = new Chart(leadsCtx, {
    type: 'pie',
    data: {
      labels: ['Facebook', 'Bluesky', 'LinkedIn', 'Instagram', 'Twitter', 'Threads'],
      datasets: [{
        label: 'Leads Overview',
        data: [20, 30, 15, 10, 30, 10],
        backgroundColor: [
          '#304FFE',
          '#29b6f6',
          '#fbc02d',
          '#d81b60',
          '#388e3c',
          '#e53935'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },
      }
    }
  });
</script>

</body>
</html>
