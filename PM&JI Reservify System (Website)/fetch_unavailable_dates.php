<?php
include 'database.php'; // Include your database connection

$sql = "SELECT event_start FROM admin_eventcalendar";
$result = $conn->query($sql);

$unavailable_dates = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $unavailable_dates[] = $row['event_start']; // Collect dates from database
    }
}

$conn->close();
header('Content-Type: application/json');
echo json_encode($unavailable_dates); // Output dates as JSON
?>
