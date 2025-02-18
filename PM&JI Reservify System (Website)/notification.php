<?php
require_once "database.php";

// Start the session to access the customer_id
session_start();

// Check if the customer_id exists in the session
if (isset($_SESSION['user_id'])) {
    $customer_id = $_SESSION['user_id'];

    // Update the notification status to 'read' when the page is accessed
    $update_sql = "UPDATE customer_notifications SET status = 'read' WHERE user_id = ? AND status = 'unread'";
    $update_stmt = mysqli_prepare($conn, $update_sql);

    if (!$update_stmt) {
        echo "Error in update query preparation: " . mysqli_error($conn);
        exit();
    }

    mysqli_stmt_bind_param($update_stmt, "i", $customer_id);
    mysqli_stmt_execute($update_stmt);
    mysqli_stmt_close($update_stmt);

    // Get the notifications from the database
    $sql = "SELECT message, created_at, status FROM customer_notifications WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = mysqli_prepare($conn, $sql);

    // Check for SQL error
    if (!$stmt) {
        echo "Error in query preparation: " . mysqli_error($conn);
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $customer_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    // Redirect to login page if customer is not logged in
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="notification.css">
</head>
<body>
    <h1>Notifications</h1>
    <div id="notification-content">
        <?php
        // Display the notifications
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $formatted_date = date("F j, Y, g:i a", strtotime($row['created_at']));
                $status_class = $row['status'] === 'unread' ? 'unread' : 'read';
                echo "<div class='notification $status_class'>";
                echo "<p><strong>" . $formatted_date . ":</strong> " . htmlspecialchars($row['message']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No notifications available.</p>";
        }

        // Close statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        ?>
    </div>
    <a href="reservation.php" class="back-button">Back to Reservations</a>

    <style>
        .notification.unread {
            font-weight: bold;
        }
        .notification.read {
            font-weight: normal;
        }
    </style>
</body>
</html>
