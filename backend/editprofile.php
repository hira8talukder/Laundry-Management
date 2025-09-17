<?php

require 'connection.php';
include("auth.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../front/login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("UPDATE user_profile SET 
        first_name = :first_name,
        last_name = :last_name,
        phone_number = :phone_number,
        date_of_birth = :dob,
        address = :address,
        permanent_address = :permanent_address,
        education = :education,
        occupation = :occupation
        WHERE user_id = :user_id");

    $stmt->execute([
        ':first_name' => $_POST['firstName'],
        ':last_name' => $_POST['lastName'],
        ':phone_number' => $_POST['phone'],
        ':dob' => $_POST['dob'],
        ':address' => $_POST['address'],
        ':permanent_address' => $_POST['permanentAddress'],
        ':education' => $_POST['education'],
        ':occupation' => $_POST['occupation'],
        ':user_id' => $user_id
    ]);

    header("Location: profile.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM user_profile WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta initial-scale=1.0">
 <title>Edit Profile | Laundry Management</title>
 https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css
 <link rel="stylesheet" href="../front/styles/editprofile.css">
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

 <div class="profile-container">
   <div class="profile-header">
     <h1><i class="fas fa-user-edit"></i> Edit Profile</h1>
   </div>

   <div class="profile-box">
     <form method="POST" action="editprofile.php">
       <div class="form-section">
         <h2><i class="fas fa-id-card"></i> Personal Information</h2>
         <div class="form-grid">
           <div class="form-group">
             <label for="firstName">First Name</label>
             <input type="text" name="firstName" value="<?= htmlspecialchars($user['first_name']) ?>" required>
           </div>
           <div class="form-group">
             <label for="lastName">Last Name</label>
             <input type="text" name="lastName" value="<?= htmlspecialchars($user['last_name']) ?>" required>
           </div>
           <div class="form-group">
             <label for="phone">Phone Number</label>
             <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone_number']) ?>" required>
           </div>
           <div class="form-group">
             <label for="dob">Date of Birth</label>
             <input type="date" name="dob" value="<?= htmlspecialchars($user['date_of_birth']) ?>" required>
           </div>
         </div>
       </div>

       <div class="form-section">
         <h2><i class="fas fa-envelope"></i> Contact Information</h2>
         <div class="form-grid">
           <div class="form-group full-width">
             <label for="address">Present Address</label>
             <textarea name="address" rows="3" required><?= htmlspecialchars($user['address']) ?></textarea>
           </div>
           <div class="form-group full-width">
             <label for="permanentAddress">Permanent Address</label>
             <textarea name="permanentAddress" rows="3" required><?= htmlspecialchars($user['permanent_address']) ?></textarea>
           </div>
         </div>
       </div>

       <div class="form-section">
         <h2><i class="fas fa-info-circle"></i> Additional Information</h2>
         <div class="form-grid">
           <div class="form-group">
             <label for="education">Education</label>
             <input type="text" name="education" value="<?= htmlspecialchars($user['education']) ?>">
           </div>
           <div class="form-group">
             <label for="occupation">Occupation</label>
             <input type="text" name="occupation" value="<?= htmlspecialchars($user['occupation']) ?>">
           </div>
         </div>
       </div>

       <div class="form-actions">
         <a href="profile.php" class="btn cancel-btn">Cancel</a>
         <button type="submit" class="btn save-btn">Save Changes</button>
       </div>
     </form>
   </div>
 </div>

 <footer class="main-footer">
   <p>&copy; 2025 Smart Laundry Management System. All rights reserved.</p>
 </footer>
</body>
</html>
