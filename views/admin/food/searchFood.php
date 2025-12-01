<?php
include '../../../includes/db.php'; // Adjust path if needed

$search = $_GET['search'] ?? '';
$search = mysqli_real_escape_string($conn, $search);

// Fetch food items matching search
$sql = "SELECT * FROM food_items WHERE name LIKE '%$search%' ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
</head>
<body>

<h2>Search Results for "<?= htmlspecialchars($search) ?>"</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Price</th>
        <th>Description</th>
        <th>Actions</th>
    </tr>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= number_format($row['price'], 2) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td>
                    <a href="edit_food.php?id=<?= $row['id'] ?>">Edit</a> |
                    <a href="delete_food.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">No food items found.</td>
        </tr>
    <?php endif; ?>
</table>

<br>
<a href="food_dashboard.php">Back to Food Dashboard</a>

</body>
</html>
