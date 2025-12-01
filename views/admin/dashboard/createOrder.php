<?php

// Start a Session
session_start();

// Include Database
include '../../../includes/db.php';

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: ../../login.php");
    exit();
}

// Fetch all customers
$customers = $conn->query("SELECT id, name FROM customers ORDER BY name ASC");

// Fetch all food items
$foods = $conn->query("SELECT id, name, price FROM food_items ORDER BY name ASC");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $customer_id = intval($_POST['customer_id'] ?? 0);
    $food_ids = $_POST['food_id'] ?? [];

    if ($customer_id && count($food_ids) > 0) {
        $total_price = 0;

        // Calculate total price using a for loop
        foreach ($food_ids as $food_id) {
            $food_id = intval($food_id);
            $qty = intval($_POST['quantity_' . $food_id] ?? 1);

            $food = $conn->query("SELECT price FROM food_items WHERE id=$food_id")->fetch_assoc();
            $total_price += $food['price'] * $qty;
        }

        // Insert new data into the orders table
        $conn->query("INSERT INTO orders (customer_id, total_price) VALUES ($customer_id, $total_price)");
        $order_id = $conn->insert_id;

        // Insert new data into order_items table
        foreach ($food_ids as $food_id) {
            $food_id = intval($food_id);
            $qty = intval($_POST['quantity_' . $food_id] ?? 1);
            $conn->query("INSERT INTO order_items (order_id, food_id, quantity) VALUES ($order_id, $food_id, $qty)");
        }

        // Redirect to orders dashboard if all actions were done
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Please select a customer and at least one food item.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Create New Order</title>
</head>

<body>

    <!-- Header -->
    <h2>Create New Order</h2>

    <!-- Display error message here in full red -->
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST">

        <!-- Customer Select (Going to use a drop down menu to select from existing customers) -->
        <label for="customer_id">Select Customer:</label><br>
        <select name="customer_id" id="customer_id" required>
            <option value="">--Listed Customers--</option>
            <?php while ($c = $customers->fetch_assoc()): ?>
                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
            <?php endwhile; ?>
        </select>
        <br><br>

        <!-- Food Items with Quantity -->
        <label>Select Food Items:</label><br>
        <?php if ($foods->num_rows > 0): ?>
            <?php while ($f = $foods->fetch_assoc()): ?>
                <div>
                    <input type="checkbox" name="food_id[]" value="<?= $f['id'] ?>" id="food_<?= $f['id'] ?>">
                    <label for="food_<?= $f['id'] ?>">
                        <?= htmlspecialchars($f['name']) ?> — ₱<?= number_format($f['price'], 2) ?>
                    </label>
                    &nbsp; Quantity:
                    <input type="number" name="quantity_<?= $f['id'] ?>" min="1" value="1" style="width:50px;">
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No food items available.</p>
        <?php endif; ?>

        <br>
        <button type="submit">Create Order</button>

    </form>

    <br>
    <a href="admin_dashboard.php">Back to Admin Dashboard</a>

</body>

</html>