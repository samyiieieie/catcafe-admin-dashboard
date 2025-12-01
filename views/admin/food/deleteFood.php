<?php

// Start a Session
session_start();

// Include Database 
include '../../../includes/db.php'; // Adjust path if needed

// Error Handling: if id cannot be taken
if (!isset($_GET['id'])) {
    die("Error: Food item ID not specified.");
}

// Filter the ID (just followed a youtube video lol)
$id = intval($_GET['id']);

// Delete the food item
$sql = "DELETE FROM food_items WHERE id = $id";

// If everything goes well, redirect to food dashboard
if ($conn->query($sql) === TRUE) {
    header("Location: food_dashboard.php?deleted=1"); 
    exit();
} else {
    echo "Error deleting food item: " . $conn->error;
}

// Note: No HTML needed
?>
