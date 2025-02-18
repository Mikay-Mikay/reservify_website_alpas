<?php
include_once 'database.php';

if (isset($_GET['reservation_id'])) {
    $reservation_id = $_GET['reservation_id'];

    // Ensure that reservation_id is an integer (sanitization)
    if (!filter_var($reservation_id, FILTER_VALIDATE_INT)) {
        echo json_encode(['title' => 'Error', 'content' => 'Invalid Reservation ID.']);
        exit();
    }

    // Fetch booking details based on reservation_id
    $sql = "SELECT * FROM booking_summary WHERE reservation_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reservation_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Prepare booking details
        $bookingDetails = [
            'title' => 'Reservation Details - ' . $reservation_id,
            'content' => [
                'email' => $row['email'],
                'event_type' => $row['event_type'],
                'event_place' => $row['event_place'],
                'number_of_participants' => $row['number_of_participants'],
                'date_and_schedule' => $row['date_and_schedule'], 
                'payment_method' => $row['payment_method'],
                'status' => $row['status']
            ]
        ];

        // Send JSON response
        echo json_encode($bookingDetails);
    } else {
        echo json_encode(['title' => 'No Details Found', 'content' => 'No booking details available for this reservation.']);
    }
} else {
    echo json_encode(['title' => 'Error', 'content' => 'Reservation ID not provided.']);
}

$conn->close();
?>
