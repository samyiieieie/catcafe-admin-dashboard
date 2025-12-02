<?php
session_start();  // MUST be at the top

include '../../database/db.php';

// Abstract Values that will hold data later
$message = "";
$username = "";
$password = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Check if username exists in admin_user table
    $stmt = $conn->prepare("SELECT * FROM admin_user WHERE username=? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        // Check password (plain text)
        if ($password === $user['password']) {

            // Store User Data in session
            $_SESSION['id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['username'] = $user['username'];

            // Redirect to admin dashboard
            header("Location: ../../pages/admin/dashboard/admin_dashboard.php");
            exit();
        } else {
            $message = "Incorrect password!";
        }
    } else {
        $message = "User does not exist!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="loginstyle.css">
    <title>Admin Login</title>
</head>

<body>
    <div class="navbar">
        <img src="assets/logo.png" alt="Logo">
    </div>

    <div class=login-container> 
        <div class="login-left">
            <img src="assets/background.png"  class="background-img" alt="Background">
            <div class="tagline">
                <p> Your comfort in a cup... </p>
                <p> with a purr </p>
            </div>
        </div>

        <div class="login-right">
            <h2>Admin Login</h2>
            <form method="post" action="">
        <!-- Input Fields-->
            <input type="text" name="username" placeholder="Username" required><br><br>
            <input type="password" name="password" placeholder="Password" required><br><br>
            <button type="submit">Login</button>
            </form>
            </div>
    </div>

    <!-- Show message -->
    <p style="color:red;"><?php echo $message; ?></p>
    
    <!-- To avoid showing admin pages when someone clicks return -->
    <script src="../../../js/login.js"></script>
</body>

</html>