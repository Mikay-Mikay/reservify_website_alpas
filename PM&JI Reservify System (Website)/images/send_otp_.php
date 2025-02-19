<?php
session_start();
require_once "database.php"; // Siguraduhin na tama ang database connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer
require 'vendor/autoload.php'; // Make sure this path is correct


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"] ?? '';

    if (empty($email)) {
        echo json_encode(["success" => false, "message" => "Email is required"]);
        exit();
    }

    // Generate OTP (6-digit code)
    $otpCode = rand(100000, 999999);
    $_SESSION["otp"] = $otpCode;
    $_SESSION["reset_email"] = $email;

    // Setup PHPMailer
    $mail = new PHPMailer(true);
    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'pmjireservify@gmail.com'; // Palitan ng iyong Gmail
        $mail->Password = 'svoa zdpp dktf izld'; // Gamitin ang App Password mula sa Google
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email Settings
        $mail->setFrom('pmjireservify@gmail.com', 'PM&JI Reservify'); // Sender email
        $mail->addAddress($email); // Recipient email
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = "
            <h2>Hello,</h2>
            <p>Your OTP for password reset is:</p>
            <h1 style='color: blue;'>$otpCode</h1>
            <p>Please use this code to verify your request.</p>
            <p>If you did not request a password reset, please ignore this email.</p>
        ";

        // Send Email
        $mail->send();
        echo json_encode(["success" => true, "message" => "OTP sent to email"]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Mailer Error: " . $mail->ErrorInfo]);
    }
}
?>
