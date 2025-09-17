<?php

include("auth.php");

$host = "localhost";
$dbname = "smart_laundry";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT o.*, s.first_name, s.last_name FROM orders o JOIN sign_up s ON o.user_id = s.user_id ORDER BY o.order_date DESC");
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Order History - Smart Laundry Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    body {
      margin: 0;
      display: flex;
      flex-direction: column;
      height: 100vh;
      background-color: #f5f7fa;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px;
      border-bottom: 1px solid #ddd;
    }

    header h1 {
      font-size: 16px;
    }

    header nav a {
      margin-left: 20px;
      text-decoration: none;
      color: #2c3e50;
      font-weight: 500;
    }

    nav a {
      margin-left: 28px;
      text-decoration: none;
      color: #2c3e50;
      font-weight: 500;
      font-size: 15px;
    }

    nav a:hover {
      color: #039855;
    }

    .dashboard-container {
      display: flex;
      flex: 1;
      overflow: hidden;
    }

    .sidebar {
      width: 220px;
      background-color: #2c3e50;
      padding-top: 30px;
      display: flex;
      flex-direction: column;
      color: white;
    }

    .sidebar a {
      padding: 15px 30px;
      color: inherit;
      text-decoration: none;
      font-size: 15px;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: background 0.3s;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background-color: #34495e;
    }

    .main-content {
      flex: 1;
      padding: 40px 56px;
      overflow-y: auto;
      background: #fff;
      display: flex;
      flex-direction: column;
    }

    .main-heading h1 {
      font-size: 28px;
      font-weight: bold;
      margin-bottom: 20px;
      color: #8540cf;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    /* Search Box */
    .search-container {
      margin-bottom: 20px;
      max-width: 400px;
    }

    .search-container input {
      width: 100%;
      padding: 10px 15px;
      border-radius: 8px;
      border: 1.5px solid #ccc;
      font-size: 16px;
      transition: border-color 0.3s ease;
    }

    .search-container input:focus {
      outline: none;
      border-color: #486481;
      box-shadow: 0 0 8px #486481;
    }

    /* Table Styles */
    table {
      width: 100%;
      border-collapse: collapse;
      box-shadow: 0 2px 15px rgba(0,0,0,0.1);
      border-radius: 12px;
      overflow: hidden;
      background: white;
    }

    thead {
      background-color: #2c3e50;
      color: white;
      user-select: none;
    }

    thead th {
      padding: 16px 20px;
      text-align: left;
      font-weight: 600;
      font-size: 16px;
      cursor: pointer;
      position: relative;
      transition: background-color 0.3s ease;
    }

    thead th:hover {
      background-color: #6b2fb5;
    }

    thead th .sort-arrow {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 12px;
      opacity: 0.5;
      transition: opacity 0.3s;
    }

    thead th.sort-asc .sort-arrow {
      opacity: 1;
      transform: translateY(-50%) rotate(180deg);
    }

    thead th.sort-desc .sort-arrow {
      opacity: 1;
    }

    tbody tr {
      border-bottom: 1px solid #e0e0e0;
      transition: background-color 0.3s ease;
    }

    tbody tr:hover {
      background-color: #f9f0ff;
    }

    tbody td {
      padding: 14px 20px;
      font-size: 14px;
      color: #555;
    }

    tbody tr:last-child {
      border-bottom: none;
    }

    .status {
      padding: 6px 14px;
      border-radius: 20px;
      font-weight: 600;
      font-size: 13px;
      text-align: center;
      width: max-content;
    }

    .status.completed {
      background-color: #27ae60;
      color: white;
    }

    .status.pending {
      background-color: #f39c12;
      color: white;
    }

    .status.cancelled {
      background-color: #e74c3c;
      color: white;
    }

    /* Pagination */
    .pagination {
      margin-top: 25px;
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      user-select: none;
    }

    .pagination button {
      background-color: #8540cf;
      border: none;
      color: white;
      padding: 8px 14px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 600;
      font-size: 14px;
      transition: background-color 0.3s ease;
    }

    .pagination button:disabled {
      background-color: #c9a9e4;
      cursor: not-allowed;
    }

    .pagination button:hover:not(:disabled) {
      background-color: #6b2fb5;
    }

    /* No results message */
    .no-results {
      text-align: center;
      font-size: 18px;
      color: #8540cf;
      padding: 40px 0;
    }

    /* Responsive */
    @media (max-width: 900px) {
      .sidebar {
        display: none;
      }

      .main-content {
        padding: 24px 20px;
      }

      table, thead, tbody, th, td, tr {
        display: block;
      }

      thead tr {
        position: absolute;
        top: -9999px;
        left: -9999px;
      }

      tbody tr {
        margin-bottom: 20px;
        border-bottom: 2px solid #8540cf;
        padding: 12px;
        background-color: #f9f0ff;
        border-radius: 8px;
      }

      tbody td {
        padding-left: 50%;
        position: relative;
        text-align: right;
        font-size: 14px;
      }

      tbody td::before {
        position: absolute;
        left: 16px;
        top: 14px;
        white-space: nowrap;
        font-weight: 600;
        content: attr(data-label);
        color: #8540cf;
        text-align: left;
      }
    }

    footer {
      background: #ffffff;
      padding: 20px 0;
      text-align: center;
      border-top: 1px solid #e1e4e8;
      font-size: 14px;
      color: #888;
      margin-top: auto;
    }
  </style>
</head>
<body>
  <header>
    <h1>🧺 Smart Laundry Management System</h1>
    <nav>
      <a href="pricing.html">Pricing</a>
      <a href="blog.html">Blog</a>
      <a href="contact.html">Contact Us</a>
    </nav>
  </header>

  <div class="dashboard-container">
    <aside class="sidebar">
      <a href="../front/adminDashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
      <a href="../backend/adminprofile.php"><i class="fas fa-user"></i> Profile</a>
      <a href="../front/userlist (1).html"><i class="fa-solid fa-users"></i> Users List</a>
      <a href="../backend/adminorderhistory.php"><i class="fas fa-history"></i> Order History</a>
      <a href="status.html"><i class="fa-solid fa-cash-register"></i> Payment History</a>
      <a href="../front/adminreviews.html"><i class="fa-solid fa-comments"></i> Reviews</a>
      <a href="settingsadmin.html"><i class="fas fa-cogs"></i> Settings</a>
      <a href="../backend/logout.php"><i class="fas fa-sign-out-alt"></i> Log Out</a>
    </aside>

    <main class="main-content">
      <div class="main-heading">
        <h1><i class="fas fa-history"></i> Order History</h1>
      </div>

      <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search by Order ID or Customer Name..." />
      </div>

      <table id="orderTable" aria-label="Order history table">
        <thead>
          <tr>
            <th data-key="orderId" tabindex="0">Order ID <span class="sort-arrow">&#9660;</span></th>
            <th data-key="date" tabindex="0">Date <span class="sort-arrow">&#9660;</span></th>
            <th data-key="customer" tabindex="0">Customer Name <span class="sort-arrow">&#9660;</span></th>
            <th data-key="service" tabindex="0">Service <span class="sort-arrow">&#9660;</span></th>
            <th data-key="amount" tabindex="0">Total Amount <span class="sort-arrow">&#9660;</span></th>
            <th data-key="status" tabindex="0">Status <span class="sort-arrow">&#9660;</span></th>
          </tr>
        </thead>
        <tbody>
          <!-- Data rows populated by JS -->
          <tbody>
<?php if (!empty($orders)): ?>
  <?php foreach ($orders as $order): ?>
    <tr>
      <td data-label="Order ID">#<?= htmlspecialchars($order['order_id']) ?></td>
      <td data-label="Date"><?= htmlspecialchars($order['order_date']) ?></td>
      <td data-label="Customer Name"><?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?></td>
      <td data-label="Service"><?= htmlspecialchars($order['product_type']) ?></td>
      <td data-label="Total Amount">$<?= number_format($order['price'], 2) ?></td>
      <td data-label="Status">
        <span class="status <?= strtolower($order['status']) ?>">
          <?= htmlspecialchars($order['status']) ?>
        </span>
      </td>
    </tr>
  <?php endforeach; ?>
<?php else: ?>
  <tr><td colspan="6" class="no-results">No orders found.</td></tr>
<?php endif; ?>
</tbody>
 
        </tbody>
      </table>

      <div class="no-results" style="display:none;">No matching orders found.</div>

      <div class="pagination">
        <button id="prevPage" disabled>&laquo; Prev</button>
        <button id="nextPage" disabled>Next &raquo;</button>
      </div>
    </main>
  </div>

  <footer>
    &copy; 2025 Smart Laundry Management System. All rights reserved.
  </footer>

  <script>
   
  </script>
</body>
</html>



