
<?php
include("../backend/connection.php");
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../front/login.html");
    exit();
}

// new orders (last 24 hours)
try {
    $stmt = $conn->prepare("SELECT COUNT(*) AS newOrders FROM orders WHERE order_date >= NOW() - INTERVAL 1 DAY");
    $stmt->execute();
    $newOrders = $stmt->fetch(PDO::FETCH_ASSOC)['newOrders'];
} catch (PDOException $e) {
    echo "Error fetching new orders: " . $e->getMessage();
}



 //total pending orders
try {
    $stmt = $conn->prepare("SELECT COUNT(*) AS total_pending_orders FROM orders WHERE status = 'Pending'");
    $stmt->execute();
    $pendingOrders = $stmt->fetch(PDO::FETCH_ASSOC)['total_pending_orders'];
} catch (PDOException $e) {
    echo "Error fetching pending orders: " . $e->getMessage();
}

//total users
try {
    $stmt = $conn->prepare("SELECT COUNT(*) AS total_users FROM sign_up WHERE role = 'user'");
    $stmt->execute();
    $totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];
} catch (PDOException $e) {
    echo "Error fetching user count: " . $e->getMessage();
}

//total completed orders
try {
    $stmt = $conn->prepare("SELECT COUNT(*) AS total_completed_orders FROM orders WHERE status = 'Completed'");
    $stmt->execute();
    $completedOrders = $stmt->fetch(PDO::FETCH_ASSOC)['total_completed_orders'];
} catch (PDOException $e) {
    echo "Error fetching completed orders: " . $e->getMessage();
}

//total reviews
try {
    $stmt = $conn->prepare("SELECT COUNT(*) AS total_reviews FROM reviews");
    $stmt->execute();
    $totalReviews = $stmt->fetch(PDO::FETCH_ASSOC)['total_reviews'];
} catch (PDOException $e) {
    echo "Error fetching reviews: " . $e->getMessage();
}




