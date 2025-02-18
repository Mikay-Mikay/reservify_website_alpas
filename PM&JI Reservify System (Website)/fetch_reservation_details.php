<?php
require_once "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ref_no"]) && isset($_POST["user_id"])) {
    $ref_no = $_POST["ref_no"];
    $user_id = $_POST["user_id"];

    $sql = "SELECT 
                r.event_type, r.others, r.event_place, r.photo_size_layout, 
                t.Email,r.contact_number, r.start_time, r.end_time, r.status, 
                t.first_name, t.middle_name, t.last_name
            FROM reservation r
            JOIN test_registration t ON r.user_id = t.id
            JOIN payment p ON p.reservation_id = r.reservation_id
            WHERE p.ref_no = ? AND r.user_id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "si", $ref_no, $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $data = mysqli_fetch_assoc($result);

        if ($data) {
            echo json_encode(["success" => true] + $data);
        } else {
            echo json_encode(["success" => false]);
        }
        mysqli_stmt_close($stmt);
    }
}
?>
