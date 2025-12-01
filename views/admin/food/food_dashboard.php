<?php
session_start();
include '../../../includes/db.php'; // Adjust path if needed

// Redirect if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: ../../login.php");
    exit();
}

// Handle search
$search = $_GET['search'] ?? '';
$search_sql = $search ? "WHERE name LIKE '%$search%'" : "";

// Fetch food items
$result = $conn->query("SELECT * FROM food_items $search_sql ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Food Items Dashboard</title>
</head>

<body>

    <h1>Food Items</h1>
    <h3>Welcome, <?php echo $_SESSION['name']; ?></h3>


    <!-- Navigation -->
    <p id="nav-bar">
        <a href="../dashboard/admin_dashboard.php">Dashboard</a>
        <a href="food_dashboard.php">Food Items</a>
        <a href="../users/customer_dashboard.php">Customers</a>
        <a href="../orders/order_history_dashboard.php">Finished Orders</a>
        <a href="../../login/logout.php">Logout</a>
    </p>

    <!-- Search Bar -->
    <form method="get" action="">
        <input type="text" name="search" placeholder="Search by name" value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
    </form>


    <!-- Add new food item -->
    <p><a href="addFood.php">Add New Food Item</a></p>

    <!-- Food items table -->
    <table border="1" cellpadding="10">
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($food = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($food['name']); ?></td>
                    <td><?php echo htmlspecialchars($food['description']); ?></td>
                    <td>₱<?php echo number_format($food['price'], 2); ?></td>
                    <td>
                        <a href="editFood.php?id=<?php echo $food['id']; ?>">Edit</a> |
                        <a href="deleteFood.php?id=<?php echo $food['id']; ?>" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No food items found.</td>
            </tr>
        <?php endif; ?>
    </table>

</body>

</html>