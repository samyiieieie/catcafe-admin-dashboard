<?php
session_start();  // MUST be at the top

include '../../database/db.php';

// Abstract Values that will hold data later
$message = "";
$username = "";
$password = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // trim both impits
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Check if username exists in admin_user table
    $stmt = $conn->prepare("SELECT * FROM admin_user WHERE username=? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // If there is more than one row in the admin table
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
    <title>Login</title>
    <link rel="stylesheet" href="../../css/login.css">
</head>

<body>
    <nav class="navbar">
        <div class="logo">
            <img class="logo" src="../../assets/.logo.svg" alt="">
            <div class="logo-text">
                <p class="logo-bold">cozy beans</p>
                <p class="logo-cursive">café</p>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="left-container">
            <div class="caveat-text">
                <p class="caveat">Your comfort in a cup… </p>
                <p class="caveat">with a purr.</p>
            </div>
            <img src="../../assets/white-overlay.png" class="overlay back" alt="">

            <img src="../../assets/white-overlay.png" class="overlay front" alt="">
        </div>
        <div class="form">
            <img class="logo-form" src="../../assets/logo.svg" alt="">
            <div class="logo-text-big">
                <p class="logo-bold">cozy beans</p>
                <p class="logo-cursive">café</p>
            </div>
            <div class="login-text">
                <img src="../../assets/left-paw.svg" alt="">
                <h1>Login</h1>
                <img src="../../assets/left-paw.svg" alt="">
            </div>

            <form method="post" action="">

                <!-- Error Handling with wrong credentials -->
                <?php if (!empty($message)): ?>
                    <p style="color: red; font-weight: bold; text-align: center; margin-bottom: 10px;">
                        <?= $message ?>
                    </p>
                <?php endif; ?>

                <div class="inputs">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button class="filled full" type="submit">Login</button>
            </form>
        </div>
    </div>
    <script src="../../js/login.js"></script>
</body>

</html>