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

// Handle payment submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'] ?? null;
    if (!$user_id) {
        echo json_encode(["status" => "error", "message" => "User not logged in"]);
        exit;
    }

    $payment_method = $_POST['payment_method'] ?? 'Cash on Delivery';
    $amount = $_POST['amount'] ?? 0.00;
    $transaction_id = $_POST['transaction_id'] ?? null;

    try {
        $stmt = $conn->prepare("INSERT INTO bill (user_id, payment_method, amount, transaction_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $payment_method, $amount, $transaction_id]);
        echo json_encode(["status" => "success", "message" => "Payment processed successfully"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Payment failed: " . $e->getMessage()]);
    }
}
?>


<!DOCTYPE html>
<html>
<head>
<style>
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: #f7f8fd;
  margin: 0;
  padding: 0;
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

.container {
  max-width: 900px;
  margin: auto;
  padding: 20px;
}
 .container > header {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 15px; /* reduce spacing below */
  margin-top: 10px;     /* add this to control top spacing */
}

.container > header h1 {
  font-size: 36px;
  font-weight: bold;
  margin-top: 5px;  /* reduce top margin */
  color: #000;
}

.illustration {
  background: url('https://img.icons8.com/ios-filled/100/000000/laundry.png') no-repeat center;
  background-size: contain;
  height: 50px;        /* reduce height */
  margin-bottom: 10px; /* reduce bottom spacing */
}


.payment-box {
  display: flex;
  justify-content: space-between;
  gap: 20px;
}

.order-summary,
.payment-method {
  background-color: white;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
  width: 48%;
}

.item {
  display: flex;
  justify-content: space-between;
  margin: 10px 0;
}

.total {
  margin-top: 20px;
  border-top: 1px solid #ccc;
  padding-top: 10px;
}

/* Updated payment method styles with blue color */
.method label {
  display: flex;
  align-items: center;
  margin: 10px 0;
  gap: 10px;
  padding: 10px;
  border-radius: 5px;
  background-color: #f0f7ff; /* Light blue background */
}

.method input[type="radio"] {
  accent-color: #4476f2; /* Blue radio button */
}

.method label:hover {
  background-color: #e1edff; /* Slightly darker blue on hover */
}

.method img {
  height: 20px;
}

.coupon h3 {
  margin-top: 20px;
  margin-bottom: 10px;
}

.coupon input {
  padding: 8px;
  width: 70%;
  margin-right: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.coupon button {
  padding: 8px 12px;
  background-color: #4476f2;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

footer {
  text-align: center;
  margin-top: 30px;
}

.pay-button {
  padding: 15px 40px;
  background-color: #4476f2;
  color: white;
  font-size: 16px;
  border: none;
  border-radius: 10px;
  cursor: pointer;
}
.cancel-button {
  padding: 15px 40px;
  background-color: #f44336; /* Red color */
  color: white;
  font-size: 16px;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.cancel-button:hover {
  background-color: #d32f2f; /* Darker red on hover */
}

footer {
      background-color: #f8f8f8;
      text-align: center;
      padding: 20px;
      border-top: 1px solid #ddd;
    }
</style>
</head>
<body>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Payment Page</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link rel="stylesheet" href="styles/payment.css" />
</head>
<body>
  <header>
    <h1>🧺 Smart Laundry Management System</h1>
    <nav>
      <a href="#">Pricing</a>
      <a href="#">Blog</a>
      <a href="#contact">Contact Us</a>
    </nav>
  </header>

  <div class="container">
    <header>
      <div class="illustration"></div>
      <h1>Payment<i class="fa-solid fa-money-bill" style="margin-left: 10px;"></i></h1>
    </header>

    <main class="payment-box">
      <section class="order-summary">
        <h2>Order Summary</h2>
        <div id="order-summary-items"></div>
        <div class="item total">
          <strong>Total</strong>
          <strong id="total-amount"></strong>
        </div>
      </section>

      <section class="payment-method">
        <h2>Payment Method</h2>
        <div class="method">
          <label><input type="radio" name="payment" /> Visa Card <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png" alt="Visa" /></label>
          <label><input type="radio" name="payment" checked /> Credit card <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png" alt="MasterCard" /></label>
          <label><input type="radio" name="payment" /> Bkash <img src="https://download.logo.wine/logo/BKash/BKash-Icon2-Logo.wine.png" alt="Bkash" /></label>
        </div>

        <div class="coupon">
          <h3>Coupon Code</h3>
          <input type="text" placeholder="Enter Coupon Code" />
          <button>Apply</button>
        </div>
      </section>
    </main>

    <footer>
      <button class="pay-button" onclick="finalizePayment()">PAY NOW</button>
      <button class="cancel-button" onclick="window.location.href='placeorder.html'">CANCEL</button>
    </footer>
  </div>

  <footer class="main-footer">
    <p>&copy; 2025 Smart Laundry Management System. All rights reserved.</p>
  </footer>

  <script>
    // Load order data from localStorage
    const customerName = localStorage.getItem('customer-name');
    const address = localStorage.getItem('address');
    const pickupType = localStorage.getItem('pickup-type');
    const deliveryType = localStorage.getItem('delivery-type');
    const grandTotal = localStorage.getItem('grand-total');
    const orders = JSON.parse(localStorage.getItem('orders') || '[]');

    // Update order summary UI
    const summaryContainer = document.getElementById('order-summary-items');
    const totalAmountElem = document.getElementById('total-amount');
    let summaryHTML = '';

    orders.forEach(order => {
      summaryHTML += `
        <div class="item">
          <span>${order.product} (${order.service}) × ${order.quantity}</span>
          <span>$${parseFloat(order.totalPrice).toFixed(2)}</span>
        </div>`;
    });

    summaryHTML += `
      <div class="item">
        <span>Pickup: ${pickupType}</span>
        <span></span>
      </div>
      <div class="item">
        <span>Delivery: ${deliveryType}</span>
        <span></span>
      </div>
      <div class="item">
        <span>Address:</span>
        <span>${address}</span>
      </div>`;

    summaryContainer.innerHTML = summaryHTML;
    totalAmountElem.innerText = `$${grandTotal} BDT`;

    function finalizePayment() {
      const payload = {
        customer_name: customerName,
        address: address,
        pickup_type: pickupType,
        delivery_type: deliveryType,
        grand_total: grandTotal,
        orders: orders
      };

      fetch('process_payment.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      })
      .then(res => res.text())
      .then(response => {
        alert("Payment Successful! Your order has been placed.");
        localStorage.clear();
        window.location.href = '../front/userdashboard.html'; // Or dashboard
      })
      .catch(err => {
        console.error(err);
        alert("Payment failed. Please try again.");
      });
    }
  </script>
</body>
</html>

</body>
</html>