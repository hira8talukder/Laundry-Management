<?php

include("connection.php");
include("auth.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Collect form data safely
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$phone = $_POST['phone_number'];
$dob = $_POST['date_of_birth'];
$email = $_POST['email'];
$present_address = $_POST['present_address'];
$permanent_address = $_POST['permanent_address'];
$education = $_POST['education'];
$occupation = $_POST['occupation'];

// Update `sign_up` table
$stmt1 = $conn->prepare("UPDATE sign_up SET first_name = ?, last_name = ?, email = ? WHERE user_id = ?");
$stmt1->execute([$first_name, $last_name, $email, $user_id]);

// Check if profile already exists
$check = $conn->prepare("SELECT * FROM user_profile WHERE user_id = ?");
$check->execute([$user_id]);

if ($check->rowCount() > 0) {
    // Update existing profile
    $stmt2 = $conn->prepare("UPDATE user_profile SET phone_number = ?, date_of_birth = ?, address = ?, permanent_address = ?, education = ?, occupation = ? WHERE user_id = ?");
    $stmt2->execute([$phone, $dob, $present_address, $permanent_address, $education, $occupation, $user_id]);
} else {
    // Insert new profile
    $stmt2 = $conn->prepare("INSERT INTO user_profile (user_id, phone_number, date_of_birth, address, permanent_address, education, occupation) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt2->execute([$user_id, $phone, $dob, $present_address, $permanent_address, $education, $occupation]);
}

// Redirect back to profile
header("Location: ../backend/adminprofile.php");
exit();
?>
