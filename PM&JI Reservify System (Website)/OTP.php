<?php
session_start();
require_once "database.php"; // Siguraduhin na tama ang database connection


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

<style> 
.spinner {
    border: 4px solid rgba(0, 0, 0, 0.1);
    border-left-color: #3498db;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    animation: spin 1s linear infinite;
    
    /* Centering the spinner */
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    margin: 10px auto; /* Centers horizontally */
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

</style>
<body>
    <form action="" method="POST" id="forgotPasswordForm">
        <h1>Forgot Password</h1>
        <div class="input-box">
            <input type="email" name="email" placeholder="Enter your email:" id="email" required>
        </div>
        <div class="text">
            <p>Enter your email to verify that it's you</p>
        </div>
        <button type="button" class="btn" onclick="sendOTP()">Send OTP</button>
        <div id="send-loading-spinner" class="spinner" style="display: none;"></div>

    
        <div class="email-verify" style="display: none;">
    <input type="text" id="otp-input" placeholder="Enter the OTP sent to your email">
    <button type="button" class="btn" id="otp_btn" onclick="verifyOTP()">Verify</button>
    <div id="verify-loading-spinner" class="spinner" style="display: none;"></div>
</div>
    </form>

    <script>
function sendOTP() {
    const email = document.getElementById('email').value;
    const otpVerifySection = document.querySelector('.email-verify');
    const sendOTPButton = document.querySelector('.btn');
    const sendSpinner = document.getElementById('send-loading-spinner');

    if (!email) {
        alert("Please enter your email!");
        return;
    }

    // Disable button at ipakita ang loading spinner habang naghihintay
    sendOTPButton.disabled = true;
    sendSpinner.style.display = "block";

    fetch("send_otp_.php", {
        method: "POST",
        body: new URLSearchParams({ email: email }),
        headers: { "Content-Type": "application/x-www-form-urlencoded" }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("OTP sent to your email: " + email);
            otpVerifySection.style.display = "flex";
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(error => {
        console.error("Error sending OTP:", error);
        alert("An error occurred while sending OTP. Please try again.");
    })
    .finally(() => {
        // Itago ang spinner at enable ulit ang button
        sendSpinner.style.display = "none";
        sendOTPButton.disabled = false;
    });
}

function verifyOTP() {
    const otpInput = document.getElementById('otp-input').value;
    const verifyButton = document.getElementById('otp_btn');
    const verifySpinner = document.getElementById('verify-loading-spinner');

    if (!otpInput) {
        alert("Please enter OTP!");
        return;
    }

    // Disable button at ipakita ang loading spinner habang naghihintay
    verifyButton.disabled = true;
    verifySpinner.style.display = "block";

    fetch("verify_otp.php", {
        method: "POST",
        body: new URLSearchParams({ otp: otpInput }),
        headers: { "Content-Type": "application/x-www-form-urlencoded" }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("OTP Verified Successfully!");
            window.location.href = "newpassword.php"; // Redirect kung successful
        } else {
            alert("Invalid OTP. Please try again.");
        }
    })
    .catch(error => {
        console.error("Error verifying OTP:", error);
        alert("An error occurred while verifying OTP.");
    })
    .finally(() => {
        // Itago ang spinner at enable ulit ang button
        verifySpinner.style.display = "none";
        verifyButton.disabled = false;
    });
}

</script>

</body>
</html>



