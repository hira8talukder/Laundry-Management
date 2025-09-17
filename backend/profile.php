<?php

require 'connection.php';
include("auth.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../front/login.html"); // adjust path if needed
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT s.email, s.created_at, p.* 
        FROM sign_up s 
        JOIN user_profile p ON s.user_id = p.user_id 
        WHERE s.user_id = :user_id";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8" />
 <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
 <title>User Profile | Laundry Management System</title>
 <link rel="stylesheet" href="../front/styles/profile.css"/>
</head>
<body>
 <header>
 <h1>🧺 Smart Laundry Management System</h1>
 <nav>
 <a href="#">Pricing</a>
 <a href="#">Blog</a>
 <a href="#contact">Contact Us</a>
 </">
 <div class="sidebar">
 <a href="../front/userdashboard.html"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
 <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
 <a href="../front/placeorder.html"><i class="fas fa-shopping-cart"></i> Place Order</a>
 <a href="../front/userorderhistory.html"><i class="fas fa-history"></i> Order History</a>
 <a href="../backend/userreviews.php"><i class="fa-solid fa-comments"></i> Reviews</a>
 <a href="../front/Usersettings.html"><i class="fas fa-cogs"></i> Settings</a>
 <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Log Out</a>
 </div>

 <div class="profile-container">
 <div class="profile-content">
 <div class="profile-header">
 <h1 class="profile-title"><i class="fas fa-user" style="margin-right: 10px;"></i>My Profile</h1>
 <a href="editprofile.php">
 <button class="edit-btn"><i class="fas fa-edit"></i> Edit Profile</button>
 </a>
 </div>

 <div class="profile-section">
 <h3 class="section-title">Personal Information</h3>
 <div class="profile-details">
 <div class="detail-item"><div class="detail-label">First Name</div><div class="detail-value"><?= htmlspecialchars($user['first_name']) ?></div></div>
 <div class="detail-item"><div class="detail-label">Last Name</div><div class="detail-value"><?= htmlspecialchars($user['last_name']) ?></div></div>
 <div class="detail-item"><div class="detail-label">Phone Number</div><div class="detail-value"><?= htmlspecialchars($user['phone_number']) ?></div></div>
 <div class="detail-item"><div class="detail-label">Date of Birth</div><div class="detail-value"><?= htmlspecialchars($user['date_of_birth']) ?></div></div>
 </div>
 </div>

 <div class="profile-section">
 <h3 class="section-title">Contact Information</h3>
 <div class="profile-details">
 <div class="detail-item"><div class="detail-label">Email Address</div><div class="detail-value"><?= htmlspecialchars($user['email']) ?></div></div>
 <div class="detail-item"><div class="detail-label">Present Address</div><div class="detail-value"><?= htmlspecialchars($user['address']) ?></div></div>
 <div class="detail-item"><div class="detail-label">Permanent Address</div><div class="detail-value"><?= htmlspecialchars($user['permanent_address']) ?></div></div>
 </div>
 </div>

 <div class="profile-section">
 <h3 class="section-title">Additional Information</h3>
 <div class="profile-details">
 <div class="detail-item"><div class="detail-label">Education</div><div class="detail-value"><?= htmlspecialchars($user['education']) ?></div></div>
 <div class="detail-item"><div class="detail-label">Occupation</div><div class="detail-value"><?= htmlspecialchars($user['occupation']) ?></div></div>
 <div class="detail-item"><div class="detail-label">Member Since</div><div class="detail-value"><?= date("d F Y", strtotime($user['created_at'])) ?></div></div>
 </div>
 </div>
 </div>
 </div>
 </div>

 <footer class="main-footer">
 <p>&copy; 2025 Smart Laundry Management System. All rights reserved.</p>
 </footer>
</body>
</html>
