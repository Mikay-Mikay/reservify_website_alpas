<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userOtp = $_POST["otp"] ?? '';

    if (empty($userOtp)) {
        echo json_encode(["success" => false, "message" => "OTP is required"]);
        exit();
    }

    // Check if OTP matches session OTP
    if (isset($_SESSION["otp"]) && $_SESSION["otp"] == $userOtp) {
        unset($_SESSION["otp"]); // Remove OTP after verification
        echo json_encode(["success" => true, "message" => "OTP Verified"]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid OTP"]);
    }
}
?>
