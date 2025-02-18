<?php
require_once "database.php";

$events = [];
$sql = "SELECT 
            tr.first_name, tr.last_name, 
            r.event_type, r.event_place, 
            r.start_time AS event_start, r.end_time AS event_end 
        FROM test_registration tr
        JOIN reservation r ON tr.id = r.user_id
        WHERE r.status = 'approved'"; // FIXED: 'approved' should be inside quotes

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = [
            'title' => 'Event: ' . $row['event_type'] . ' - ' . $row['first_name'] . ' ' . $row['last_name'],
            'start' => $row['event_start'],
            'end' => $row['event_end']
        ];
    }
}

// Close statement and database connection
$stmt->close();
$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($events);
?>
