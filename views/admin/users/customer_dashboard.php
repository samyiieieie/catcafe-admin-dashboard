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
$search = mysqli_real_escape_string($conn, $search);

// Fetch customers
$sql = "SELECT * FROM customers WHERE name LIKE '%$search%' ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Customer Dashboard</title>
</head>

<body>

    <h1>Customer Dashboard</h1>
    <h3>Welcome, <?php echo $_SESSION['name']; ?></h3>


    <!-- Navigation -->
    <p id="nav-bar">
        <a href="../dashboard/admin_dashboard.php">Dashboard</a> 
        <a href="../food/food_dashboard.php">Food Items</a> 
        <a href="customer_dashboard.php">Customers</a> 
        <a href="../orders/order_history_dashboard.php">Finished Orders</a> 
        <a href="../../login/logout.php">Logout</a>

    </p>

    <!-- Search -->
    <form method="get" action="">
        <input type="text" name="search" placeholder="Search by name" value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
    </form>

    <!-- Add new customer -->
    <p><a href="createUser.php">Add New Customer</a></p>

    <!-- Customers table -->
    <table border="1" cellpadding="10">
        <tr>
            <th>Name</th>
            <th>Age</th>
            <th>Contact Number</th>
            <th>Address</th>
            <th>Actions</th>
        </tr>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($customer = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($customer['name']) ?></td>
                    <td><?= $customer['age'] ?></td>
                    <td><?= htmlspecialchars($customer['contact_number']) ?></td>
                    <td><?= htmlspecialchars($customer['current_address']) ?></td>
                    <td>
                        <a href="editUser.php?id=<?= $customer['id'] ?>">Edit</a> |
                        <a href="deleteUser.php?id=<?= $customer['id'] ?>" onclick="return confirm('Are you sure you want to delete this customer?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No customers found.</td>
            </tr>
        <?php endif; ?>
    </table>

</body>

</html>