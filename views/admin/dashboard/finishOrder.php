<?php
session_start();
include '../../../includes/db.php';

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: ../../login.php");
    exit();
}

// Check if order ID is provided
if (!isset($_GET['id'])) {
    die("Order ID not specified.");
}

$order_id = intval($_GET['id']);

// Fetch order to check status
$order_res = $conn->query("SELECT * FROM orders WHERE id = $order_id");
if ($order_res->num_rows == 0) {
    die("Order not found.");
}
$order = $order_res->fetch_assoc();



// Update order status to 'finished'
$update = $conn->query("UPDATE orders SET status='finished' WHERE id = $order_id");

if ($update) {

    header("Location: admin_dashboard.php?finished=1");
    exit();
} else {
    echo "Error finishing order: " . $conn->error;
}
