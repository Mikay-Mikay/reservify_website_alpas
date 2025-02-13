<?php
require 'databasee.php'; // Ensure this file has the correct database credentials

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_ID = $_POST['admin_ID']; // Ensure it matches the form's input field name
    $password = $_POST['password'];

    try {
        // Query to fetch admin details (Ensure 'roles' column is correct)
        $query = "SELECT admin_ID, password, roles FROM admin_login WHERE admin_ID = :admin_ID";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':admin_ID', $admin_ID);
        $stmt->execute();

        // Fetch result
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Compare passwords (if stored as plaintext, use simple comparison)
            if ($password === $user['password']) { // Use password_verify($password, $user['password']) if stored as hashed
                session_start();
                $_SESSION['roles'] = $user['roles']; // Store role in session
                $_SESSION['admin_ID'] = $user['admin_ID']; // Store admin_ID in session

                // Redirect to admin dashboard
                header("Location: admin_dashboard.php");
                exit();
            } else {
                echo "<script>alert('Invalid ID or Password!'); window.location.href='admin_login.php';</script>";
            }
        } else {
            echo "<script>alert('Invalid ID or Password!'); window.location.href='admin_login.php';</script>";
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
        <img src="images/reservify_logo.png" alt="PM&JI Logo" class="logo">
        <h1>PM&JI Admin</h1>
        <p>Login</p>
        <form action="admin_login.php" method="POST">
            <input type="text" name="admin_ID" placeholder="ID:" required>
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
