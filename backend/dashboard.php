<?php
include("auth.php");


if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Smart Laundry Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Inter', sans-serif;
    }
    body {
      background: #f9f9f9;
      color: #2e2e2e;
    }
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 2rem;
      background-color: #fff;
      box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    }
    nav a {
      margin: 0 1rem;
      text-decoration: none;
      color: #333;
    }
    .logout-btn {
      background: #00a86b;
      color: #fff;
      padding: 0.5rem 1rem;
      border: none;
      border-radius: 5px;
    }
    .container {
      display: flex;
    }
    aside {
      width: 220px;
      background: #fff;
      padding: 2rem 1rem;
      height: 100vh;
      box-shadow: 1px 0 4px rgba(0,0,0,0.05);
    }
    aside h4 {
      margin-bottom: 1.5rem;
      color: #999;
    }
    aside ul {
      list-style: none;
    }
    aside ul li {
      margin: 1rem 0;
      padding: 0.5rem 1rem;
      border-radius: 5px;
      cursor: pointer;
    }
    aside ul li.active,
    aside ul li:hover {
      background: #f0f0f0;
      font-weight: 600;
    }
    main {
      flex: 1;
      padding: 2rem;
    }
    .overview {
      display: flex;
      gap: 1rem;
      margin-bottom: 2rem;
    }
    .card {
      flex: 1;
      background: #fff;
      padding: 1rem;
      border-radius: 10px;
      box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    }
    .card h3 {
      font-size: 1.5rem;
    }
    .card small {
      color: green;
      font-weight: 600;
    }
    .graph, .revenue-breakdown {
      background: #fff;
      padding: 1rem;
      border-radius: 10px;
      margin-bottom: 2rem;
      box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    }
    .actions {
      display: flex;
      gap: 1rem;
    }
    .action-card {
      flex: 1;
      background: #fff;
      border-radius: 10px;
      overflow: hidden;
      text-align: center;
      box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    }
    .action-card img {
      width: 100%;
      height: 150px;
      object-fit: cover;
    }
    .action-card h4 {
      margin-top: 0.5rem;
    }
    .add-btn {
      background: #00a86b;
      color: #fff;
      padding: 0.75rem 1rem;
      border: none;
      border-radius: 5px;
      margin-bottom: 2rem;
    }
    .main-footer {
      text-align: center;
      padding: 1rem;
      background: #fff;
      box-shadow: 0 -1px 4px rgba(0,0,0,0.05);
      position: fixed;
      bottom: 0;
      width: 100%;
    }
  </style>
</head>
<body>
  <header>
    <h2>Smart Laundry Management System</h2>
    <nav>
      <a href="#">Dashboard</a>
      <a href="#">Orders</a>
      <a href="#">Customers</a>
      <a href="#">Reports</a>
      <a href="#">Settings</a>
      <input type="text" placeholder="Search" style="padding: 0.3rem 0.5rem; margin-left: 1rem;">
      <button class="logout-btn">Logout</button>
    </nav>
  </header>

  <div class="container">
    <aside>
      <h4>Welcome, User</h4>
      <ul>
        <li class="active">Dashboard</li>
        <li>Orders</li>
        <li>Customers</li>
        <li>Reports</li>
        <li>Settings</li>
      </ul>
    </aside>

    <main>
      <h1>Dashboard</h1>
      <p>Overview of your laundry business</p>

      <div class="overview">
        <div class="card">
          <h3>120 Orders</h3>
          <small>+10%</small>
        </div>
        <div class="card">
          <h3>15 Pending</h3>
          <small>+5%</small>
        </div>
        <div class="card">
          <h3>105 Completed</h3>
          <small>+8%</small>
        </div>
        <div class="card">
          <h3>$5,000 Revenue</h3>
          <small>+12%</small>
        </div>
      </div>

      <div class="graph">
        <h3>Orders Over Time</h3>
        <p>Graph placeholder for 30 days stats</p>
      </div>

      <div class="revenue-breakdown">
        <h3>Revenue Breakdown</h3>
        <p>Bar chart placeholder: Laundry, Dry Cleaning, Alterations</p>
      </div>

      <button class="add-btn">Add New Order</button>

      <div class="actions">
        <div class="action-card">
          <img src="https://via.placeholder.com/300x150.png?text=Add+New+Order" alt="Add">
          <h4>Add New Order</h4>
          <p>Quickly create a new order</p>
        </div>
        <div class="action-card">
          <img src="https://via.placeholder.com/300x150.png?text=Manage+Customers" alt="Customers">
          <h4>Manage Customers</h4>
          <p>Handle customer details</p>
        </div>
        <div class="action-card">
          <img src="https://via.placeholder.com/300x150.png?text=View+Reports" alt="Reports">
          <h4>View Reports</h4>
          <p>Analyze business performance</p>
        </div>
      </div>
    </main>
  </div>
    <footer class="main-footer">
    <p>&copy; 2025 Smart Laundry Management System. All rights reserved.</p>
  </footer>

</body>
</html>
