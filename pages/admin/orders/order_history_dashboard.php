<?php
session_start();
include '../../../database/db.php'; // Adjust path if needed

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: ../../login.php");
    exit();
}

// Handle search by customer name or order ID
$search = $_GET['search'] ?? '';
$search = mysqli_real_escape_string($conn, $search);

// Fetch only finished orders with customer info
$sql = "SELECT o.id AS order_id, o.customer_id, c.name AS customer_name, 
        o.total_price, o.status, o.created_at
        FROM orders o
        JOIN customers c ON o.customer_id = c.id
        WHERE (c.name LIKE '%$search%' OR o.id LIKE '%$search%')
          AND o.status='finished'
        ORDER BY o.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Finished Orders Dashboard</title>
</head>

<body>

    <h1>Finished Orders Dashboard</h1>


    <!-- Navigation -->
    <p id="nav-bar">
        <a href="../dashboard/admin_dashboard.php">Dashboard</a> 
        <a href="../food/food_dashboard.php">Food Items</a> 
        <a href="../users/customer_dashboard.php">Customers</a> 
        <a href="order_history_dashboard.php">Finished Orders</a> 
        <a href="../../login/logout.php">Logout</a>
    </p>

    <!-- Orders Table -->
    <table border="1" cellpadding="10">
        <tr>
            <th>Customer</th>
            <th>Food Items</th>
            <th>Total Price</th>
            <th>Order Date</th>
            <th>Status</th>
        </tr>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($order = $result->fetch_assoc()): ?>

                <!-- Fetch food items for this order -->
                <?php
                $order_id = $order['order_id'];
                $items_res = $conn->query("SELECT f.name, oi.quantity FROM order_items oi
                                       JOIN food_items f ON oi.food_id = f.id
                                       WHERE oi.order_id = $order_id");
                $food_list = [];
                while ($item = $items_res->fetch_assoc()) {
                    $food_list[] = htmlspecialchars($item['name']) . " (x" . $item['quantity'] . ")";
                }
                ?>

                <tr>
                    <td><?= htmlspecialchars($order['customer_name']) ?></td>
                    <td><?= implode(", ", $food_list) ?></td>
                    <td>₱<?= number_format($order['total_price'], 2) ?></td>
                    <td><?= $order['created_at'] ?></td>
                    <td><?= ucfirst($order['status']) ?></td>
                </tr>

            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No finished orders found.</td>
            </tr>
        <?php endif; ?>
    </table>

</body>

</html>