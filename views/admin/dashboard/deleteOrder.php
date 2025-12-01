<?php

// Start a Session
session_start();

// Include Database
include '../../../includes/db.php'; 

// Check if logged in
if (!isset($_SESSION['id'])) {
    header("Location: ../../login.php");
    exit();
}

// Check if order ID is provided
if (!isset($_GET['id'])) {
    die("Error: Order ID not specified.");
}

$order_id = intval($_GET['id']);

// Delete the order (order_items will be deleted automatically via foreign key ON DELETE CASCADE)
if ($conn->query("DELETE FROM orders WHERE id = $order_id") === TRUE) {
    header("Location: admin_dashboard.php?deleted=1");
    exit();
} else {
    echo "Error deleting order: " . $conn->error;
}
