<?php

include("connection.php");
include("auth.php");

if (!isset($_SESSION["user_id"])) {
    echo json_encode([]);
    exit();
}

$userId = $_SESSION["user_id"];
$stmt = $conn->prepare("SELECT * FROM reviews WHERE user_id = ?");
$stmt->execute([$userId]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($reviews);
?>
