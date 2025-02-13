<?php
session_start();
require_once "database.php";

$admin_ID = isset($_SESSION['admin_ID']) ? $_SESSION['admin_ID'] : 'AD-0001';

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin_login.php');
    exit();
}

// Fetch notifications for the admin
$notifications = [];
$sql = "SELECT * FROM admin_notifications WHERE admin_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $admin_ID);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }
    $stmt->close();
} else {
    echo "Error: " . $conn->error;
}

// Query to fetch reservation and user details
$sql = "SELECT r.reservation_id, r.user_id, r.event_type, r.others, r.event_place, 
               r.photo_size_layout, r.contact_number, r.start_time, r.end_time, 
               r.image, r.message, r.status, 
               u.First_Name, u.Middle_Name, u.Last_Name, u.Email
        FROM reservation r
        JOIN test_registration u ON r.user_id = u.id";

$result = $conn->query($sql);

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin PM&JI Reservify</title>
    <link rel="stylesheet" href="admin_dashboard.css?v=1.1">
    <link rel="stylesheet" href="admin_profile.css?v=1.1">
    <link rel="stylesheet" href="admin_activitylog.css?v=1.1">
    <link rel="stylesheet" href="admin_bookings.css?v=1.1">
</head>
<body>
    <div class="admin-dashboard">
    <aside class="sidebar">
            <div class="logo">
                <img src="images/reservify_logo.png" alt="Reservify Logo">
                <p>Hello, Admin!</p>
            </div>
            <nav>
                <ul>
                    <li class="dashboard-item">
                        <a href="admin_dashboard.php" style="display: flex; align-items: center; gap: 7px;">
                            <img src="images/home.png" alt="Home Icon">
                            <span style="margin-left: 1px; margin-top: 4px; color: black;">Dashboard</span>
                        </a>
                    </li>
                </ul>
                <hr class="divider">
                <ul>
                    <li>
                        <a href="admin_bookings.php" style="text-decoration: none; color: black; display: flex; justify-content: space-between; align-items: center;">
                        <span>Bookings</span>
                        <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                    <li>
                        <a href="admin_payments.php"style="text-decoration: none; color: black; display: flex; justify-content: space-between; align-items: center;">
                        <span>Payments</span>
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                    <li>
                        <a href="admin_bookinghistory.php"style="text-decoration: none; color: black; display: flex; justify-content: space-between; align-items: center;">
                        <span>Booking History</span>
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                    <li>
                        <a href="admin_managefeedback.php"style="text-decoration: none; color: black; display: flex; justify-content: space-between; align-items: center;">
                        <span>Manage Feedback</span>
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                    <li>
                        <a href="admin_calendar.php"style="text-decoration: none; color: black; display: flex; justify-content: space-between; align-items: center;">
                        <span>Calendar</span>
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                </ul>
                <hr class="divider">
                <ul>
                    <li>
                        <a href="admin_manageinq.php"style="text-decoration: none; color: black; display: flex; justify-content: space-between; align-items: center;">
                        <span>Manage Inquiries</span>
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <div class="content">
            <div class="header-right">
                <main class="booking-status">
                    <header>
                        <h1>Booking Status</h1>
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <input type="text" id="searchBar" placeholder="Search reservation number.." onkeyup="searchTable()">

                                                
                      <!-- Notification Bell -->
           <div class="notification-container">
                <img src="images/notif_bell.png.png" alt="Notification Bell" id="notif-bell" onclick="toggleNotification()">
                <div id="notification-dropdown" class="notification-dropdown">
                    <h2>Notifications</h2>
                </div>
            </div>

                            
                            <!-- Profile Icon -->
                            <div class="profile-container">
                                <img class="profile-icon" src="images/user_logo.png" alt="Profile Icon" onclick="toggleDropdown()">
                                <div id="profile-dropdown" class="dropdown">
                                    <p class="dropdown-header">Jiar Cabubas (Admin)</p>
                                    <hr>
                                    <ul>
                                        <li><a href="admin_profile.php">Profile</a></li>
                                        <li><a href="admin_activitylog.php">Activity Log</a></li>
                                    </ul>
                                    <hr>
                                    <a class="logout" href="?logout">Logout</a>
                                </div>
                            </div>
                        </div>
                    </header>
                    <table>
    <thead>
        <tr>
            <th>Reservation Number</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
<?php
$current_date = date("Ymd");
$counter = 1;

