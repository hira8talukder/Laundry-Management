<?php

include("auth.php");

$host = "localhost";
$dbname = "smart_laundry";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $userId = $_SESSION['user_id'];
    $firstName = $_SESSION['first_name'] ?? 'N/A';
    $lastName = $_SESSION['last_name'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = :userId ORDER BY order_date DESC");
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User Order History - Smart Laundry</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <style>
    /* (Use the full CSS you shared in userorderhistory.html here...) */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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

    nav a {
      margin-left: 20px;
      text-decoration: none;
      color: #000;
      font-weight: 500;
    }

    nav a:hover {
      color: #039855;
    }

    body {
      background: url('https://images.unsplash.com/photo-1581579185169-53e13c7e7b08') no-repeat center center fixed;
      background-size: cover;
      color: #333;
      display: flex;
      flex-direction: column;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 40px;
      background-color: #ffffff;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .navbar .logo {
      font-weight: 600;
      font-size: 22px;
    }

    .navbar .nav-links a {
      margin-left: 20px;
      text-decoration: none;
      color: #333;
      font-weight: 500;
      transition: color 0.3s;
    }

    .navbar .nav-links a:hover {
      color: #3498db;
    }

    .dashboard-container {
      display: flex;
      flex: 1;
    }

    .sidebar {
      width: 220px;
      background-color: #2c3e50;
      padding-top: 30px;
      display: flex;
      flex-direction: column;
      color: white;
      min-height: 100vh;
    }

    .sidebar a {
      padding: 15px 30px;
      color: white;
      text-decoration: none;
      font-size: 16px;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: background 0.3s;
    }

    .sidebar a:hover {
      background-color: #34495e;
    }

    .main-content {
      flex: 1;
      padding: 40px;
      background-color: rgba(255,255,255,0.9);
    }

    .main-content h1 {
      margin-bottom: 30px;
      text-align: left;
      color: #8540cf; 
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #fff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 16px 20px;
      text-align: left;
    }

    th {
      background-color: #2c3e50;
      color: white;
    }

    tr:nth-child(even) {
      background-color: #f4f6f8;
    }

    tr:hover {
      background-color: #ecf0f1;
    }

    @media (max-width: 768px) {
      .dashboard-container {
        flex-direction: column;
      }

      .sidebar {
        width: 100%;
        flex-direction: row;
        overflow-x: auto;
      }

      .sidebar a {
        flex: 1;
        justify-content: center;
      }
    }
    footer {
      background-color: #f8f8f8;
      text-align: center;
      padding: 20px;
      border-top: 1px solid #ddd;
    }
    .status, .payment {
  padding: 6px 12px;
  border-radius: 20px;
  font-weight: bold;
  font-size: 0.85rem;
  display: inline-block;
  text-align: center;
  white-space: nowrap;
}

.status.complete {
  color: green;
  border: 2px solid green;
  background-color: #e8f5e9;
}

.status.in-progress {
  color: #fbc02d;
  background-color: #fff9c4;
  border: 2px solid #fdd835;
}

.status.pending {
  color: #b71c1c;
  background-color: #ffebee;
  border: 2px solid #ef5350;
}

.payment.paid {
  color: white;
  background-color: #4caf50;
  border-radius: 12px;
  padding: 5px 12px;
}
  </style>
</head>
<body>
  <header>
    <h1>🮺 Smart Laundry Management System</h1>
    <nav>
      <a href="#">Pricing</a>
      <a href="#">Blog</a>
      <a href="#contact">Contact Us</a>
    </nav>
  </header>
  <div class="dashboard-container">
    <div class="sidebar">
      <a href="userdashboard.html"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
      <a href="profile.html"><i class="fas fa-user"></i> Profile</a>
      <a href="placeorder.html"><i class="fas fa-shopping-cart"></i> Place Order</a>
      <a href="userorderhistory.php"><i class="fas fa-history"></i> Order History</a>
      <a href="userreviews.html"><i class="fa-solid fa-comments"></i> Reviews</a>
      <a href="Usersettings.html"><i class="fas fa-cogs"></i> Settings</a>
      <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Log Out</a>
    </div>

    <div class="main-content">
      <h1><i class="fas fa-history" style="margin-right: 10px;"></i>My Order History</h1>
      <table>
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Name</th>
            <th>Date</th>
            <th>Status</th>
            <th>Items</th>
            <th>Total</th>
            <th>Payment</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
              <tr>
                <td>#<?= htmlspecialchars($order['order_id']) ?></td>
                <td><?= htmlspecialchars("$firstName $lastName") ?></td>
                <td><?= htmlspecialchars($order['order_date']) ?></td>
                <td>
                  <?php
                    $status = strtolower($order['status'] ?? 'pending');
                    $statusClass = 'pending';
                    if ($status === 'completed') $statusClass = 'complete';
                    elseif ($status === 'in progress') $statusClass = 'in-progress';
                  ?>
                  <span class="status <?= $statusClass ?>"><?= ucfirst($status) ?></span>
                </td>
                <td><?= htmlspecialchars($order['product_type']) ?></td>
                <td>$<?= number_format($order['price'], 2) ?></td>
                <td><span class="payment paid">Paid</span></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="7">No orders found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <footer class="main-footer">
    <p>&copy; 2025 Smart Laundry Management System. All rights reserved.</p>
  </footer>
</body>
</html>
