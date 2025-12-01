<?php
session_start();
include "../../../includes/db.php"; 

// 1. Check if ID is provided
if (!isset($_GET['id'])) {
    die("Customer ID not specified.");
}

$id = intval($_GET['id']);

// 2. Fetch the current customer
$result = $conn->query("SELECT * FROM customers WHERE id = $id");
if ($result->num_rows == 0) {
    die("Customer not found.");
}

$customer = $result->fetch_assoc();

// 3. Handle form submission
if (isset($_POST['update'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $age = intval($_POST['age']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $current_address = mysqli_real_escape_string($conn, $_POST['current_address']);

    $sql = "UPDATE customers 
            SET name='$name', age=$age, contact_number='$contact_number', current_address='$current_address'
            WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: customer_dashboard.php");
        exit();
    } else {
        echo "Error updating customer: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Customer</title>
</head>

<body>

    <h2>Edit Customer</h2>

    <form method="POST">

        <label>Name:</label><br>
        <input type="text" name="name" value="<?= htmlspecialchars($customer['name']) ?>" required><br><br>

        <label>Age:</label><br>
        <input type="number" name="age" min="1" value="<?= $customer['age'] ?>" required><br><br>

        <label>Contact Number:</label><br>
        <input type="text" name="contact_number" value="<?= htmlspecialchars($customer['contact_number']) ?>" required><br><br>

        <label>Current Address:</label><br>
        <input type="text" name="current_address" value="<?= htmlspecialchars($customer['current_address']) ?>" required><br><br>

        <button name="update" type="submit">Update Customer</button>
    </form>

    <br>
    <a href="customer_dashboard.php">Back to Customer Dashboard</a>

</body>

</html>