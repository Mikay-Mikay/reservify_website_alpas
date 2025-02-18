<?php
require 'database.php'; // Include the database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_id = $_POST['admin_id'];
    $password = $_POST['password'];

    try {
        // Query to validate admin credentials
        $query = "SELECT admin_ID, roles FROM admin_login WHERE admin_ID = ? AND password = ?";
        $stmt = $conn->prepare($query);
        
        // Bind parameters to the prepared statement
        $stmt->bind_param("ss", $admin_id, $password); // "ss" indicates both are strings

        $stmt->execute();

        // Store result to allow use of num_rows
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Fetch user details
            $stmt->bind_result($admin_id, $roles); // Bind the result columns to variables
            $stmt->fetch();

            // Start session and store user details
            session_start();
            $_SESSION['admin_id'] = $admin_id; // Store admin_id in the session
            $_SESSION['role'] = $roles;

            // Redirect based on role
            if ($roles === 'Owner') {
                header("Location: admin_dashboard.php");
            } elseif ($roles === 'Co-Owner') {
                header("Location: admin_dashboard.php");
            } elseif ($roles === 'Customer Support') {
                header("Location: admin_dashboard.php");
            } else {
                echo "<script>alert('Unauthorized access!');</script>";
            }
            exit();
        } else {
            echo "<script>alert('Invalid ID or Password!');</script>";
        }
    } catch (mysqli_sql_exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PM&JI Admin Login</title>
    <link rel="stylesheet" href="admin_login.css?v=1.1">
</head>
<body>
    <div class="login-container">
        <!-- Logo aligned to the left corner of the login container -->
        <img src="images/reservify_logo.png" alt="PM&JI Logo" class="logo">
        <h1>PM&JI Admin</h1>
        <p>Login</p>
        <form action="admin_login.php" method="POST">

            <input type="text" name="admin_id" placeholder="ID:" required>
            <div class="password-container">
                <input type="password" name="password" id="password" placeholder="Password:" required>
                <img src="images/password_hide.png.png" alt="Toggle Password" id="toggle-password" onclick="togglePassword()">
            </div>
            <button type="submit" class="login-button">Login</button>
        </form>
    </div>

    <!-- JavaScript for password toggle -->
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleImage = document.getElementById('toggle-password');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleImage.src = 'images/password_unhide.png.png'; // Show "unhide" icon
            } else {
                passwordField.type = 'password';
                toggleImage.src = 'images/password_hide.png.png'; // Show "hide" icon
            }
        }
    </script>
</body>
</html>
