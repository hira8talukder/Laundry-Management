<?php

include("connection.php");
include("auth.php");

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    echo json_encode(["error" => "unauthorized"]);
    exit();
}

$stmt = $conn->prepare("SELECT * FROM reviews ORDER BY review_date DESC");
$stmt->execute();
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($reviews);
?>
