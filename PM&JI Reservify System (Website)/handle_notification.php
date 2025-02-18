<?php
require_once "database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notification_id = isset($_POST['notification_id']) ? intval($_POST['notification_id']) : null;
    $action = isset($_POST['action']) ? $_POST['action'] : null;
    $reject_reason = isset($_POST['reject_reason']) ? $_POST['reject_reason'] : null;

    if ($notification_id && $action) {
        // Fetch reservation or payment details using notification ID
        $sql = "SELECT reservation_id, user_id, First_name, Last_name, Email, event_type, event_place, contact_number, image, payment_id 
                FROM admin_notifications WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $notification_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($notification = mysqli_fetch_assoc($result)) {
            $reservation_id = $notification['reservation_id'];
            $customer_id = $notification['user_id'];
            $payment_id = $notification['payment_id'];

            // Fetch user_id from payment if it's missing from admin_notifications
            if ($customer_id === null && $payment_id !== null) {
                $sql_get_user = "SELECT user_id FROM payment WHERE payment_id = ?";
                $stmt_get_user = mysqli_prepare($conn, $sql_get_user);
                mysqli_stmt_bind_param($stmt_get_user, "i", $payment_id);
                mysqli_stmt_execute($stmt_get_user);
                $result_get_user = mysqli_stmt_get_result($stmt_get_user);
                
                if ($row = mysqli_fetch_assoc($result_get_user)) {
                    $customer_id = $row['user_id'];
                }
                mysqli_stmt_close($stmt_get_user);
            }

            // Check if the user exists
            $sql_check_user = "SELECT id FROM test_registration WHERE id = ?";
            $stmt_check_user = mysqli_prepare($conn, $sql_check_user);
            mysqli_stmt_bind_param($stmt_check_user, "i", $customer_id);
            mysqli_stmt_execute($stmt_check_user);
            $result_check = mysqli_stmt_get_result($stmt_check_user);

            if (mysqli_num_rows($result_check) > 0) {
                $status = ($action === 'approve') ? 'approved' : 'rejected';
                
                if ($payment_id) {
                    // Handle Payment Approval/Rejection
                    updateStatusAndNotify($conn, 'payment', $payment_id, $customer_id, $status, $reservation_id, $reject_reason);
                } elseif ($reservation_id) {
                    // Handle Reservation Approval/Rejection
                    updateStatusAndNotify($conn, 'reservation', $reservation_id, $customer_id, $status, null, $reject_reason);
                }
                
                // Mark notification as read
                $sql_update_notification = "UPDATE admin_notifications SET is_read = 1 WHERE id = ?";
                $stmt_update_notification = mysqli_prepare($conn, $sql_update_notification);
                mysqli_stmt_bind_param($stmt_update_notification, "i", $notification_id);
                mysqli_stmt_execute($stmt_update_notification);
                mysqli_stmt_close($stmt_update_notification);

                // Redirect with success message
                $alert_msg = "You have successfully " . ($status === 'approved' ? 'approved' : 'rejected') . " the " . ($payment_id ? "payment" : "reservation") . " status of the customer.";
                echo "<script>
                    alert('$alert_msg');
                    window.location.href = 'admin_dashboard.php?status=$status';
                </script>";
                exit();
            } else {
                echo "<script>alert('Invalid user ID: No corresponding user found in the test_registration table.');</script>";
            }
            mysqli_stmt_close($stmt_check_user);
        } else {
            echo "<script>alert('Notification not found.');</script>";
        }
        mysqli_stmt_close($stmt);
    }
}

// Function to update status and send notifications
function updateStatusAndNotify($conn, $type, $id, $user_id, $status, $reservation_id = null, $reject_reason = null) {
    $table = ($type === 'payment') ? 'payment' : 'reservation';
    $column = ($type === 'payment') ? 'payment_id' : 'reservation_id';

    // Update the status in the respective table
    $sql_update = "UPDATE $table SET status = ? WHERE $column = ?";
    $stmt_update = mysqli_prepare($conn, $sql_update);
    mysqli_stmt_bind_param($stmt_update, "si", $status, $id);
    
    if (mysqli_stmt_execute($stmt_update)) {
        if ($status === 'approved') {
            if ($type === 'payment') {
                $message = "Your payment for Reservation ID: $reservation_id has been approved! You can now proceed to <a href='Booking summary.php' style='text-decoration: underline; color: blue;'>Booking Summary</a>.";
            } else {
                $message = "Your reservation for Reservation ID: $reservation_id has been approved! You can now proceed to <a href='payment.php' style='text-decoration: underline; color: blue;'>payment</a>.";
            }
        } else {
            $message = "Your $type has been rejected. Reason: $reject_reason.";
        }

        insertNotification($conn, $user_id, $message, ($type === 'payment') ? $id : null, $reject_reason);
    } else {
        echo "<script>alert('Error updating $type status.');</script>";
    }
    mysqli_stmt_close($stmt_update);
}


// Function to insert notification for the customer
function insertNotification($conn, $user_id, $message, $payment_id = null, $reject_reason = null) {
    $sql_notify = ($reject_reason) ? 
        "INSERT INTO customer_notifications (user_id, message, reject_reason, payment_id) VALUES (?, ?, ?, ?)" :
        "INSERT INTO customer_notifications (user_id, message, payment_id) VALUES (?, ?, ?)";
    
    $stmt_notify = mysqli_prepare($conn, $sql_notify);

    if ($reject_reason) {
        mysqli_stmt_bind_param($stmt_notify, "issi", $user_id, $message, $reject_reason, $payment_id);
    } else {
        if ($payment_id === null) {
            $sql_notify = "INSERT INTO customer_notifications (user_id, message) VALUES (?, ?)";
            $stmt_notify = mysqli_prepare($conn, $sql_notify);
            mysqli_stmt_bind_param($stmt_notify, "is", $user_id, $message);
        } else {
            mysqli_stmt_bind_param($stmt_notify, "isi", $user_id, $message, $payment_id);
        }
    }

    if (!mysqli_stmt_execute($stmt_notify)) {
        echo "<script>alert('Error inserting notification into customer_notifications.');</script>";
    }
    mysqli_stmt_close($stmt_notify);
}

?>