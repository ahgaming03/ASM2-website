<?php
include("database.php");

// Get the item ID, order ID and new quantity from the request
$product_id = $_POST["product_id"];
$order_id = $_POST["order_id"];
$quantity = $_POST["quantity"];

// Update the quantity of the item in the cart table
$stmt = $conn->prepare("UPDATE ordersline SET quantity=? WHERE product_id=? and order_id=?");
$stmt->bind_param("iii", $quantity, $product_id, $order_id);
$stmt->execute();

$conn->close();
?>