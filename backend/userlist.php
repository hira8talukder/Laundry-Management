<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Users List - Smart Laundry Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    /* [CSS code remains unchanged — already well-styled!] */
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f5f7fa;
      display: flex;
      flex-direction: column;
      height: 100vh;
    }

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
      color: #2c3e50;
      font-weight: 600;
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
    }
    .main-heading h1 {
      font-size: 28px;
      font-weight: bold;
      margin-bottom: 30px;
      color: #8540cf;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .search-box {
      max-width: 400px;
      margin-bottom: 30px;
    }
    .search-box input {
      width: 100%;
      padding: 14px 20px;
      border-radius: 30px;
      border: 2px solid #ccc;
      font-size: 16px;
      transition: border-color 0.3s ease;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    .search-box input:focus {
      border-color: #8540cf;
      outline: none;
      box-shadow: 0 0 10px rgba(133,64,207,0.6);
    }

    .users-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit,minmax(300px,1fr));
      gap: 24px;
    }
    .user-card {
      background: white;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(133,64,207,0.12);
      padding: 24px 28px;
      transition: box-shadow 0.3s ease, transform 0.2s ease;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }
    .user-card:hover {
      box-shadow: 0 12px 30px rgba(133,64,207,0.3);
      transform: translateY(-5px);
    }
    .user-name {
      font-size: 22px;
      font-weight: 700;
      color: #5a1ea0;
    }
    .user-info {
      font-size: 15px;
      color: #555;
      word-break: break-word;
    }
    .user-joined {
      font-size: 13px;
      color: #a28fc9;
      font-style: italic;
      margin-top: 12px;
    }
    .status-badge {
      margin-top: 16px;
      padding: 8px 26px;
      border-radius: 40px;
      font-weight: 700;
      font-size: 14px;
      color: white;
      text-shadow: 0 1px 3px rgba(0,0,0,0.2);
      user-select: none;
      align-self: flex-start;
    }
    .status-active {
      background: #2ecc71;
      box-shadow: 0 4px 14px #27ae6011;
    }

    footer {
      background: #ffffff;
      padding: 16px 0;
      text-align: center;
      border-top: 1px solid #e1e4e8;
      font-size: 14px;
      color: #888;
    }

    @media (max-width: 900px) {
      .sidebar {
        display: none;
      }
      .main-content {
        padding: 24px 20px;
      }
    }
  </style>
</head>
<body>

  <!-- Header -->
  <header>
    <h1>🧺 Smart Laundry Management System</h1>
    <nav>
      <a href="#">Home</a>
      <a href="#">Pricing</a>
      <a href="#">Blog</a>
      <a href="#contact">Contact Us</a>
    </nav>
  </header>

  <!-- Dashboard -->
  <div class="dashboard-container">

    <!-- Sidebar -->
    <aside class="sidebar">
      <a href="../front/adminDashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
      <a href="adminprofile.php"><i class="fas fa-user"></i> Profile</a>
      <a href="userlist.html" class="active"><i class="fa-solid fa-users"></i> Users List</a>
      <a href="order_history.html"><i class="fas fa-history"></i> Order History</a>
      <a href="status.html"><i class="fa-solid fa-cash-register"></i> Payment History</a>
      <a href="status.html"><i class="fa-solid fa-list-check"></i> Reviews</a>
      <a href="settings.html"><i class="fas fa-cogs"></i> Settings</a>
      <a href="logout.html"><i class="fas fa-sign-out-alt"></i> Log Out</a>
    </aside>

    <!-- Main content -->
    <main class="main-content">
      <div class="main-heading">
        <h1><i class="fa-solid fa-users"></i> Users List</h1>
      </div>

      <div class="search-box">
        <input type="search" id="searchInput" placeholder="Search users by name, email, or phone..." />
      </div>

      <div class="users-grid" id="usersGrid">
        <!-- Cards will be inserted here by JS -->
      </div>
    </main>
  </div>

  <!-- Footer -->
  <footer>
    &copy; 2025 Smart Laundry Management System. All rights reserved.
  </footer>

  <!-- JS Section -->
  <script>
    async function fetchUsers() {
      try {
        const response = await fetch('../backend/get_user.php');
        const users = await response.json();
        console.log("Fetched users:", users);
        renderUsers(users);
      } catch (error) {
        console.error("Error fetching users:", error);
      }
    }

    function renderUsers(users) {
  const usersGrid = document.getElementById('usersGrid');
  usersGrid.innerHTML = '';

  if (users.length === 0) {
    usersGrid.innerHTML = `<p style="font-size:18px; color:#8540cf; grid-column: 1 / -1; text-align:center;">No users found.</p>`;
    return;
  }

  users.forEach(user => {
    const fullName = `${user.first_name || ''} ${user.last_name || ''}`.trim();
    const email = user.email || 'N/A';
    const phone = user.phone_number ?? 'Not Provided';
    const occupation = user.occupation || 'N/A';
    const address = user.address || 'N/A';
    const joinedDate = new Date(user.created_at).toLocaleDateString(undefined, {
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    });

    const card = document.createElement('div');
    card.className = 'user-card';
    card.innerHTML = `
      <div class="user-name">${fullName}</div>
      <div class="user-info"><i class="fa-solid fa-envelope"></i> ${email}</div>
      <div class="user-info"><i class="fa-solid fa-phone"></i> ${phone}</div>
      <div class="user-info"><i class="fa-solid fa-briefcase"></i> ${occupation}</div>
      <div class="user-info"><i class="fa-solid fa-location-dot"></i> ${address}</div>
      <div class="user-joined">Joined: ${joinedDate}</div>
      <div class="status-badge status-active">Active</div>
    `;
    usersGrid.appendChild(card);
  });
}


    document.getElementById('searchInput').addEventListener('input', function () {
  const query = this.value.toLowerCase();
  const userCards = document.querySelectorAll('.user-card');

  userCards.forEach(card => {
    const text = card.textContent.toLowerCase();
    card.style.display = text.includes(query) ? 'block' : 'none';
  });
});




    fetchUsers();
  </script>
</body>
</html>
