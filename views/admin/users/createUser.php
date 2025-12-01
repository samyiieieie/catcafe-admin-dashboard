<?php
session_start();
include "../../../includes/db.php";

$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $age = intval($_POST['age']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $current_address = mysqli_real_escape_string($conn, $_POST['current_address']);

    // Insert into customers table
    $query = "INSERT INTO customers (name, age, contact_number, current_address) 
              VALUES ('$name', $age, '$contact_number', '$current_address')";

    if (mysqli_query($conn, $query)) {
        header("Location: customer_dashboard.php");
        exit();
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add New Customer</title>
</head>

<body>

    <h2>Add New Customer</h2>

    <p style="color:red;"><?php echo $message; ?></p>

    <form action="" method="POST">

        <label>Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Age:</label><br>
        <input type="number" name="age" min="1" required><br><br>

        <label>Contact Number:</label><br>
        <input type="text" name="contact_number" required><br><br>

        <label>Current Address:</label><br>
        <input type="text" name="current_address" required><br><br>

        <button type="submit">Add Customer</button>
    </form>

    <br>
    <a href="customers_dashboard.php">Back to Customer Dashboard</a>

</body>

</html>