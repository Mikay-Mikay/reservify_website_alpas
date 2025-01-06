<?php
session_start();
require_once 'database.php';

// Check if the user is logged in
$user_id = $_SESSION['id'] ?? null;

if ($user_id === null) {
    // If no user is logged in, return an empty JSON response
    echo json_encode([]);
    exit;
}

// Query to fetch notifications for the logged-in customer
$notifications = [];
$sql = "SELECT * FROM customer_notifications WHERE user_id = ? ORDER BY created_at DESC";

if ($stmt = $conn->prepare($sql)) {
    // Bind the user ID to the query
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    
    // Get the query result
    $result = $stmt->get_result();
    
    // Fetch notifications and modify the message if necessary
    while ($row = $result->fetch_assoc()) {
        // Modify the message if the reservation is approved
        if ($row['status'] == 'approved') {
            $row['message'] = "Your reservation is approved. You can now proceed to <a href='payment.php' style='text-decoration: underline; color: blue;'>payment</a>.";
        }

        // Add the modified notification to the array
        $notifications[] = $row;
    }
    
    // Close the statement
    $stmt->close();
} else {
    // If there is an error preparing the statement, return an error message
    echo json_encode(["error" => "Error preparing the statement: " . $conn->error]);
    exit;
}

// Close the database connection
$conn->close();

// Return notifications as JSON
echo json_encode($notifications);
?>
