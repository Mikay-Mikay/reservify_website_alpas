<?php
$error_message = ""; // Initialize the error message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "database.php";

    // Retrieve form data
    $Email = $_POST['Email'];

    // SQL query to check if the email exists in the database
    $sql = "SELECT * FROM test_registration WHERE Email = ?";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $sql)) {
        // Bind parameters and execute
        mysqli_stmt_bind_param($stmt, "s", $Email);
        mysqli_stmt_execute($stmt);

        // Get result
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        // Check if user exists
        if ($user) {
            // Email found, proceed with password reset or next step
            // Redirect to the password reset page or send reset email
            header("Location: reset-password.php?email=" . urlencode($Email));
            exit();
        } else {
            // Email not found, show error message
            $error_message = "Email not found.";
        }
    } else {
        $error_message = "Error processing the request. Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="forgot password.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
</head>

<body>
    <form action="" method="POST" id="forgotPasswordForm">
        <h1>Forgot Password</h1>
        <div class="input-box">
            <input type="email" name="Email" placeholder="Enter your email" id="username" required>
            <i class='bx bxs-envelope'></i>
        </div>
        <div class="text">
            <p>Please input your email to verify it's you.</p>
        </div>
        <button type="submit" class="btn">Next</button>

        <!-- Error message displayed here -->
        <?php if (!empty($error_message)) : ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
    </form>

    <style>
        .error-message {
            margin-top: 10px;
            text-align: center;
            color: red !important; /* Red color for the error message */
            font-size: 12px; /* Adjust font size */
            font-weight: bold;
            font-family: 'Poppins', sans-serif; /* Ensure consistent font */
        }
    </style>
</body>

</html>
