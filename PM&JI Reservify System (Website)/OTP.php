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
            // Email found, redirect to newpassword.php
            header("Location: newpassword.php?email=" . urlencode($Email));
            exit();  // Make sure to stop the script after the redirection
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
    <link rel="stylesheet" href="OTP.css">
    <script src="https://smtpjs.com/v3/smtp.js"></script>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
</head>

<body>
    <form action="" method="POST" id="forgotPasswordForm">
        <h1>Forgot Password</h1>
        <div class="input-box">
            <input type="email" name="Email" placeholder="Enter your email:" id="email" required>
        </div>
        <div class="text">
            <p>Enter your email to verify that it's you</p>
        </div>
        <button type="button" class="btn" onclick="sendOTP()">Send OTP</button>
    
        <!-- This section is hidden by default -->
        <div class="email-verify" style="display: none;">
            <input type="text" id="otp-input" placeholder="Enter the OTP sent to your email">
            <!-- Verify button dynamically displayed -->
            <button type="button" class="btn" id="otp_btn" style="display: none;">Verify</button>
        </div>
    </form>

    <script src="OTP Using JavaScript.js"></script>
</body>

</html>
