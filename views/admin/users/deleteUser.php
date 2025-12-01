<?php


session_start();

include "../../../includes/db.php";

// 1. Check if ID is provided
if (!isset($_GET['id'])) {
    die("Error: Customer ID not specified.");
}

// 2. Filter the ID
$id = intval($_GET['id']);

// 3. Delete the customer
$sql = "DELETE FROM customers WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    header("Location: customer_dashboard.php?deleted=1"); // Back to customer dashboard
    exit();
} else {
    echo "Error deleting customer: " . $conn->error;
}
