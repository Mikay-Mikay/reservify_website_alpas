<?php
session_start();

// Define default values or fetch them from the session
$admin_name = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'Admin';
$admin_id = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : 'AD-0001';

// Sample activities (you can replace these with data from a database)
$activities = [
    [
        'time' => '08:00 AM',
        'activity' => 'Logged in',
        'details' => 'Customer Support accessed the Manage Inquiries to address inquiries.'
    ],
    [
        'time' => '08:15 AM',
        'activity' => 'Responded to Bookings',
        'details' => 'Owner checked pending reservations.'
    ],
    [
        'time' => '09:45 AM',
        'activity' => 'Approved Booking',
        'details' => 'Owner approved Reservation #34567 for a wedding.'
    ]
];

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin_login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin PM&JI Reservify</title>
    <link rel="stylesheet" href="admin_profile.css?v=1.0">
    <link rel="stylesheet" href="admin_dashboard.css">
    <link rel="stylesheet" href="admin_activitylog.css">
    <link rel="stylesheet" href="admin_bookingstatus.css?v=1.1">
</head>
<body>
<div class="admin-dashboard">
        <aside class="sidebar">
            <div class="logo">
                <img src="images/reservify_logo.png" alt="Reservify Logo">
                <p>Hello, <?php echo htmlspecialchars($admin_name); ?>!</p>
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
                        <span>Booking Status</span>
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
                        Booking History
                        <a href="admin_bookinghistory.php">
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                    <li>
                        Manage Feedback
                        <a href="admin_managefeedback.php">
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                </ul>
                <hr class="divider">
                <ul>
                    <li>
                        Calendar
                        <a href="admin_calendar.php">
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                    <li>
                        Progress
                        <a href="admin_managefeedback.php">
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                </ul>
                <hr class="divider">
                <ul>
                    <li>
                        Manage Inquiries
                        <a href="admin_managefeedback.php">
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>
        <main class="content">
            <header>
                <h1>Activity Log</h1>
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
            <!-- Activity Log Section -->
            <section class="activity-log">
                <h2>December 08, 2024</h2>
                <a href="admin_dashboard.php" class="back-button">
                    <img src="images/back button.png" alt="Back Button">
                </a>
                <div class="activity-list">
                    <?php foreach ($activities as $activity): ?>
                        <div class="activity-card">
                            <p><strong>Time:</strong> <?php echo $activity['time']; ?></p>
                            <p><strong>Activity:</strong> <?php echo $activity['activity']; ?></p>
                            <p><strong>Details:</strong> <?php echo $activity['details']; ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </main>
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
        }
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
    </script>
</body>
</html>
