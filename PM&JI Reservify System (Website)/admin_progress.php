<?php
session_start();
// Assuming the admin's name is stored in the session after login
$admin_name = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'Admin';

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
    <title>Admin Calendar</title>
    <link rel="stylesheet" href="admin_calendar.css">
    <link rel="stylesheet" href="admin_dashboard.css">
    <link rel="stylesheet" href="admin_bookinghistory.css">
    <link rel="stylesheet" href="admin_profile.css">
    <link rel="stylesheet" href="admin_bookingstatus.css">
    <link rel="stylesheet" href="admin_payments.css">
    <link rel="stylesheet" href="admin_managefeedback.css">
    <link rel="stylesheet" href="admin_progress.css?v=1.1">
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
                            <img src="images/home.png (1).png" alt="Home Icon">
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
                        <a href="admin_payments.php" style="text-decoration: none; color: white; display: flex; justify-content: space-between; align-items: center;">
                            <span>Payments</span>
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                    <li>
                        <a href="admin_bookinghistory.php" style="text-decoration: none; color: white; display: flex; justify-content: space-between; align-items: center;">
                            <span>Booking History</span>
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                    <li>
                        <a href="admin_managefeedback.php" style="text-decoration: none; color: white; display: flex; justify-content: space-between; align-items: center;">
                            <span>Manage Feedback</span>
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                </ul>
                <hr class="divider">
                <ul>
                    <li>
                        <a href="admin_calendar.php" style="text-decoration: none; color: white; display: flex; justify-content: space-between; align-items: center;">
                            <span>Calendar</span>
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                    <li>
                        <a href="admin_progress.php" style="text-decoration: none; color: white; display: flex; justify-content: space-between; align-items: center;">
                            <span>Progress</span>
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                </ul>
                <hr class="divider">
                <ul>
                    <li>
                        <a href="admin_manageinquiries.php" style="text-decoration: none; color: white; display: flex; justify-content: space-between; align-items: center;">
                            <span>Manage Inquiries</span>
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <main class="content">
            <header class="header">
                <h1>Progress</h1>
                <div class="header-right">
            <div class="notification-container">
                <img src="images/notif_bell.png.png" alt="Notification Bell" id="notif-bell" onclick="toggleNotification()">
                <div id="notification-dropdown" class="notification-dropdown">
                                <h2>Notifications</h2>
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
            </header>
        <div class="progress-list">
            <div class="progress-item">
                PMJI-20241130-CUST001
                <img src="images/click_here.png.png" alt="Click Here">
            </div>
            <div class="progress-item">
                PMJI-20241201-CUST002
                <img src="images/click_here.png.png" alt="Click Here">
            </div>
            <div class="progress-item">
                PMJI-20241203-CUST003
                <img src="images/click_here.png.png" alt="Click Here">
            </div>
            <div class="progress-item">
                PMJI-20241205-CUST004
                <img src="images/click_here.png.png" alt="Click Here">
            </div>
            <div class="progress-item">
                PMJI-20241209-CUST005
                <img src="images/click_here.png.png" alt="Click Here">
            </div>
            <div class="progress-item">
                PMJI-20241215-CUST006
                <img src="images/click_here.png.png" alt="Click Here">
            </div>
            <div class="progress-item">
                PMJI-20241219-CUST007
                <img src="images/click_here.png.png" alt="Click Here">
            </div>
            <div class="progress-item">
                PMJI-20241222-CUST008
                <img src="images/click_here.png.png" alt="Click Here">
            </div>
            <div class="progress-item">
                PMJI-20241226-CUST009
                <img src="images/click_here.png.png" alt="Click Here">
            </div>
            <div class="progress-item">
                PMJI-20241228-CUST010
                <img src="images/click_here.png.png" alt="Click Here">
                </div>
            </div>
        </div>
    </div>
</main>
</div>
<script>
    // Toggle Profile Dropdown
function toggleDropdown() {
    const dropdown = document.getElementById('profile-dropdown');
    dropdown.classList.toggle('show');
}

// Toggle Notification Dropdown
function toggleNotification() {
    const notifDropdown = document.getElementById('notification-dropdown');
    notifDropdown.classList.toggle('show');
}
</script>
</body>
</html>