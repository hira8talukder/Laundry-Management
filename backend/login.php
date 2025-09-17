<?php
session_start();
include("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Fetch user by email
    $stmt = $conn->prepare("SELECT * FROM sign_up WHERE email = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Validate user and password
    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["user_id"];
        // $user["user_id"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["role"] = $user["role"];

        // Redirect based on role
        if ($user["role"] === "admin") {
          header("Location: ../front/adminDashboard.php");
        } else {
            header("Location: ../front/userdashboard.html");
        }
        exit();
    } else {
        echo "<script> window.location.href='../front/login.html';</script>";
        exit();
    }
}
