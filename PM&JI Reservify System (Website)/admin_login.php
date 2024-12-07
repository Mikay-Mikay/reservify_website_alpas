<?php
require 'databasee.php'; // Include the database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_id = $_POST['admin_id'];
    $password = $_POST['password'];

    try {
        // Query to validate admin credentials
        $query = "SELECT role, fullname FROM admin_login WHERE admin_ID = :admin_id AND password = :password";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':admin_id', $admin_id);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Fetch user details
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $role = $user['role'];
            $fullname = $user['fullname'];

            // Start session and store user details
            session_start();
            $_SESSION['role'] = $role;
            $_SESSION['fullname'] = $fullname;

            // Redirect based on role
            if ($role === 'Owner') {
                header("Location: admin_dashboard.php");
            } elseif ($role === 'Co-Owner') {
                header("Location: admin_dashboard.php");
            } elseif ($role === 'Customer Support') {
                header("Location: admin_dashboard.php");
            } else {
                echo "<script>alert('Unauthorized access!');</script>";
            }
            exit();
        } else {
            echo "<script>alert('Invalid ID or Password!');</script>";
        }
    } catch (PDOException $e) {
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
    <link rel="stylesheet" href="admin_login.css">
</head>
<body>
    <div class="login-container">
        <!-- Logo aligned to the left corner of the login container -->
        <img src="images/reservify_logo_blue.png.png" alt="PM&JI Logo" class="logo">
        <h1>PM&JI Admin</h1>
        <p>Login</p>
        <form action="admin_login.php" method="POST">
            <input type="text" name="fullname" placeholder="Full Name (e.g., Maria Elena Cruz Santos):" required>
            <input type="text" name="admin_id" placeholder="ID:" required>
            <div class="password-container">
                <input type="password" name="password" id="password" placeholder="Password:" required>
                <img src="images/password_hide.png.png" alt="Toggle Password" id="toggle-password" onclick="togglePassword()">
            </div>
            <button type="submit" class="login-button">Login</button>
        </form>
        <a href="admin_forgotpass.html" class="forgot-password">Forgot Password?</a>
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
