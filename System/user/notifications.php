<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Notifications - ORIGATO</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
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
      cursor: pointer;
    }
    .logo img {
      height: 40px;
      user-select: none;
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
    .user-icon {
      width: 35px;
      height: 35px;
      background-color: #e0e0e0;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 1.3rem;
      color: #666;
    }

    .container {
      display: flex;
      padding: 30px;
    }

    .sidebar {
      width: 250px;
      background-color: white;
      border-radius: 25px;
      padding: 30px;
      text-align: center;
      margin-right: 30px;
    }

    .sidebar img {
      width: 90px;
      height: 90px;
      border-radius: 50%;
      margin-bottom: 10px;
    }

    .sidebar h4 {
      margin: 10px 0;
    }

    .sidebar a {
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
      color: black;
      padding: 10px;
      border-radius: 10px;
      margin-top: 10px;
    }

    .sidebar a.active {
      background-color: #d90479;
      color: white;
    }

    .main-content {
      background-color: white;
      border-radius: 25px;
      flex-grow: 1;
      padding: 30px;
      position: relative;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .mark-read {
      position: absolute;
      top: 20px;
      right: 30px;
      font-size: 14px;
      color: #6c8bff;
      cursor: pointer;
    }

    .no-notification {
      text-align: center;
      color: black;
      font-size: 18px;
    }

    .no-notification img {
      width: 80px;
      margin-bottom: 15px;
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
    <button title="Favorites"><i class="bi bi-heart"></i></button>
    <button title="Cart"><i class="bi bi-cart"></i></button>
    <div class="user-icon" title="User Profile"><i class="bi bi-person-circle"></i></div>
  </div>
</header>

<div class="container">
  <div class="sidebar">
    <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="Profile" />
    <h4>NewUserHere</h4>
    <hr />
    <a href="#"><i class="bi bi-person"></i>Account</a>
    <a href="#"><i class="bi bi-bag"></i>My Purchase</a>
    <a href="#" class="active"><i class="bi bi-bell"></i>Notifications</a>
  </div>

  <div class="main-content">
    <span class="mark-read">Mark all as Read</span>
    <div class="no-notification">
      <img src="https://cdn-icons-png.flaticon.com/512/1827/1827392.png" alt="No Notifications" />
      <div>No Notifications</div>
    </div>
  </div>
</div>

</body>
</html>
