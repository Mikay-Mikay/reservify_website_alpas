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
    <link rel="stylesheet" href="admin_view_notification.css?v=1.1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <title>Notification Details</title>
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
        <!-- Approve Button -->
        <button type="submit" name="action" value="approve" class="approve-button">Approve</button>

        <!-- Reject Button -->
        <button type="button" id="reject-button" class="reject-button">Reject</button>

        <!-- Back to Dashboard Button -->
        <a href="admin_dashboard.php" class="back-button">Back to Dashboard</a>
    </div>
</form>

<!-- Modal for Reject Reason -->
<div id="reject-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close-button" id="close-modal">&times;</span>
        
        <h2>Reason for Rejection</h2>
        <form action="handle_notification.php" method="post">
            <textarea id="reject-reason" name="reject_reason" rows="4" cols="40" placeholder="Enter reason for rejection"></textarea>
            <div class="modal-actions">
                <button type="submit" name="action" value="reject" class="submit-reject-button">Submit</button>
            </div>
            <input type="hidden" name="notification_id" value="<?php echo htmlspecialchars($notification_id); ?>">
        </form>
    </div>
</div>




</div>

<script>
// Show the modal when the "Reject" button is clicked
document.getElementById("reject-button").addEventListener("click", function () {
    const modal = document.getElementById("reject-modal");
    modal.style.display = "flex";
});

// Close the modal when the close button is clicked
document.getElementById("close-modal").addEventListener("click", function () {
    const modal = document.getElementById("reject-modal");
    modal.style.display = "none";
});

// Close the modal when clicking outside the modal content
window.addEventListener("click", function (event) {
    const modal = document.getElementById("reject-modal");
    if (event.target === modal) {
        modal.style.display = "none";
    }
});
</script>
</body>

</html>
