<?php
include("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = trim($_POST["fullName"]);
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];
    $role = $_POST["role"];

    // Password match check
    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match!'); window.location.href='../front/register.html';</script>";
        exit();
    }

    // Split full name into first and last
    $nameParts = explode(" ", $fullName, 2);
    $firstName = $nameParts[0];
    $lastName = isset($nameParts[1]) ? $nameParts[1] : "";

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $conn->beginTransaction();

        // Insert into sign_up
        $stmt1 = $conn->prepare("INSERT INTO sign_up (first_name, last_name, email, password, role)
                                 VALUES (:fname, :lname, :email, :password, :role)");
        $stmt1->execute([
            ':fname' => $firstName,
            ':lname' => $lastName,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':role' => $role
        ]);

        $user_id = $conn->lastInsertId();

        // Insert into user_profile
        $stmt2 = $conn->prepare("INSERT INTO user_profile (user_id, first_name, last_name, phone_number, address, profile_picture)
                                 VALUES (:user_id, :fname, :lname, '', '', NULL)");
        $stmt2->execute([
            ':user_id' => $user_id,
            ':fname' => $firstName,
            ':lname' => $lastName
        ]);

        $conn->commit();

        echo "<script> window.location.href='../front/login.html';</script>";
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>