while ($row = $result->fetch_assoc()) {
    $formatted_reservation_id = "PMJI-" . $current_date . "-CUST" . str_pad($counter, 3, "0", STR_PAD_LEFT);
    $counter++;

    $status_color = ($row['status'] == 'approved') ? 'green' : (($row['status'] == 'rejected') ? 'red' : 'gray');

    echo "<tr class='reservation-row' 
    data-reservation-id='$formatted_reservation_id' 
    data-name='" . htmlspecialchars($row['First_Name'] . ' ' . $row['Middle_Name'] . ' ' . $row['Last_Name']) . "' 
    data-email='" . htmlspecialchars($row['Email']) . "'
    data-event-type='" . htmlspecialchars($row['event_type']) . "' 
    data-others='" . htmlspecialchars($row['others']) . "' 
    data-event-place='" . htmlspecialchars($row['event_place']) . "' 
    data-photo-size-layout='" . htmlspecialchars($row['photo_size_layout']) . "' 
    data-contact-number='" . htmlspecialchars($row['contact_number']) . "' 
    data-start-time='" . htmlspecialchars($row['start_time']) . "' 
    data-end-time='" . htmlspecialchars($row['end_time']) . "' 
    data-image='" . htmlspecialchars($row['image']) . "' 
    data-message='" . htmlspecialchars($row['message']) . "' 
    data-status='" . htmlspecialchars($row['status']) . "' 
    style='cursor: pointer;'>
<td>" . htmlspecialchars($formatted_reservation_id) . "</td>
<td style='color: $status_color;'>" . htmlspecialchars($row['status']) . "</td>
</tr>";

}
?>
</tbody>
</tbody>

</table>

<!-- Modal -->
<div id="reservation-modal" class="modal">
    <div class="modal-content">
    <span class="close-btn">&times;</span> <!-- Siguraduhin nasa tamang lugar -->
    <h2 style="text-align: center;">Reservation Details</h2>
        <p id="modal-name"></p>
        <p id="modal-email"></p>
        <p id="modal-event-type"></p>
        <p id="modal-others"></p>
        <p id="modal-event-place"></p>
        <p id="modal-photo-size-layout"></p>
        <p id="modal-contact-number"></p>
        <p id="modal-start-time"></p>
        <p id="modal-end-time"></p>
        <img id="modal-image" src="" alt="Event Image" style="max-width: 50%;">
        <p id="modal-message"></p>
        <p id="modal-status"></p>
    </div>
</div>

<!-- Modal Styling and JavaScript -->
<style>
   /* Modal Styles */
.modal {
    display: none; /* Hidden by default */
    position: fixed;
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    color: black; /* Set the text color inside the modal to black */
}


.modal-content h5,
.modal-content p {
    color: black; /* Ensure text color inside paragraphs and headings is black */
}

.close-btn {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close-btn:hover,
.close-btn:focus {
    color: red;
    text-decoration: none;
    cursor: pointer;
}

</style>
                </main>
            </div>
        </div>
    </div>

    
<script>
// Ensure JavaScript runs only after DOM is fully loaded
document.addEventListener("DOMContentLoaded", function () {
    var modal = document.getElementById("reservation-modal");
    var closeBtn = document.querySelector(".close-btn");

    document.querySelectorAll(".reservation-row").forEach(function (row) {
        row.addEventListener("click", function () {
            document.getElementById("modal-name").textContent = "Name: " + (this.getAttribute("data-name") || "N/A");
            document.getElementById("modal-email").textContent = "Email: " + (this.getAttribute("data-email") || "N/A");
            document.getElementById("modal-event-type").textContent = "Event Type: " + (this.getAttribute("data-event-type") || "N/A");
            document.getElementById("modal-others").textContent = "Others: " + (this.getAttribute("data-others") || "N/A");
            document.getElementById("modal-event-place").textContent = "Event Place: " + (this.getAttribute("data-event-place") || "N/A");
            document.getElementById("modal-photo-size-layout").textContent = "Photo Size/Layout: " + (this.getAttribute("data-photo-size-layout") || "N/A");
            document.getElementById("modal-contact-number").textContent = "Contact Number: " + (this.getAttribute("data-contact-number") || "N/A");
            document.getElementById("modal-start-time").textContent = "Start Time: " + (this.getAttribute("data-start-time") || "N/A");
            document.getElementById("modal-end-time").textContent = "End Time: " + (this.getAttribute("data-end-time") || "N/A");
            document.getElementById("modal-message").textContent = "Message: " + (this.getAttribute("data-message") || "N/A");
            document.getElementById("modal-status").textContent = "Status: " + (this.getAttribute("data-status") || "N/A");

            var imagePath = this.getAttribute("data-image");
            document.getElementById("modal-image").src = imagePath ? "images/" + imagePath : "images/default.jpg";

            modal.style.display = "block";
        });
    });

    if (closeBtn) {
        closeBtn.onclick = function () {
            modal.style.display = "none";
        };
    }

    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
});


</script>
</body>
</html>
