<?php

// Start a Session
session_start();

// Include the Connect Database File
include '../../../database/db.php';

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
    <link rel="stylesheet" href="../../../css/overview.css">
</head>

<body>

    <main>
        <!-- Header + Welcome Message with name column from DB -->
        <h1>Admin Dashboard</h1>
        <h2>Welcome,
            <span class="name">
                <?php echo $_SESSION['name']; ?>
            </span>
            !
        </h2>
        <section class="tables">
            <article class="summary">
                <!-- Sales Summary -->
                <div class="sales">
                    <div class="summary-name">
                        <div class="icon">
                            <img src="../../../assets/icons/sales-icon.svg" alt="">
                        </div>
                        <p>Total Sales</p>
                    </div>
                    <p class="card-info">
                        ₱<?php echo $total_sales ?? 0; ?>
                    </p>
                </div>

                <!-- Orders Summary -->
                <div class="summary-card">
                    <div class="summary-name">
                        <div class="icon">
                            <img src="../../../assets/icons/orders-icon.svg" alt="">
                        </div>

                        <p>Orders</p>
                    </div>

                    <p class="card-info">
                        <?php echo $total_orders; ?>
                    </p>
                </div>
                <!-- Food Summary -->
                <div class="summary-card">
                    <div class="summary-name">
                        <div class="icon">
                            <img src="../../../assets/icons/food-icon.svg" alt="">
                        </div>

                        <p>Food Inventory</p>
                    </div>

                    <p class="card-info">
                        <?php echo $total_food; ?>
                    </p>
                </div>
                <!-- Customers Summary -->
                <div class="summary-card">
                    <div class="summary-name">
                        <div class="icon">
                            <img src="../../../assets/icons/customers-icon.svg" alt="">
                        </div>

                        <p>Customers</p>
                    </div>

                    <p class="card-info">
                        <?php echo $total_customers; ?>
                    </p>
                </div>
            </article>
            <article class="pending-orders">
                <!-- Pending Orders Table -->

                <div class="pending-orders-name">
                    <div class="icon">
                        <img src="../../../assets/icons/pending-orders.svg" alt="">
                    </div>
                    <h2>Pending Orders</h2>
                </div>


                <!-- Create Order Button -->
                <button onclick="window.location.href='createOrder.php'" class="outline create-order">
                    <img src="../../../assets/icons/add-icon.svg" alt="">
                    Create New Order
                </button>


                <table border="1" cellpadding="10" id="pending-table">
                    <tr class="row-border">
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
                                <td class="actions">
                                    <a href="#" class="btn finish-order" data-id="<?= $order['order_id'] ?>">
                                        <img src="../../../assets/icons/check-icon.svg" alt="">
                                        Finish
                                    </a>
                                    <a href="#" class="btn delete-order" data-id="<?= $order['order_id'] ?>">
                                        <img src="../../../assets/icons/trash-icon.svg" alt="">
                                        Delete
                                    </a>
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
            </article>
        </section>


        <script src="../../../js/admin_dashboard.js"></script>
    </main>

</body>

</html>