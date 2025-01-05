<?php
require_once "database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notification_id = isset($_POST['notification_id']) ? intval($_POST['notification_id']) : null;
    $action = isset($_POST['action']) ? $_POST['action'] : null;

    if ($notification_id && $action) {
        // Get the reservation details using the notification ID
        $sql = "SELECT reservation_id, user_id, First_name, Middle_name, Last_name, Email, event_type, event_place, contact_number, image, admin_id 
                FROM admin_notifications WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $notification_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($reservation = mysqli_fetch_assoc($result)) {
            $reservation_id = $reservation['reservation_id'];
            $customer_id = $reservation['user_id'];
            $admin_id = $reservation['admin_id'];
            $first_name = $reservation['First_name'];
            $middle_name = $reservation['Middle_name'];
            $last_name = $reservation['Last_name'];
            $email = $reservation['Email'];
            $event_type = $reservation['event_type'];
            $event_place = $reservation['event_place'];
            $contact_number = $reservation['contact_number'];
            $image = $reservation['image'];

            $status = ($action === 'approve') ? 'approved' : 'rejected'; // Set status based on the action
            $booking_id = "Booking_" . uniqid(); // Generate unique booking ID
            $is_read = 0; // Default value for is_read

            if ($action === 'approve') {
                // Update reservation status to 'approved'
                $sql_update = "UPDATE reservation SET status = 'approved' WHERE reservation_id = ?";
                $stmt_update = mysqli_prepare($conn, $sql_update);
                mysqli_stmt_bind_param($stmt_update, "s", $reservation_id);
                if (mysqli_stmt_execute($stmt_update)) {
                    // Notify customer: Insert a notification into customer_notifications table
                    $message = "Your reservation with ID $reservation_id has been approved. You can now proceed to <a href='payment.php' style='text-decoration: underline; color: blue;'>payment</a>.";

                    $sql_notify = "INSERT INTO customer_notifications (user_id, message) VALUES (?, ?)";
                    $stmt_notify = mysqli_prepare($conn, $sql_notify);
                    mysqli_stmt_bind_param($stmt_notify, "is", $customer_id, $message);
                    mysqli_stmt_execute($stmt_notify);
                    mysqli_stmt_close($stmt_notify);

                    // Display alert box with JavaScript redirect
                    echo "<script>
                        alert('You have successfully approved the reservation status of customer.');
                        window.location.href = 'admin_dashboard.php?status=approved';
                    </script>";
                    exit();
                } else {
                    // Error updating reservation status
                    echo "Error updating reservation status.";
                }
                mysqli_stmt_close($stmt_update);
            } elseif ($action === 'reject') {
                // Update reservation status to 'rejected'
                $sql_update = "UPDATE reservation SET status = 'rejected' WHERE reservation_id = ?";
                $stmt_update = mysqli_prepare($conn, $sql_update);
                mysqli_stmt_bind_param($stmt_update, "s", $reservation_id);
                if (mysqli_stmt_execute($stmt_update)) {
                    // Notify customer: Insert a notification into customer_notifications table
                    $message = "Your reservation with ID $reservation_id has been rejected.";
                    $sql_notify = "INSERT INTO customer_notifications (user_id, message) VALUES (?, ?)";
                    $stmt_notify = mysqli_prepare($conn, $sql_notify);
                    mysqli_stmt_bind_param($stmt_notify, "is", $customer_id, $message);
                    mysqli_stmt_execute($stmt_notify);
                    mysqli_stmt_close($stmt_notify);

                    // Display alert box with JavaScript redirect
                    echo "<script>
                        alert('You have successfully rejected the reservation status of customer.');
                        window.location.href = 'admin_dashboard.php?status=rejected';
                    </script>";
                    exit();
                } else {
                    // Error updating reservation status
                    echo "Error updating reservation status.";
                }
                mysqli_stmt_close($stmt_update);
            }
        } else {
            // Handle case where notification doesn't exist
            echo "Notification not found.";
        }

        mysqli_stmt_close($stmt);
    }
}

// Redirect back to admin dashboard if no action
header("Location: admin_dashboard.php");
exit();
?>
