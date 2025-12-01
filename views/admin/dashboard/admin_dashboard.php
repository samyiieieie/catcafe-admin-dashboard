<?php

// Start a Session
session_start();

// Include the Connect Database File
include '../../../includes/db.php';

// Redirect to login if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: ../../login/login.php");
    exit();
}

// For the Summary Table, use SQL commands to get data from the admin_dashboard database
$total_customers = $conn->query("SELECT COUNT(*) AS total FROM customers")->fetch_assoc()['total'];
$total_food = $conn->query("SELECT COUNT(*) AS total FROM food_items")->fetch_assoc()['total'];
$total_orders = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];
$total_sales = $conn->query("SELECT SUM(total_price) AS total FROM orders WHERE status='finished'")->fetch_assoc()['total'];

// Table to fetch order details with the status "PENDING"
$pending_orders = $conn->query("
    SELECT o.id AS order_id, c.name AS customer_name, o.total_price, o.created_at
    FROM orders o
    JOIN customers c ON o.customer_id = c.id
    WHERE o.status='pending'
    ORDER BY o.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>

<body>

    <!-- Header + Welcome Message with name column from DB-->
    <h1>Admin Dashboard</h1>
    <h3>Welcome, <?php echo $_SESSION['name']; ?></h3>

    <!-- Navigation Bar-->
    <p id="nav-bar">
        <a href="admin_dashboard.php">Dashboard</a> 
        <a href="../food/food_dashboard.php">Food Items</a> 
        <a href="../users/customer_dashboard.php">Customers</a> 
        <a href="../orders/order_history_dashboard.php">Finished Orders</a> 
        <a href="../../login/logout.php">Logout</a>
    </p>

    <!-- Summary Table -->
    <table border="1" cellpadding="10" id="summary-table">
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
            <td>₱<?php echo $total_sales ?? 0; ?></td>
        </tr>
    </table>

    <!-- Pending Orders Table -->
    <h2>Pending Orders</h2>

    <!-- Create Order Button -->
    <p><a href="createOrder.php">Create New Order</a></p>

    <table border="1" cellpadding="10" id="pending-table">
        <tr>
            <th>Customer</th>
            <th>Food Items</th>
            <th>Total Price</th>
            <th>Order Date</th>
            <th>Actions</th>
        </tr>

        <?php if ($pending_orders->num_rows > 0): ?>
            <?php while ($order = $pending_orders->fetch_assoc()): ?>

                <?php
                
                // Fetch food items for this order
                $order_id = $order['order_id'];
                $items_res = $conn->query("SELECT f.name, oi.quantity 
                                       FROM order_items oi
                                       JOIN food_items f ON oi.food_id = f.id
                                       WHERE oi.order_id = $order_id");

                // store into an array
                $food_list = [];

                // store item name + food name + the price (* quantity)
                while ($item = $items_res->fetch_assoc()) {
                    $food_list[] = htmlspecialchars($item['name']) . " (x" . $item['quantity'] . ")";
                }
                ?>
                <tr>
                    <td><?= htmlspecialchars($order['customer_name']) ?></td>
                    <td><?= implode(", ", $food_list) ?></td>
                    <td>₱<?= number_format($order['total_price'], 2) ?></td>
                    <td><?= $order['created_at'] ?></td>
                    <td>
                        
                        <!-- Finish Order Button -->
                        <a href="finishOrder.php?id=<?= $order['order_id'] ?>"
                            onclick="return confirm('Are you sure you want to mark this order as finished?');">Finish</a> |

                        <!-- Delete Order Button -->
                        <a href="deleteOrder.php?id=<?= $order['order_id'] ?>"
                            onclick="return confirm('Are you sure you want to delete this order?');">Delete</a>
                    </td>
                </tr>

            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <!-- Display this if there are no orders in the table -->
                <td colspan="5">No pending orders.</td>
            </tr>
        <?php endif; ?>
    </table>


</body>

</html>