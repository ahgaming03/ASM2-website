<?php
include("database.php");

// Get the order ID from the request
$id = $_POST["id"];
$status = 1;

// Update the quantity of the item in the cart table
$stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id=?");
$stmt->bind_param("ii", $status, $id);
$stmt->execute();

$conn->close();
?> 