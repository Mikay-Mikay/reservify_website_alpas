<?php
require_once "database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notification_id = isset($_POST['notification_id']) ? intval($_POST['notification_id']) : null;
    $action = isset($_POST['action']) ? $_POST['action'] : null;
    $reject_reason = isset($_POST['reject_reason']) ? $_POST['reject_reason'] : null; // Capture rejection reason

    if ($notification_id && $action) {
        // Get the reservation details using the notification ID
        $sql = "SELECT reservation_id, user_id, First_name, Last_name, Email, event_type, event_place, contact_number, image 
                FROM admin_notifications WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $notification_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($reservation = mysqli_fetch_assoc($result)) {
            $reservation_id = $reservation['reservation_id']; // Get reservation_id (not used in message)
            $customer_id = $reservation['user_id'];
            $first_name = $reservation['First_name'];
            $last_name = $reservation['Last_name'];
            $email = $reservation['Email'];

            $status = ($action === 'approve') ? 'approved' : 'rejected'; // Set status based on the action
            $is_read = 0; // Default value for is_read

            if ($action === 'approve') {
                // Update reservation status to 'approved'
                $sql_update = "UPDATE reservation SET status = 'approved' WHERE reservation_id = ?";
                $stmt_update = mysqli_prepare($conn, $sql_update);
                mysqli_stmt_bind_param($stmt_update, "i", $reservation_id); // Bind reservation_id
                if (mysqli_stmt_execute($stmt_update)) {
                    // Notify customer for approval without the reservation ID
                    $message = "Your reservation has been approved. You can now proceed to <a href='payment.php' style='text-decoration: underline; color: blue;'>payment</a>.";
                    $sql_notify = "INSERT INTO customer_notifications (user_id, message) VALUES (?, ?)";
                    $stmt_notify = mysqli_prepare($conn, $sql_notify);
                    mysqli_stmt_bind_param($stmt_notify, "is", $customer_id, $message);
                    mysqli_stmt_execute($stmt_notify);
                    mysqli_stmt_close($stmt_notify);

                    echo "<script>
                        alert('You have successfully approved the reservation status of customer.');
                        window.location.href = 'admin_dashboard.php?status=approved';
                    </script>";
                    exit();
                } else {
                    echo "Error updating reservation status.";
                }
                mysqli_stmt_close($stmt_update);
            } elseif ($action === 'reject') {
                // Update reservation status to 'rejected'
                $sql_update = "UPDATE reservation SET status = 'rejected' WHERE reservation_id = ?";
                $stmt_update = mysqli_prepare($conn, $sql_update);
                mysqli_stmt_bind_param($stmt_update, "i", $reservation_id); // Bind reservation_id
                if (mysqli_stmt_execute($stmt_update)) {
                    // Include the rejection reason in the message
                    $message = "Your reservation has been rejected due to the following reason: $reject_reason";

                    // Insert notification into the customer_notifications table, including the reject_reason
                    $sql_notify = "INSERT INTO customer_notifications (user_id, message) VALUES (?, ?)";
                    $stmt_notify = mysqli_prepare($conn, $sql_notify);
                    mysqli_stmt_bind_param($stmt_notify, "is", $customer_id, $message);
                    mysqli_stmt_execute($stmt_notify);
                    mysqli_stmt_close($stmt_notify);

                    echo "<script>
                        alert('You have successfully rejected the reservation status of customer.');
                        window.location.href = 'admin_dashboard.php?status=rejected';
                    </script>";
                    exit();
                } else {
                    echo "Error updating reservation status.";
                }
                mysqli_stmt_close($stmt_update);
            }
        } else {
            echo "Notification not found.";
        }

        mysqli_stmt_close($stmt);
    }
}

header("Location: admin_dashboard.php");
exit();
?>
