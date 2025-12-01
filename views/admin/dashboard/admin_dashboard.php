<?php
session_start();
include '../../../includes/db.php';

// Redirect to login if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: ../../views/admin/login.php");
    exit();
}

// Fetch totals
$total_customers = $conn->query("SELECT COUNT(*) AS total FROM customers")->fetch_assoc()['total'];
$total_food = $conn->query("SELECT COUNT(*) AS total FROM food_items")->fetch_assoc()['total'];
$total_orders = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];
$total_sales = $conn->query("SELECT SUM(total_price) AS total FROM orders")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>

<body>

    <h1>Welcome, <?php echo $_SESSION['name']; ?></h1>

    <!-- Navigation -->
    <p>
        <a href="admin_dashboard.php">Dashboard</a> |
        <a href="../food/food_dashboard.php">Food Items</a> |
        <a href="../users/customer_dashboard.php">Customers</a> |
        <a href="../orders/orders.php">Orders</a> |
        <a href="../../login/logout.php">Logout</a>
    </p>

    <!-- Summary -->
    <h2>Summary</h2>
    <table border="1" cellpadding="10">
        <tr>
            <th>Total Customers</th>
            <th>Total Food Items</th>
            <th>Total Orders</th>
            <th>Total Sales</th>
        </tr>
        <tr>
            <td><?php echo $total_customers; ?></td>
            <td><?php echo $total_food; ?></td>
            <td><?php echo $total_orders; ?></td>
            <td><?php echo $total_sales ?? 0; ?></td>
        </tr>
    </table>

</body>

</html>