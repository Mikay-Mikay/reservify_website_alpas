<?php
require_once "database.php";

// Get the notification ID from the URL
$notification_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($notification_id) {
    // Fetch the notification from the database
    $sql = "SELECT * FROM payment_notification WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $notification_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $notification = $row;

        // Extract the date from the notification (assuming it's stored in the 'created_at' field)
        $date = new DateTime($notification['created_at']);
        $formatted_date = $date->format('Ymd'); // Format as YYYYMMDD (e.g., 20241130)
        
        // Get the latest customer number (CUST###) for the given date
        $sql_customer = "SELECT MAX(SUBSTRING(reservation_id, 10, 3)) AS last_customer 
                         FROM reservation
                         WHERE reservation_id LIKE 'PMJI-$formatted_date-CUST%'";
                         
        $stmt_customer = mysqli_prepare($conn, $sql_customer);
        mysqli_stmt_execute($stmt_customer);
        $result_customer = mysqli_stmt_get_result($stmt_customer);
        
        $customer_data = mysqli_fetch_assoc($result_customer);
        $last_customer = $customer_data['last_customer'];

        // Increment the customer number or start from 1 if there are no previous customers
        if ($last_customer) {
            $customer_number = intval($last_customer) + 1;
        } else {
            $customer_number = 1; // Start from CUST001 if no customers exist
        }
        
        // Format the customer number to 3 digits
        $customer_number_formatted = str_pad($customer_number, 3, '0', STR_PAD_LEFT);

        // Construct the reservation ID
        $reservation_id = "PMJI-$formatted_date-CUST$customer_number_formatted";

        // Get the payment details associated with the reservation ID
        $sql_payment = "SELECT * FROM payments WHERE reservation_id = ?";
        $stmt_payment = mysqli_prepare($conn, $sql_payment);
        mysqli_stmt_bind_param($stmt_payment, "s", $reservation_id);
        mysqli_stmt_execute($stmt_payment);
        $result_payment = mysqli_stmt_get_result($stmt_payment);

        $payment = mysqli_fetch_assoc($result_payment);

        // Close statements
        mysqli_stmt_close($stmt_payment);
        mysqli_stmt_close($stmt_customer);
    } else {
        // Redirect to error page if notification not found
        header("Location: error_page.php");
        exit();
    }

    // Close the main statement
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    // Redirect to the admin dashboard if notification ID is not set
    header("Location: admin_dashboard.php");
    exit();
}
?>

<!-- HTML to display the notification details -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Details</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #56aeff;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Main container for notification details */
        .notification-details {
            width: 80%;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        /* Heading styles */
        .notification-details h1 {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Paragraph styles for text content */
        .notification-details p {
            font-size: 16px;
            line-height: 1.6;
            margin: 10px 0;
        }

        /* Bold text for labels */
        .notification-details p strong {
            font-weight: 600;
        }

        /* Image styling */
        .notification-details img {
            display: block;
            margin: 20px 0;
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        /* Link for back button */
        .notification-details .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        /* Hover effect for back button */
        .notification-details .back-button:hover {
            background-color: #0056b3;
        }

        /* Responsive Design for Mobile */
        @media (max-width: 768px) {
            .notification-details {
                width: 90%;
                padding: 15px;
            }

            .notification-details h1 {
                font-size: 24px;
            }

            .notification-details p {
                font-size: 14px;
            }

            .notification-details .back-button {
                font-size: 14px;
                padding: 8px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="notification-details">
        <h1>Notification Details</h1>
        <p><strong>Notification ID:</strong> <?php echo htmlspecialchars($notification['id']); ?></p>
        <p><strong>Message:</strong> <?php echo htmlspecialchars($notification['message']); ?></p>
        <p><strong>Reservation ID:</strong> <?php echo htmlspecialchars($reservation_id); ?></p>
        <p><strong>Created At:</strong> <?php echo htmlspecialchars($notification['created_at']); ?></p>

        <!-- Display Payment Method -->
        <?php if (!empty($payment)): ?>
            <h2>Payment Details</h2>
            <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($payment['payment_method']); ?></p>
            <p><strong>Payment Amount:</strong> <?php echo htmlspecialchars($payment['payment_amount']); ?></p>
            <p><strong>Payment Status:</strong> <?php echo htmlspecialchars($payment['payment_status']); ?></p>
        <?php else: ?>
            <p>No payment details found.</p>
        <?php endif; ?>

        <a href="admin_dashboard.php" class="back-button">Back to Dashboard</a>
    </div>
</body>
</html>
