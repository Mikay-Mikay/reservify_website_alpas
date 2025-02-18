<?php
session_start(); // Start the session

$error_message = ""; // Initialize the error message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "database.php";

    // Retrieve form data
    $Email = trim($_POST['Email'] ?? '');
    $Password = trim($_POST['Password'] ?? '');

    // Check if fields are not empty
    if (empty($Email) || empty($Password)) {
        $error_message = "Both Email and Password are required.";
    } else {
        // SQL query
        $sql = "SELECT * FROM test_registration WHERE Email = ?";
        $stmt = mysqli_stmt_init($conn);

        if (mysqli_stmt_prepare($stmt, $sql)) {
            // Bind parameters and execute
            mysqli_stmt_bind_param($stmt, "s", $Email);
            mysqli_stmt_execute($stmt);

            // Get result
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);

            if ($user && password_verify($Password, $user['Password'])) {
                // Login successful, store user data in session
                $_SESSION['id'] = $user['id']; // Adjust 'id' based on your table's column

                // Redirect to reservation page
                header("Location: About Us.php");
                exit();
            } else {
                $error_message = "Invalid email or password.";
            }
        } else {
            // Display error for debugging
            $error_message = "SQL Error: " . mysqli_error($conn);
        }

        // Close the statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
</head>

<body>
    <form action="" method="POST" id="loginForm">
        <h1>Login</h1>
        <div class="input-box">
            <input type="email" name="Email" placeholder="Email" id="username" required>
            <i class='bx bxs-envelope'></i>
        </div>
        <div class="input-box">
            <input type="password" name="Password" placeholder="Password" id="password" required>
            <i class='bx bxs-lock-alt'></i>
        </div>
        <button type="submit" class="btn">Login</button>

        <!-- Error message displayed here -->
        <?php if (!empty($error_message)) : ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <style>
            .error-message {
                margin-top: 10px;
                text-align: center;
                color: red !important; /* Red color for the error message */
                font-size: 10px; /* Adjust font size */
                font-weight: bold;
                font-family: 'Poppins', sans-serif; /* Ensure consistent font */
            }
        </style>

        <div class="register-link">
            <p>Don't have an account? <a href="Sign up.php">Sign Up</a><br><a href="OTP.php" class="forgot-password">Forgot Password?</a></p>
        </div>

    </form>
</body>

</html>
