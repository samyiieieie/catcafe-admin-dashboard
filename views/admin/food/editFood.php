<?php
include '../../../includes/db.php'; // Adjust path if needed

// 1. Check if ID is provided
if (!isset($_GET['id'])) {
    die("Food item ID not specified.");
}

$id = $_GET['id'];

// 2. Fetch the current food item
$result = $conn->query("SELECT * FROM food_items WHERE id = $id");
if ($result->num_rows == 0) {
    die("Food item not found.");
}

$item = $result->fetch_assoc();

// 3. Handle form submission
if (isset($_POST['update'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $sql = "UPDATE food_items 
            SET name='$name', price='$price', description='$description'
            WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: food_dashboard.php"); // Back to food dashboard
        exit;
    } else {
        echo "Error updating food item: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Food Item</title>
</head>
<body>

<h2>Edit Food Item</h2>

<form method="POST">

    Name: <input type="text" name="name" value="<?= htmlspecialchars($item['name']) ?>" required><br><br>

    Price: <input type="number" name="price" step="0.01" value="<?= $item['price'] ?>" required><br><br>

    Description: <textarea name="description"><?= htmlspecialchars($item['description']) ?></textarea><br><br>

    <button name="update">Update</button>
</form>

<br>
<a href="food_dashboard.php">Back to Food Dashboard</a>

</body>
</html>
