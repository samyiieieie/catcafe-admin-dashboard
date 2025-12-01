<?php

// Start a Session
session_start();

// Include Database Connection
include "../../../includes/db.php";


if (!isset($_GET['id'])) {
    die("Error: Customer ID not specified.");
}

// Filter the ID
$id = intval($_GET['id']);

// Set "$sql" to delete the customer using the primary key
$sql = "DELETE FROM customers WHERE id = $id";

// Redirect back to customer dashboard once finished
if ($conn->query($sql) === TRUE) {
    header("Location: customer_dashboard.php?deleted=1"); 
    exit();
} else {
    echo "Error deleting customer: " . $conn->error;
}
