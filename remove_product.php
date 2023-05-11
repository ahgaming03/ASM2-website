<?php
include("database.php");

// Get product and order ID  from the request
$product_id = $_POST["product_id"];
$order_id = $_POST["order_id"];

// Delete the product from the cart
$stmt = $conn->prepare("DELETE FROM ordersline WHERE product_id=? and order_id=?");
$stmt->bind_param("ii", $product_id, $order_id);
$stmt->execute();

$conn->close();
?>

