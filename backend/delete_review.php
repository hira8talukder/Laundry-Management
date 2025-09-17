<?php
include("connection.php");
include("auth.php");
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
    if ($stmt->execute([$id])) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
