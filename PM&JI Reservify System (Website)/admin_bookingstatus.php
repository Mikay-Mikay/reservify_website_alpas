<?php
session_start();

require_once "database.php";
// Assuming the admin's ID is stored in the session after login
$admin_ID = isset($_SESSION['admin_ID']) ? $_SESSION['admin_ID'] : 'AD-0001';

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin_login.php');
    exit();
}


// Database connection (update with your own credentials)
require_once "database.php";

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin_login.php');
    exit();
}

// Fetch notifications from the database for the admin
$notifications = [];
$sql = "SELECT * FROM admin_notifications WHERE admin_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);


if ($stmt) {
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }

    $stmt->close();
} else {
    echo "Error: " . $conn->error;
}
// Query to fetch reservation_id and status
$sql = "SELECT reservation_id, status FROM reservation";
$result = $conn->query($sql); // Assuming $conn is defined in database.php



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
    <link rel="stylesheet" href="admin_bookingstatus.css?v=1.1">
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
                            <img src="images/home.png (1).png" alt="Home Icon">
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
                    <?php if (!empty($notifications)): ?>
                        <?php foreach ($notifications as $notification): ?>
                            <div class="notification">
                                <!-- Notification link -->
                                <a href="admin_view_notification.php?id=<?php echo urlencode($notification['id']); ?>" class="notification-link">
                                    <p><strong>Notification ID: </strong><?php echo htmlspecialchars($notification['id']); ?></p>
                                    <p><?php echo htmlspecialchars($notification['message']); ?></p>
                                </a>
                                
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No notifications found.</p>
                    <?php endif; ?>
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
                        <tr>
                        <tr>
                        <tr>
        <th>Reservation Number</th>
        <th>Status</th>
    </tr>
    <?php
    // Get current date in YYYYMMDD format
    $current_date = date("Ymd");

    // Initialize counter for sequential number
    $counter = 1;

    // Fetch each reservation row and display
    while ($row = $result->fetch_assoc()) {
        $reservation_id = $row['reservation_id'];
        $status = $row['status'];

        // Format the reservation number (PMJI-YYYYMMDD-CUST001)
        $formatted_reservation_id = "PMJI-" . $current_date . "-CUST" . str_pad($counter, 3, "0", STR_PAD_LEFT);
        
        // Increment the counter for the next reservation
        $counter++;

        // Assign a color based on the status
        if ($status == 'approved') {
            $status_color = 'green';
        } elseif ($status == 'rejected') {
            $status_color = 'red';
        } else {
            $status_color = 'gray'; // Default color if status is not approved/rejected
        }
        
        echo "<tr>";
        echo "<td>" . htmlspecialchars($formatted_reservation_id) . "</td>";
        echo "<td style='color: $status_color;'>" . htmlspecialchars($status) . "</td>";
        echo "</tr>";
    }
    ?>
                        </thead>
                    </table>
                </main>
            </div>
        </div>
    </div>

    <script>
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
}

// Close dropdown if clicked outside
window.onclick = function(event) {
    if (!event.target.matches('#notif-bell') && !event.target.closest('.notification-container')) {
        const notifDropdown = document.getElementById('notification-dropdown');
        if (notifDropdown && notifDropdown.classList.contains('show')) {
            notifDropdown.classList.remove('show');
        }
    }
};


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
