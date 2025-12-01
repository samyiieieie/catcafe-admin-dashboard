<?php
session_start();
include '../../../includes/db.php'; // Adjust path if needed

// 1. Check if ID is provided
if (!isset($_GET['id'])) {
    die("Error: Food item ID not specified.");
}

// 2. Filter the ID
$id = intval($_GET['id']);

// 3. Delete the food item
$sql = "DELETE FROM food_items WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    header("Location: food_dashboard.php?deleted=1"); // Back to food dashboard
    exit();
} else {
    echo "Error deleting food item: " . $conn->error;
}

// Note: No HTML needed
?>
