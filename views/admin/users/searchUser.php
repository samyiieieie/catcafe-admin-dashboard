<?php

// 1. Include Database
include "../../../includes/db.php";

$search = $_GET['search'] ?? '';
$search = mysqli_real_escape_string($conn, $search);

// 2. Setting $SQL as the command to get specific information for the database
$sql = "SELECT * FROM users WHERE name LIKE '%$search'";
$result = $conn->query($sql);


?>

<!DOCTYPE html>
<html>

<body>
    <h2>Search Results for "<?= htmlspecialchars($search) ?>"</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Username</th>
            <th>Password</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= $row['username'] ?></td>
                <td><?= $row['password'] ?></td>
                <td><?= $row['role'] ?></td>
                <td>
                    <a href="admin/food/edit.php?id=<?= $row['id'] ?>">Edit</a> |
                    <a href="admin/food/delete.php?id=<?= $row['id'] ?>">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <br> <a href="../dashboard/admin_dashboard.php">Back to Dashboard</a>

</body>

</html>