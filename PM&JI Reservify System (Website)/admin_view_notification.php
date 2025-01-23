<?php
require_once "database.php";

// Get the notification ID from the URL
$notification_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($notification_id) {
    // Fetch the notification from the database
    $sql = "SELECT * FROM admin_notifications WHERE id = ?";
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
                         WHERE reservation_id LIKE 'PMJI-$formatted_date-CUST%'"; // Adjust table name if needed
                         
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
        $customer_number_formatted = str_pad($customer_number, 3, '0', STR_PAD_LEFT); // Format as CUST001, CUST002, etc.

        // Construct the reservation ID
        $reservation_id = "PMJI-$formatted_date-CUST$customer_number_formatted";

        // Close the statement for the customer query
        mysqli_stmt_close($stmt_customer);
    } else {
        header("Location: error_page.php");
        exit();
    }

    // Close the main statement
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    header("Location: admin_dashboard.php");
    exit();
}
?>

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

        /* Button container for Approve and Reject buttons */
        .notification-details .button-container {
            display: flex;
            justify-content: flex-start; /* Align buttons to the left */
            gap: 10px; /* Space between buttons */
            margin-top: 20px; /* Add space above buttons */
        }

        /* Approve and Reject button styles */
        .notification-details .approve-button, .notification-details .reject-button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        /* Approve button color */
        .notification-details .approve-button {
            background-color: #28a745;
            color: #fff;
        }

        /* Reject button color */
        .notification-details .reject-button {
            background-color: #dc3545;
            color: #fff;
        }

        /* Hover effect for buttons */
        .notification-details .approve-button:hover {
            background-color: #218838;
        }

        .notification-details .reject-button:hover {
            background-color: #c82333;
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

            .notification-details .back-button, .notification-details .approve-button, .notification-details .reject-button {
                font-size: 14px;
                padding: 8px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="notification-details">
        <h1>Notification Details</h1>
        <p><strong>Reservation ID:</strong> <?php echo htmlspecialchars($reservation_id); ?></p>
        <p><strong>Message:</strong> <?php echo htmlspecialchars($notification['message']); ?></p>
        <p><strong>Created At:</strong> <?php echo htmlspecialchars($notification['created_at']); ?></p>

        <!-- Display the image -->
<?php
if (!empty($notification['image'])) {
    // Add the correct path to the image file (assuming 'images/' is your folder)
    $file_path = 'images/' . htmlspecialchars($notification['image']);
    
    if (file_exists($file_path)) {
        echo '<p><strong>Image:</strong></p>';
        echo '<img src="' . $file_path . '" alt="Notification Image" style="max-width: 100%; height: auto;">';
    } else {
        echo '<p>Image file not found at: ' . $file_path . '</p>';
    }
} else {
    echo '<p>No image associated with this notification.</p>';
}
?>


        <!-- Approve and Reject Buttons -->
        <form action="handle_notification.php" method="POST">
            <input type="hidden" name="notification_id" value="<?php echo htmlspecialchars($notification_id); ?>">
            
            <!-- Button container for inline buttons -->
            <div class="button-container">
                <button type="submit" name="action" value="approve" class="approve-button">Approve</button>
                <button type="submit" name="action" value="reject" class="reject-button">Reject</button>
            </div>
        </form>

        <a href="admin_dashboard.php" class="back-button">Back to Dashboard</a>
    </div>
</body>
</html>