?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Smart Laundry Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    * {
      box-sizing: border-box;
    }

    .main-heading h1 {
      font-size: 28px;
      font-weight: bold;
      margin-bottom: 30px;
      color: #8540cf;
    }


    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
      flex-direction: column;
      height: 100vh;
      background-color: #f5f7fa;
    }

    /* --------------------------------------------------*
       HEADER
    * --------------------------------------------------*/
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 32px;
      background: #ffffff;
      border-bottom: 1px solid #e1e4e8;
    }

    header h1 {
      font-size: 18px;
      margin: 0;
    }

    nav a {
      margin-left: 28px;
      text-decoration: none;
      color: #2c3e50;
      font-weight: 500;
      transition: color 0.2s;
    }

    nav a:hover {
      color: #039855;
    }

    /* --------------------------------------------------*
       LAYOUT WRAPPER
    * --------------------------------------------------*/
    .dashboard-container {
      display: flex;
      flex: 1;
      overflow: hidden;
    }

    /* --------------------------------------------------*
       SIDEBAR
    * --------------------------------------------------*/
    .sidebar {
      width: 220px;
      background-color: #2c3e50;
      padding-top: 30px;
      display: flex;
      flex-direction: column;
      color: white;
      overflow-y: auto;
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

    .sidebar a:hover {
      background-color: #34495e;
    }

    /* --------------------------------------------------*
       MAIN CONTENT
    * --------------------------------------------------*/
    .main-content {
      flex: 1;
      padding: 40px 56px;
      overflow-y: auto;
    }

    /* --------------------------------------------------*
       STAT CARDS (TOP ROW)
    * --------------------------------------------------*/
    .stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 24px;
      margin-bottom: 36px;
    }

    .stat-card {
      position: relative;
      border-radius: 12px;
      padding: 26px 22px 32px;
      color: #ffffff;
      min-height: 110px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
    }

    .stat-number {
      font-size: 34px;
      font-weight: 700;
      margin: 0 0 4px;
      line-height: 1;
    }

    .stat-label {
      font-size: 15px;
      font-weight: 500;
      margin: 0;
      opacity: 0.9;
    }

    .view-more {
      position: absolute;
      bottom: 12px;
      right: 18px;
      font-size: 12px;
      text-decoration: none;
      color: #ffffff;
      opacity: 0.9;
      transition: opacity 0.2s;
    }

    .view-more:hover {
      opacity: 1;
    }

    /* Color variations */
    .blue {
      background: #3B82F6;
    }

    .green {
      background: #16A34A;
    }

    .orange {
      background: #F59E0B;
    }

    .red {
      background: #DC2626;
    }

    .purple {
      background: #8540cf;
    }

    .pink {
      background: #ec4899;
    }

    .larger-label {
      font-size: 20px;
      text-align: center;
      width: 100%;
    }

    /* --------------------------------------------------*
       CHART PANELS
    * --------------------------------------------------*/
    .charts {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 24px;
    }

    .chart-card {
      background: #ffffff;
      border: 1px solid #e1e4e8;
      border-radius: 12px;
      padding: 22px 26px 30px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .chart-card h3 {
      margin: 0 0 18px;
      font-size: 17px;
      font-weight: 600;
      color: #8540cf;
    }

    .chart-wrapper {
      flex: 1;
      position: relative;
    }

    canvas {
      max-height: 240px;
    }

    /* --------------------------------------------------*
       RESPONSIVE
    * --------------------------------------------------*/
    @media (max-width: 900px) {
      .sidebar {
        display: none;
      }

      .main-content {
        padding: 24px;
      }
    }
  </style>
</head>

<body>
  <!-- =================== HEADER =================== -->
  <header>
    <h1>🧺 Smart Laundry Management System</h1>
    <nav>
      <a href="#">Pricing</a>
      <a href="#">Blog</a>
      <a href="#contact">Contact Us</a>
    </nav>
  </header>

  <div class="dashboard-container">
    <!-- =================== SIDEBAR =================== -->
    <aside class="sidebar">
      <a href="../front/adminDashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
      <a href="../backend/adminprofile.php"><i class="fas fa-user"></i> Profile</a>
<<<<<<< HEAD
      <a href="userlist (1).html"><i class="fa-solid fa-users"></i> Users List</a>
      <a href="../backend/adminorderhistory.php"><i class="fas fa-history"></i> Order History</a>
=======
      <a href="../backend/userlist.php"><i class="fa-solid fa-users"></i> Users List</a>
      <a href="../font/adminorderhistory.php"><i class="fas fa-history"></i> Order History</a>
>>>>>>> 53ff9e8f93376d91e03296c262d074bf14c7f60b
      <a href="status.html"><i class="fa-solid fa-cash-register"></i> Payment History</a>
      <a href="../backend/adminreviews.php"><i class="fa-solid fa-comments"></i> Reviews</a>
      <a href="settingsadmin.html"><i class="fas fa-cogs"></i> Settings</a>
      <a href="../backend/logout.php"><i class="fas fa-sign-out-alt"></i> Log Out</a>

    </aside>

    <!-- =================== MAIN CONTENT =================== -->
    <main class="main-content">
      <div class="main-heading">
        <h1><i class="fas fa-tachometer-alt" style="margin-right: 10px;"></i>Dashboard</h1>
      </div>
      <!-- Top statistics row -->
      <section class="stats">
        <div class="stat-card blue">
         <h2 class="stat-number" id="newOrders"><?php echo $pendingOrders; ?></h2>

          <p class="stat-label">New Orders</p>
          <a href="adminorders.html" class="view-more">View more »</a>
        </div>
        <div class="stat-card green">
          <h2 class="stat-number" id="inProgress"><?php echo $pendingOrders; ?></h2>
          <p class="stat-label">In Progress</p>
          <a href="adminprogress.html" class="view-more">View more »</a>
        </div>
        <div class="stat-card orange">
          <h2 class="stat-number" id="completedOrders"><?php echo $completedOrders; ?></h2>
          <p class="stat-label">Complete</p>
          <a href="admincompleted.html" class="view-more">View more »</a>
        </div>
        <div class="stat-card red">
          <h2 class="stat-number" id="totalCustomers"><?php echo $totalUsers; ?></h2>
          <p class="stat-label">Users</p>
          <a href="userlist (1).html" class="view-more">View more »</a>
        </div>
        <div class="stat-card orange">
          <h2 class="stat-number" id="completedOrders"><?php echo $totalReviews; ?></h2>
          <p class="stat-label">Reviews</p>
          <a href="adminreviews.html" class="view-more">View more »</a>
        </div>
        <div class="stat-card purple">
          <p class="stat-label larger-label">Add Income</p>
          <a href="#" class="view-more">View more »</a>
        </div>
        <div class="stat-card pink">
          <p class="stat-label larger-label">Add Expenditure</p>
          <a href="#" class="view-more">View more »</a>
        </div>
      </section>

      <!-- Charts grid -->
      <section class="charts">
        <!-- Orders This Month -->
        <div class="chart-card">
          <h3>Orders This Month</h3>
          <div class="chart-wrapper">
            <canvas id="ordersThisMonthChart"></canvas>
          </div>
        </div>

        <!-- Income This Year -->
        <div class="chart-card">
          <h3>Income This Year</h3>
          <div class="chart-wrapper">
            <canvas id="incomeThisYearChart"></canvas>
          </div>
        </div>

        <!-- Expenditure This Month -->
        <div class="chart-card">
          <h3>Expenditure This Month</h3>
          <div class="chart-wrapper">
            <canvas id="expenditureThisMonthChart"></canvas>
          </div>
        </div>

        <!-- Profit This Year -->
        <div class="chart-card">
          <h3>Profit This Year</h3>
          <div class="chart-wrapper">
            <canvas id="profitThisYearChart"></canvas>
          </div>
        </div>
      </section>
    </main>
  </div>

  <!-- =================== FOOTER =================== -->
  <footer style="background:#ffffff;text-align:center;padding:20px 0;border-top:1px solid #e1e4e8;">
    <p style="margin:0">&copy; 2025 Smart Laundry Management System. All rights reserved.</p>
  </footer>

  <!-- =================== CHART.JS CONFIG =================== -->
  <script>
  const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                  'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

  const ordersData = [30, 45, 60, 70, 55, 40, 65, 80, 90, 100, 95, 85];
  const incomeData = [5000, 7000, 8000, 9000, 7500, 6000, 8500, 10000, 11000, 12000, 11500, 10500];
  const expenditureData = [2000, 2500, 3000, 3200, 2800, 2400, 3100, 4000, 4200, 4500, 4300, 3900];
  const profitData = incomeData.map((income, i) => income - expenditureData[i]);

  function createLineChart(ctxId, label, data, borderColor, bgColor) {
    const ctx = document.getElementById(ctxId).getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: months,
        datasets: [{
          label: label,
          data: data,
          fill: true,
          borderColor: borderColor,
          backgroundColor: bgColor,
          tension: 0.4,
          pointBackgroundColor: borderColor,
          pointBorderColor: '#fff',
          pointHoverRadius: 6,
          pointRadius: 4,
          pointStyle: 'circle',
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: true,
            labels: {
              color: '#333',
              font: {
                size: 14,
                weight: 'bold'
              }
            }
          },
          tooltip: {
            mode: 'index',
            intersect: false,
            backgroundColor: '#333',
            titleColor: '#fff',
            bodyColor: '#fff',
            borderColor: borderColor,
            borderWidth: 1
          }
        },
        scales: {
          x: {
            title: {
              display: true,
              text: 'Month',
              color: '#666',
              font: { weight: 'bold' }
            },
            ticks: {
              color: '#555'
            },
            grid: {
              display: false
            }
          },
          y: {
            title: {
              display: true,
              text: label,
              color: '#666',
              font: { weight: 'bold' }
            },
            ticks: {
              color: '#555'
            },
            grid: {
              color: '#eee'
            }
          }
        }
      }
    });
  }

  // Call charts with prettier colors
  createLineChart('ordersThisMonthChart', 'Orders', ordersData, '#3e95cd', 'rgba(62, 149, 205, 0.2)');
  createLineChart('incomeThisYearChart', 'Income', incomeData, '#4caf50', 'rgba(76, 175, 80, 0.2)');
  createLineChart('expenditureThisMonthChart', 'Expenditure', expenditureData, '#f44336', 'rgba(244, 67, 54, 0.2)');
  createLineChart('profitThisYearChart', 'Profit', profitData, '#ff9800', 'rgba(255, 152, 0, 0.2)');
</script>
</body>

</html>