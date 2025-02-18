<?php
require_once "database.php";

$events = [];

// Fetch only reservations that are approved and have approved payments
$sql = "SELECT r.id, r.fullname, r.start_time, r.end_time, r.event_type, r.event_place, 
               r.photo_size_layout, r.contact_number
        FROM reservation r
        WHERE r.status = 'approved' 
        AND EXISTS (SELECT 1 FROM payment p WHERE p.reservation_id = r.id AND p.status = 'approved')";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = [
            'id'    => $row['id'],
            'title' => $row['fullname'] . " - " . $row['event_type'],
            'start' => date("Y-m-d\TH:i:s", strtotime($row['start_time'])), // FullCalendar format
            'end'   => date("Y-m-d\TH:i:s", strtotime($row['end_time'])),
            'color' => '#28a745', // Green for approved reservations
            'event_type' => $row['event_type'],
            'event_place' => $row['event_place'],
            'photo_size_layout' => $row['photo_size_layout'],
            'contact_number' => $row['contact_number']
        ];
    }
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($events);
?>
