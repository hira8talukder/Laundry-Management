<?php



include("connection.php");
include("auth.php");

// Debug: check if session is working
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Query data from sign_up and user_profile using user_id
$stmt = $conn->prepare("
 SELECT s.*, 
       p.phone_number, 
       p.date_of_birth, 
       p.address AS present_address, 
       p.education, 
       p.occupation
FROM sign_up s
LEFT JOIN user_profile p ON s.user_id = p.user_id
WHERE s.user_id = :user_id

");

$stmt->execute([':user_id' => $user_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);



// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
// exit();




// Debug: show admin data to test
//echo "<pre>"; print_r($admin); echo "</pre>";
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Profile | Laundry Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
     <link rel="stylesheet" href="../front/styles/adminprofile.css"/>
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
        <div class="sidebar">
            <a href="../front/adminDashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="../backend/adminprofile.php"><i class="fas fa-user"></i> Profile</a>
            <a href="../front/userlist (1).html "><i class="fa-solid fa-users"></i> Users List</a>
            <a href="../backend/adminorderhistory.php"><i class="fas fa-history"></i> Order History</a>
            <a href="paymenthistory.html"><i class="fa-solid fa-cash-register"></i> Payment History</a>
            <a href="adminreviews.html"><i class="fa-solid fa-comments"></i> Reviews</a>
            <a href="settingsadmin.html"><i class="fas fa-cogs"></i> Settings</a>
            <a href="../backend/logout.php"><i class="fas fa-sign-out-alt"></i> Log Out</a>
        </div>

        <div class="profile-container">
            <div class="profile-content">
                <div class="profile-header">
                    <h1 class="profile-title"><i class="fas fa-user" style="margin-right: 10px;"></i>My Profile</h1>
                    <a href="../backend/admineditprofile.php">
                        <button class="edit-btn"><i class="fas fa-edit"></i> Edit Profile</button>
                    </a>

                </div>

                <div class="profile-section">
                    <h3 class="section-title">Personal Information</h3>
                    <div class="profile-details">
                        <div class="detail-item">
                            <div class="detail-label">First Name</div>
                            <div class="detail-value"><?= htmlspecialchars($admin['first_name']) ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Last Name</div>
                            <div class="detail-value"><?= htmlspecialchars($admin['last_name']) ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Phone Number</div>
                            <div class="detail-value"><?= htmlspecialchars($admin['phone_number'] ?? 'Not set') ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Date of Birth</div>
                            <div class="detail-value"><?= htmlspecialchars($admin['date_of_birth'] ?? 'Not set') ?></div>
                        </div>
                    </div>
                </div>

                <!-- ✅ Contact Information -->
                <div class="profile-section">
                    <h3 class="section-title">Contact Information</h3>
                    <div class="profile-details">
                        <div class="detail-item">
                            <div class="detail-label">Email Address</div>
                            <div class="detail-value"><?= htmlspecialchars($admin['email']) ?></div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Present Address</div>
                            <div class="detail-value"><?= htmlspecialchars($admin['present_address'] ?? 'Not set') ?></div>
                        </div>
                      
                    </div>
                </div>

                <!-- ✅ Additional Information -->
                <div class="profile-section">
                    <h3 class="section-title">Additional Information</h3>
                    <div class="profile-details">
                        <div class="detail-item">
                            <div class="detail-label">Education</div>
                            <div class="detail-value"><?= htmlspecialchars($admin['education'] ?? 'Not set') ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Occupation</div>
                            <div class="detail-value"><?= htmlspecialchars($admin['occupation'] ?? 'Not set') ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Member Since</div>
                            <div class="detail-value"><?= date('F j, Y', strtotime($admin['created_at'])) ?></div>
                        </div>
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