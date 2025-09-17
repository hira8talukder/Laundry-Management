<?php
include("connection.php");
include("auth.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"];
    $review_text = $_POST["review_text"];

    $stmt = $conn->prepare("UPDATE reviews SET review_text = ? WHERE id = ?");
    if ($stmt->execute([$review_text, $id])) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
