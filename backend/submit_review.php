<?php

include("connection.php");
include("auth.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $name = $_POST["name"];
    $service = $_POST["service"];
    $rating = $_POST["rating"];
    $review = $_POST["review"];
    $date = date("Y-m-d");

   
    try {
        $stmt = $conn->prepare("INSERT INTO reviews (user_id, name, service, rating, review_text, review_date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $name, $service, $rating, $review, $date]);
        echo "success";
    } catch (PDOException $e) {
        echo "error: " . $e->getMessage();
    }
} else {
    echo "unauthorized";
}
?>
