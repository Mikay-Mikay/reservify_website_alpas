<?php
session_start();
include_once 'database.php';
// Assuming the admin's name is stored in the session after login
$admin_name = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'Admin';

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin_login.php');
    exit();
}
// Get all reservations and their associated booking details
$sql = "
    SELECT r.reservation_id, r.status, 
           bs.first_name, bs.middle_name, bs.last_name, bs.email, 
           bs.event_type, bs.event_place, bs.number_of_participants, 
           bs.date_and_schedule, bs.payment_method
    FROM reservation r
    INNER JOIN booking_summary bs ON r.reservation_id = bs.reservation_id
    ORDER BY r.reservation_id ASC
";
$result = $conn->query($sql);

// Handle database error
if (!$result) {
    echo "Error: " . $conn->error;
    exit();
}

$bookingData = []; // Array to store the booking details
while ($row = $result->fetch_assoc()) {
    // Format reservation number (Example: PMJI-YYYYMMDD-CUST001)
    $reservation_number = "PMJI-" . date("Ymd") . "-CUST" . str_pad($row['reservation_id'], 3, "0", STR_PAD_LEFT);
    $bookingData[$reservation_number] = [
        'email' => $row['email'],
        'event_type' => $row['event_type'],
        'event_place' => $row['event_place'],
        'number_of_participants' => $row['number_of_participants'],
        'date_and_schedule' => $row['date_and_schedule'],
        'payment_method' => $row['payment_method'],
        'status' => $row['status']
    ];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin PM&JI Reservify</title>
    <link rel="stylesheet" href="admin_dashboard.css?v=1.1">
    <link rel="stylesheet" href="admin_bookinghistory.css">
    <link rel="stylesheet" href="admin_profile.css?v=1.1">
    <link rel="stylesheet" href="admin_bookingstatus.css?v=1.1">
    <link rel="stylesheet" href="admin_payments.css?v=1.1">
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
                            <img src="images/home.png.png" alt="Home Icon">
                            <span style="margin-left: 1px; margin-top: 4px;">Dashboard</span>
                        </a>
                    </li>
                </ul>
                <hr class="divider">
                <ul>
                    <li>
                        <a href="admin_bookingstatus.php" style="text-decoration: none; color: white; display: flex; justify-content: space-between; align-items: center;">
                        <span>Bookings</span>
                        <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                    <li>
                        <a href="admin_payments.php"style="text-decoration: none; color: white; display: flex; justify-content: space-between; align-items: center;">
                        <span>Payments</span>
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                    <li>
                        <a href="admin_bookinghistory.php"style="text-decoration: none; color: white; display: flex; justify-content: space-between; align-items: center;">
                        <span>Booking History</span>
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                    <li>
                        <a href="admin_managefeedback.php"style="text-decoration: none; color: white; display: flex; justify-content: space-between; align-items: center;">
                        <span>Manage Feedback</span>
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                    <li>
                        <a href="admin_calendar.php"style="text-decoration: none; color: white; display: flex; justify-content: space-between; align-items: center;">
                        <span>Calendar</span>
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                </ul>
                <hr class="divider">
                <ul>
                    <li>
                        <a href="admin_manageinquiries.php"style="text-decoration: none; color: white; display: flex; justify-content: space-between; align-items: center;">
                        <span>Manage Inquiries</span>
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

    <!-- Main Content -->
<main class="content">
<header>
    <!-- Header Section -->
    <div class="header">
        <!-- Booking History Title -->
        <h1>Booking History</h1>
            <div class="search-bar-container">
                <input type="text" id="searchBar" placeholder="Search bookings..." onkeyup="searchBookings()">
            </div>
            <!-- Notification Bell and Profile -->
        <div class="header-right">
            <!-- Notification Bell -->
        <div class="notification-container">
                <img src="images/notif_bell.png.png" alt="Notification Bell" id="notif-bell" onclick="toggleNotification()">
                <div id="notification-dropdown" class="notification-dropdown">
                    <h2>Notifications</h2>
                    <!-- Static Notifications (pansamantala lang, gawan mo php to hehe) -->
                    <div class="notification">
                        <p><strong>PMJI-20241130-CUST001</strong> John A. Doe successfully paid PHP 3,500 for Booking ID #56789 via GCash.</p>
                        <span>3:30 PM, Nov 29, 2024</span>
                    </div>
                    <div class="notification">
                        <p><strong>Ticket-CS-20241129-0003</strong> John A. Doe: "Service Inquiry" — Can I reschedule my booking for December 8, 2024? Contact details logged.</p>
                        <span>11:30 AM, Nov 29, 2024</span>
                    </div>
                    <div class="notification">
                        <p><strong>PMJI-20241130-CUST002</strong> Anne C. Cruz attempted payment for booking #56789 but it failed. Please follow up.</p>
                        <span>2:45 PM, Nov 29, 2024</span>
                    </div>
                    <div class="notification">
                        <p><strong>PMJI-20241130-CUST003</strong> Jane D. Smith requested a booking for December 20, 2024. Please review and approve or decline.</p>
                        <span>4:15 PM, Nov 29, 2024</span>
                    </div>
                </div>
            </div>
                        <!-- Profile Icon -->
                        <div class="profile-container">
                            <img class="profile-icon" src="images/user_logo.png" alt="Profile Icon" onclick="toggleDropdown()">
                            <div id="profile-dropdown" class="dropdown">
                                <p class="dropdown-header"><?php echo htmlspecialchars($admin_name); ?></p>
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
        <!-- Booking List -->
<!-- Displaying Booking List -->
<div class="booking-list">
        <?php foreach ($bookingData as $reservation_number => $details) { ?>
            <div class="booking-item" onclick="showDetails('<?php echo $reservation_number; ?>')">
                <?php echo $reservation_number; ?>
            </div>
        <?php } ?>
    </div>
        </main>
    </div>

   

    <script>
    // Booking details data
    // Example data fetched from PHP (you could also fetch this via AJAX if needed)
// Assuming you have fetched the `$row` data from your database
const bookingData = <?php echo json_encode($bookingData); ?>;

// Show booking details in an alert box
function showDetails(reservationNumber) {
    const bookingDetails = bookingData[reservationNumber];
    if (bookingDetails) {
        // Display details in an alert box
        alert(
            "Reservation ID: " + reservationNumber + "\n" +
            "Email: " + bookingDetails.email + "\n" +
            "Event Type: " + bookingDetails.event_type + "\n" +
            "Event Place: " + bookingDetails.event_place + "\n" +
            "Participants: " + bookingDetails.number_of_participants + "\n" +
            "Date & Schedule: " + bookingDetails.date_and_schedule + "\n" +
            "Payment Method: " + bookingDetails.payment_method + "\n" +
            "Status: " + bookingDetails.status
        );
    } else {
        alert("No details found for this reservation.");
    }
}

    function toggleDropdown() {
            const dropdown = document.getElementById('profile-dropdown');
            dropdown.classList.toggle('show');
        }

        window.onclick = function(event) {
            if (!event.target.matches('.profile-icon')) {
                const dropdown = document.getElementById('profile-dropdown');
                if (dropdown && dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            }
        };

        // Toggle Notification Dropdown
        function toggleNotification() {
            const notifDropdown = document.getElementById('notification-dropdown');
            notifDropdown.classList.toggle('show');

            // Close notification dropdown if clicked outside
            if (!event.target.matches('#notif-bell') && !event.target.closest('.notification-container')) {
                const notifDropdown = document.getElementById('notification-dropdown');
                if (notifDropdown && notifDropdown.classList.contains('show')) {
                    notifDropdown.classList.remove('show');
                }
            }
        }

        function searchTable() {
            const input = document.getElementById("searchBar").value.toUpperCase();
            const table = document.querySelector("table tbody");
            const rows = table.getElementsByTagName("tr");

            for (let i = 0; i < rows.length; i++) {
                const cell = rows[i].getElementsByTagName("td")[0];
                if (cell) {
                    const textValue = cell.textContent || cell.innerText;
                    rows[i].style.display = textValue.toUpperCase().indexOf(input) > -1 ? "" : "none";
                }
            }
        }
</script>
</body>
</html>
