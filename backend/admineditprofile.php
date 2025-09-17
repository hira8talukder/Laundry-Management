<?php

include("../backend/connection.php");
include("auth.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT s.*, 
           p.phone_number, 
           p.date_of_birth, 
           p.address AS present_address, 
           p.permanent_address,
           p.education, 
           p.occupation
    FROM sign_up s
    LEFT JOIN user_profile p ON s.user_id = p.user_id
    WHERE s.user_id = :user_id
");
$stmt->execute([':user_id' => $user_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Edit Profile | Laundry Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../front/styles/admineditprofile.css">
</head>
<header>
    <h1>🧺 Smart Laundry Management System</h1>
    <nav>
        <a href="pricing.html">Pricing</a>
        <a href="blog.html">Blog</a>
        <a href="contact.html">Contact Us</a>
    </nav>
</header>
<body>
<div class="profile-container">
    <div class="profile-header">
        <h1><i class="fas fa-user-edit"></i> Edit Profile</h1>
    </div>

    <div class="profile-box">
        <form id="profile-form" method="POST" action="../backend/adminupdateprofile.php">
            <div class="form-section">
                <h2><i class="fas fa-id-card"></i> Personal Information</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" name="first_name" value="<?= htmlspecialchars($admin['first_name'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="last_name" value="<?= htmlspecialchars($admin['last_name'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone_number" value="<?= htmlspecialchars($admin['phone_number'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" id="dob" name="date_of_birth" value="<?= htmlspecialchars($admin['date_of_birth'] ?? '') ?>" required>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h2><i class="fas fa-envelope"></i> Contact Information</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($admin['email'] ?? '') ?>" required>
                    </div>
                    <div class="form-group full-width">
                        <label for="present_address">Present Address</label>
                        <textarea id="present_address" name="present_address" rows="3" required><?= htmlspecialchars($admin['present_address'] ?? '') ?></textarea>
                    </div>
                    <div class="form-group full-width">
                        <label for="permanent_address">Permanent Address</label>
                        <textarea id="permanent_address" name="permanent_address" rows="3" required><?= htmlspecialchars($admin['permanent_address'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h2><i class="fas fa-info-circle"></i> Additional Information</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="education">Education</label>
                        <input type="text" id="education" name="education" value="<?= htmlspecialchars($admin['education'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="occupation">Occupation</label>
                        <input type="text" id="occupation" name="occupation" value="<?= htmlspecialchars($admin['occupation'] ?? '') ?>">
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn cancel-btn">Cancel</button>
                <button type="submit" class="btn save-btn">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelector('.cancel-btn').addEventListener('click', function () {
        if (confirm('Discard all changes?')) {
            window.location.href = '../backend/adminprofile.php';
        }
    });
</script>
<footer class="main-footer">
    <p>&copy; 2025 Smart Laundry Management System. All rights reserved.</p>
</footer>
</body>
</html>
