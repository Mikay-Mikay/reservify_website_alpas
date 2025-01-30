<?php
session_start();
// Assuming the admin's id is stored in the session after login
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
    <link rel="stylesheet" href="admin_dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                            <span style="margin-left: 1px; margin-top: 4px; text-decoration: none; color: white;">Dashboard</span>
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
        <main class="content">
    <header>
        <h1>Dashboard</h1>
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
                    <p class="dropdown-header"> Admin</p>
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
            <section class="dashboard-cards">
                <div class="card">
                    <img src="images/booking.png.png" alt="Booking Icon" style="float: left; margin-right: 10px;">
                    <p>Total of Customer's Booking:</p>
                    <h2>20</h2>
                </div>
                <div class="card">
                    <img src="images/progress.png.png" alt="Progress Icon" style="float: left; margin-right: 10px;">
                    <p>Total of Progress:</p>
                    <h2>12</h2>
                </div>
                <div class="card">
                    <img src="images/booking_status.png.png" alt="Booking Status Icon" style="float: left; margin-right: 10px;">
                    <p>Total of Booking Status:</p>
                    <h2>9</h2>
                </div>
                <div class="card">
                    <img src="images/visitors.png.png" alt="Visitors Icon" style="float: left; margin-right: 10px;">
                    <p>Total of Visitors:</p>
                    <h2>45</h2>
                </div>
            </section>
        </main>
    </div>

    <!-- Bar Graph Section -->
    <section class="chart-container">
                <canvas id="bookingChart"></canvas>
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

// Bar Graph Data and Configuration
const ctx = document.getElementById('bookingChart').getContext('2d');
    const bookingChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [{
                label: 'Customer Bookings',
                data: [10, 15, 8, 12, 20, 25, 18, 10, 5, 8, 12, 30], // Replace with dynamic PHP data
                backgroundColor: 'rgba(86, 174, 255, 0.7)',
                borderColor: 'rgba(86, 174, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    enabled: true
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Months'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Bookings'
                    }
                }
            }
        }
    });
    </script>
</body>
</html>
