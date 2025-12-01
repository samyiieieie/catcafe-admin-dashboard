<?php
include '../../../includes/db.php'; // Adjust path if needed

// If the "Submit" button was clicked
if (isset($_POST['submit'])) {

    // Get input values
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);

    // Insert into food_items table
    $sql = "INSERT INTO food_items (name, description, price) VALUES ('$name', '$description', '$price')";
    if ($conn->query($sql)) {
        header("Location: food_dashboard.php"); // Back to food dashboard
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Food Item</title>
</head>

<body>

    <h2>Add New Food Item</h2>

    <form method="POST">

        <!-- Name -->
        Name: <input type="text" name="name" required><br><br>

        <!-- Price -->
        Price: <input type="number" name="price" step="0.01" required><br><br>

        <!-- Description -->
        Description: <textarea name="description"></textarea><br><br>

        <button name="submit">Save</button>
    </form>

    <br>
    <a href="food_dashboard.php">Back to Food Dashboard</a>

</body>

</html>