<?php

include("auth.php");
// Database connection
$host = "localhost";
$dbname = "smart_laundry";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        echo "<script>alert('User not logged in');</script>";
    } else {
        $customer_name = $_POST['customer-name'] ?? '';
        $address = $_POST['address'] ?? '';
        $pickup_type = $_POST['pickup-type'] ?? 'Home Pickup';
        $delivery_type = $_POST['delivery-type'] ?? 'Home Delivery';
        $grand_total = $_POST['grand-total'] ?? 0.00;
        $orders_json = $_POST['orders'] ?? '[]';
        $orders = json_decode($orders_json, true);

        $first_order = $orders[0] ?? null;

        if (!$first_order) {
            echo "<script>alert('No order data provided');</script>";
        } else {
            $product_type = $first_order['product'] ?? 'General';
            $product_quantity = $first_order['quantity'] ?? 1;
            $number_of_products = 1;
            $price = $first_order['totalPrice'] ?? $grand_total;
            $max_delivery_date = date('Y-m-d', strtotime('+4 days'));

            try {
                $stmt = $conn->prepare("INSERT INTO orders (
                    user_id, phone_number, product_type, product_quantity, number_of_products,
                    pickup_type, delivery_type, address, price, max_delivery_date
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                $stmt->execute([
                    $user_id,
                    '', // phone_number not provided
                    $product_type,
                    $product_quantity,
                    $number_of_products,
                    $pickup_type,
                    $delivery_type,
                    $address,
                    $price,
                    $max_delivery_date
                ]);

                echo "<script>alert('Order placed successfully');</script>";
            } catch (PDOException $e) {
                echo "<script>alert('Database error: " . $e->getMessage() . "');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Place Order - Smart Laundry</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    body {
      background: url('https://images.unsplash.com/photo-1581579185169-53e13c7e7b08') no-repeat center center fixed;
      background-size: cover;
      color: #333;
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
      margin-left: 25px;
      color: #333;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s;
    }
    nav a:hover {
      color: #8540cf;
    }
    .dashboard-container {
      display: flex;
      min-height: 100vh;
    }
    .sidebar {
      width: 220px;
      background-color: #2c3e50;
      padding-top: 30px;
      display: flex;
      flex-direction: column;
    }
    .sidebar a {
      color: #ecf0f1;
      padding: 15px 30px;
      text-decoration: none;
      font-size: 16px;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: background 0.3s ease;
    }
    .sidebar a:hover {
      background-color: #34495e;
    }
    .main-content {
      flex: 1;
      padding: 30px;
      background-color: rgba(255, 255, 255, 0.95);
    }
    .main-content h1 {
      color: #8540cf;
      margin-bottom: 20px;
      font-size: 24px;
    }
    .form-section {
      background-color: #ffffff;
      padding: 25px 30px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .form-row {
      display: flex;
      flex-direction: column;
      margin-bottom: 20px;
    }
    .form-row label {
      font-weight: 600;
      margin-bottom: 8px;
      color: #333;
    }
    .form-row input,
    .form-row select {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
    }
    .order-table {
      display: flex;
      flex-direction: column;
      gap: 12px;
      margin-bottom: 40px;
    }
    .order-row {
      display: grid;
      grid-template-columns: repeat(5, 1fr) auto;
      gap: 12px;
      align-items: center;
      margin-top: 10px;
    }
    .order-row input,
    .order-row select {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
    }
    .add-btn, .proceed-button {
      margin-top: 25px;
      padding: 10px 20px;
      font-size: 16px;
      background-color: #3498db;
      color: white;
      border: none;
      border-radius: 6px;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    .add-btn:hover, .proceed-button:hover {
      background-color: #2980b9;
    }
    .proceed-button-container {
      text-align: center;
      margin-top: 35px;
    }
    footer {
      background-color: #f4f4f4;
      text-align: center;
      padding: 15px;
      font-size: 14px;
      border-top: 1px solid #ddd;
      color: #555;
    }
    .error-message {
      color: red;
      font-size: 14px;
      margin-top: 4px;
    }
    .delete-btn {
      padding: 6px 12px;
      background-color: #e74c3c;
      color: white;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
      font-size: 14px;
      transition: background-color 0.3s ease;
    }
    .delete-btn:hover {
      background-color: #c0392b;
    }
    .grand-total {
      font-weight: bold;
      font-size: 18px;
      color: #090909;
      margin-top: 20px;
    }
  </style>
</head>
<body>
<header>
  <h1>🧺 Smart Laundry Management System</h1>
  <nav>
    <a href="userdashboard.html">Home</a>
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
    <a href="userorderhistory.html"><i class="fas fa-history"></i> Order History</a>
    <a href="userreviews.html"><i class="fa-solid fa-comments"></i> Reviews</a>
    <a href="Usersettings.html"><i class="fas fa-cogs"></i> Settings</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Log Out</a>
  </div>

  <div class="main-content">
    <h1><i class="fas fa-shopping-cart" style="margin-right: 10px;"></i>Place an Order</h1>
    <section class="form-section">
      <div class="form-row">
        <label>Customer Name *</label>
        <input type="text" id="customer-name" />
        <div id="name-error" class="error-message"></div>
      </div>
      <div class="form-row">
        <label>Address *</label>
        <input type="text" id="address" />
        <div id="address-error" class="error-message"></div>
      </div>

      <h2 style="color:#8540cf; margin-top: 30px;">Order Details *</h2>
      <div class="order-table" id="order-rows-container">
        <div class="order-row">
          <select class="product">
            <option value="">Select</option>
            <option>Shirt</option>
            <option>Pant</option>
            <option>T-Shirt</option>
          </select>
          <select class="service">
            <option value="">Select</option>
            <option>Dry-Wash</option>
            <option>Normal-Wash</option>
            <option>Iron</option>
            <option>Iron & Wash</option>
          </select>
          <input type="number" class="quantity" value="0" min="0" onchange="updateTotal(this)" />
          <input type="text" class="unit-price" value="0.00" readonly />
          <input type="text" class="total-price" value="0.00" readonly />
          <button class="delete-btn" onclick="deleteRow(this)"><i class="fa-solid fa-trash"></i></button>
        </div>
      </div>
      <div id="order-error" class="error-message"></div>
      <button class="add-btn" onclick="addOrderRow()">+ Add New</button>
      <div class="grand-total">Grand Total: $<span id="grand-total">0.00</span></div>

      <h2 style="color:#8540cf; margin-top: 30px;">Pickup & Delivery</h2>
      <div class="form-row" style="margin-top: 20px;">
        <label>Pickup Type *</label>
        <select id="pickup-type">
          <option value="">Select</option>
          <option>Door Pickup</option>
        </select>
        <div id="pickup-error" class="error-message"></div>
      </div>
      <div class="form-row">
        <label>Delivery Type *</label>
        <select id="delivery-type">
          <option value="">Select</option>
          <option>Door Delivery</option>
        </select>
        <div id="delivery-error" class="error-message"></div>
      </div>

      <div class="proceed-button-container">
        <button type="button" class="proceed-button" onclick="submitForm(event)">Proceed to Payment</button>
      </div>
    </section>
  </div>
</div>

<footer>
  <p>&copy; 2025 Smart Laundry Management System. All rights reserved.</p>
</footer>

<script>
function addOrderRow() {
  const container = document.getElementById('order-rows-container');
  const row = document.createElement('div');
  row.classList.add('order-row');
  row.innerHTML = `
    <select class="product">
      <option value="">Select</option>
      <option>Shirt</option>
      <option>Pant</option>
      <option>T-Shirt</option>
    </select>
    <select class="service">
      <option value="">Select</option>
      <option>Dry-Wash</option>
      <option>Normal-Wash</option>
      <option>Iron</option>
      <option>Iron & Wash</option>
    </select>
    <input type="number" class="quantity" value="0" min="0" onchange="updateTotal(this)" />
    <input type="text" class="unit-price" value="0.00" readonly />
    <input type="text" class="total-price" value="0.00" readonly />
    <button class="delete-btn" onclick="deleteRow(this)">❌</button>
  `;
  container.appendChild(row);
}

function deleteRow(button) {
  const container = document.getElementById('order-rows-container');
  if (container.querySelectorAll('.order-row').length > 1) {
    button.closest('.order-row').remove();
    updateGrandTotal();
  } else {
    alert("At least one order row is required.");
  }
}

function updateTotal(el) {
  const row = el.closest('.order-row');
  const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
  const unitPrice = 5.00;
  row.querySelector('.unit-price').value = unitPrice.toFixed(2);
  row.querySelector('.total-price').value = (unitPrice * quantity).toFixed(2);
  updateGrandTotal();
}

function updateGrandTotal() {
  let grandTotal = 0;
  document.querySelectorAll('.order-row').forEach(row => {
    grandTotal += parseFloat(row.querySelector('.total-price').value) || 0;
  });
  document.getElementById('grand-total').innerText = grandTotal.toFixed(2);
}

function validateForm() {
  let valid = true;
  document.querySelectorAll('.error-message').forEach(el => el.innerText = '');

  const name = document.getElementById("customer-name").value.trim();
  const address = document.getElementById("address").value.trim();
  const pickup = document.getElementById("pickup-type").value;
  const delivery = document.getElementById("delivery-type").value;

  if (!name) {
    document.getElementById("name-error").innerText = "Customer name is required.";
    valid = false;
  }
  if (!address) {
    document.getElementById("address-error").innerText = "Address is required.";
    valid = false;
  }

  const rows = document.querySelectorAll('#order-rows-container .order-row');
  let hasValidRow = false;
  rows.forEach(row => {
    const product = row.querySelector('.product').value;
    const service = row.querySelector('.service').value;
    const quantity = parseInt(row.querySelector('.quantity').value);
    if (product && service && quantity > 0) {
      hasValidRow = true;
    }
  });
  if (!hasValidRow) {
    document.getElementById("order-error").innerText = "At least one valid order is required.";
    valid = false;
  }

  if (!pickup) {
    document.getElementById("pickup-error").innerText = "Pickup type is required.";
    valid = false;
  }
  if (!delivery) {
    document.getElementById("delivery-error").innerText = "Delivery type is required.";
    valid = false;
  }

  return valid;
}

function submitForm(e) {
  e.preventDefault();
  if (!validateForm()) return;

  const formData = new FormData();
  formData.append('customer-name', document.getElementById('customer-name').value);
  formData.append('address', document.getElementById('address').value);
  formData.append('pickup-type', document.getElementById('pickup-type').value);
  formData.append('delivery-type', document.getElementById('delivery-type').value);
  formData.append('grand-total', document.getElementById('grand-total').innerText);

  const orders = [];
  document.querySelectorAll('.order-row').forEach(row => {
    orders.push({
      product: row.querySelector('.product').value,
      service: row.querySelector('.service').value,
      quantity: row.querySelector('.quantity').value,
      unitPrice: row.querySelector('.unit-price').value,
      totalPrice: row.querySelector('.total-price').value
    });
  });
  formData.append('orders', JSON.stringify(orders));
  
  fetch('submit_order.php', {
  method: 'POST',
  body: formData
})
.then(response => response.text())
.then(data => {
  // Save order to localStorage
  localStorage.setItem('customer-name', document.getElementById('customer-name').value);
  localStorage.setItem('address', document.getElementById('address').value);
  localStorage.setItem('pickup-type', document.getElementById('pickup-type').value);
  localStorage.setItem('delivery-type', document.getElementById('delivery-type').value);
  localStorage.setItem('grand-total', document.getElementById('grand-total').innerText);
  localStorage.setItem('orders', JSON.stringify(orders));

  // Redirect
  window.location.href = "process_payment.php";
})

 
  .catch(err => {
    console.error(err);
    alert("Error saving order.");
  });
}
</script>
</body>
</html>
