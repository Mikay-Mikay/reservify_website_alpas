<?php
session_start();

// Assuming the admin's ID is stored in the session after login
$admin_ID = isset($_SESSION['admin_ID']) ? $_SESSION['admin_ID'] : 'AD-0001';

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
    <link rel="stylesheet" href="admin_calendar.css">
    <link rel="stylesheet" href="admin_dashboard.css?v=1.1">
    <link rel="stylesheet" href="admin_bookinghistory.css">
    <link rel="stylesheet" href="admin_profile.css?v=1.1">
    <link rel="stylesheet" href="admin_bookingstatus.css?v=1.1">
    <link rel="stylesheet" href="admin_payments.css?v=1.1">
    <link rel="stylesheet" href="admin_managefeedback.css">
    <link rel="stylesheet" href="admin_manageinq.css?v=1.1">
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
                        <a href="admin_manageinq.php"style="text-decoration: none; color: white; display: flex; justify-content: space-between; align-items: center;">
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
                <h1>Manage Inquiries</h1>
                <div class="header-right">
                    <!-- Notification Bell -->
                    <div class="notification-container">
                        <img src="images/notif_bell.png.png" alt="Notification Bell" id="notif-bell" onclick="toggleNotification()">
                        <div id="notification-dropdown" class="notification-dropdown">
                            <h2>Notifications</h2>
                            <!-- Notifications -->
                            <div class="notification">
                                <p><strong>Ticket CS-20241129-0001</strong>: John A. Doe: "Service Inquiry" — Can I reschedule my booking for December 8, 2024? Contact details logged.</p>
                                <span>11:30 AM, Nov 29, 2024</span>
                            </div>
                            <div class="notification">
                                <p><strong>PMJI-20241130-CUST001</strong>: John A. Doe successfully paid PHP 3,500 for Booking ID #56789 via GCash.</p>
                                <span>3:30 PM, Nov 29, 2024</span>
                            </div>
                            <div class="notification">
                                <p><strong>PMJI-20241130-CUST002</strong>: Anne C. Cruz attempted payment for booking #56789 but it failed. Please follow up.</p>
                                <span>2:45 PM, Nov 29, 2024</span>
                            </div>
                            <div class="notification">  
                                <p><strong>PMJI-20241130-CUST003</strong>: Jane D. Smith requested a booking for December 20, 2024. Please review and approve or decline.</p>
                                <span>4:15 PM, Nov 29, 2024</span>
                            </div>
                        </div>
                    </div>
                    <!-- Profile Icon -->
                    <div class="profile-container">
                        <img class="profile-icon" src="images/user_logo.png" alt="Profile Icon" onclick="toggleDropdown()">
                        <div id="profile-dropdown" class="dropdown">
                            <p class="dropdown-header">Admin</p>
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

            <!-- Inquiry List -->
            <section class="inquiry-section">
                <div class="inquiry-search">
                    <input type="text" placeholder="Search inquiries..." class="search-input">
                    <button class="search-btn">Search</button>
                </div>
                <table class="inquiry-table">
                    <thead>
                        <tr>
                            <th>Inquiry ID</th>
                            <th>Customer Name</th>
                            <th>Email</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>CS-20241129-0001</td>
                            <td>John A. Doe</td>
                            <td>john.doe@example.com</td>
                            <td>Nov 29, 2024</td>
                            <td><span class="status open">Open</span></td>
                            <td>
                                <button class="view-btn">View</button>
                                <button class="resolve-btn">Resolve</button>
                            </td>
                        </tr>
                        <tr>
                            <td>CS-20241129-0002</td>
                            <td>Anne C. Cruz</td>
                            <td>anne.cruz@example.com</td>
                            <td>Nov 29, 2024</td>
                            <td><span class="status resolved">Resolved</span></td>
                            <td>
                                <button class="view-btn">View</button>
                                <button class="resolve-btn">Resolve</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </section>
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

// Close dropdowns when clicking outside
window.onclick = function(event) {
    // Close profile dropdown if clicked outside
    if (!event.target.matches('.profile-icon') && !event.target.closest('.profile-container')) {
        const dropdown = document.getElementById('profile-dropdown');
        if (dropdown && dropdown.classList.contains('show')) {
            dropdown.classList.remove('show');
        }
    }

    // Close notification dropdown if clicked outside
    if (!event.target.matches('#notif-bell') && !event.target.closest('.notification-container')) {
        const notifDropdown = document.getElementById('notification-dropdown');
        if (notifDropdown && notifDropdown.classList.contains('show')) {
            notifDropdown.classList.remove('show');
        }
    }
};
    </script>
</body>
</html>
