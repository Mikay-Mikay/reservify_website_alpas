<?php
include_once 'database.php';
session_start();

// Check if the user is an admin
if (!isset($_SESSION['fullname'])) {
    echo "Error: Not logged in as admin!";
    exit();
}

// Ensure that the event ID is passed via POST
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = $_POST['id'];  // Use 'id' instead of 'eventId'

    // Log the id for debugging
    error_log("Attempting to delete event with ID: $id");

    // Prepare the delete SQL statement
    $sql = "DELETE FROM admin_eventcalendar WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind the id to the SQL statement
        $stmt->bind_param("i", $id);
        
        // Execute the query and check if successful
        if ($stmt->execute()) {
            echo "Event deleted successfully!";
        } else {
            echo "Error deleting event: " . $stmt->error;
            error_log("SQL error: " . $stmt->error);
        }

        $stmt->close();
    } else {
        echo "Error preparing SQL statement!";
        error_log("Error preparing SQL: " . $conn->error);
    }
} else {
    echo "Invalid event ID!";
    error_log("Invalid or missing event ID");
}

$conn->close();
?>
