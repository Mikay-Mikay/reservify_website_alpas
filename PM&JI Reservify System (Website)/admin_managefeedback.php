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
    <title>Admin PM&JI Reservify</title>
    <link rel="stylesheet" href="admin_dashboard.css?v=1.1">
    <link rel="stylesheet" href="admin_bookinghistory.css">
    <link rel="stylesheet" href="admin_profile.css?v=1.1">
    <link rel="stylesheet" href="admin_bookingstatus.css?v=1.1">
    <link rel="stylesheet" href="admin_payments.css?v=1.1">
    <link rel="stylesheet" href="admin_managefeedback.css?">
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
        <h1>Manage Feedback</h1>
        <div class="header-right">
        <div class="filter-container">
                        <button class="filter-button" onclick="toggleFilterMenu()">
                            <img src="images/filter.png.png" alt="Filter">
                        </button>
                        <div class="filter-menu" id="filterMenu">
                            <div class="filter-option" onclick="filterByStar(1)">1 Star ⭐</div>
                            <div class="filter-option" onclick="filterByStar(2)">2 Star ⭐⭐</div>
                            <div class="filter-option" onclick="filterByStar(3)">3 Star ⭐⭐⭐</div>
                            <div class="filter-option" onclick="filterByStar(4)">4 Star ⭐⭐⭐⭐</div>
                            <div class="filter-option" onclick="filterByStar(5)">5 Star ⭐⭐⭐⭐⭐</div>
                        </div>
                    </div>
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
            </div>
        </div>
    </header>
    <div class="feedback-container">
        <div class="feedback-row">
            <!-- Feedback 1 -->
            <div class="feedback-card">
                <div class="profile">
                    <img src="images/profile_user_icon.png" alt="Profile Icon">
                    <span>Crisostomo Ibarra</span>
                    <div class="date">12-01-24</div>
                </div>
                <p>The response time of the owner was fast and super friendly. The website is very easy to use ❤️.</p>
                <div class="stars">
                    <img src="images/star_filled.png.png" alt="Star">
                    <img src="images/star_filled.png.png" alt="Star">
                    <img src="images/star_filled.png.png" alt="Star">
                    <img src="images/star_filled.png.png" alt="Star">
                    <img src="images/star_empty.png.png" alt="Star">
                </div>
            </div>
            <!-- Feedback 2 -->
            <div class="feedback-card">
                <div class="profile">
                    <img src="images/profile_user_icon.png" alt="Profile Icon">
                    <span>Maria Clara</span>
                    <div class="date">01-09-24</div>
                </div>
                <p>The quality of shots are amazing! It's beautiful and they have a lot of props to use. It's so fun!</p>
                <div class="stars">
                    <img src="images/star_filled.png.png" alt="Star">
                    <img src="images/star_filled.png.png" alt="Star">
                    <img src="images/star_filled.png.png" alt="Star">
                    <img src="images/star_empty.png.png" alt="Star">
                    <img src="images/star_empty.png.png" alt="Star">
                </div>
            </div>
        </div>

        <div class="feedback-row">
            <!-- Feedback 3 -->
            <div class="feedback-card">
                <div class="profile">
                    <img src="images/profile_user_icon.png" alt="Profile Icon">
                    <span>Dexter Cabubas</span>
                    <div class="date">03-28-24</div>
                </div>
                <p>Their website is very easy to use, I reserved with no problem at all.</p>
                <div class="stars">
                    <img src="images/star_filled.png.png" alt="Star">
                    <img src="images/star_filled.png.png" alt="Star">
                    <img src="images/star_filled.png.png" alt="Star">
                    <img src="images/star_filled.png.png" alt="Star">
                    <img src="images/star_filled.png.png" alt="Star">
                </div>
            </div>
            <!-- Feedback 4 -->
            <div class="feedback-card">
                <div class="profile">
                    <img src="images/profile_user_icon.png" alt="Profile Icon">
                    <span>Mikaela Somera</span>
                    <div class="date">12-01-24</div>
                </div>
                <p>Their website is very easy to use, but at some point there were minor issues that made me hard to make a booking.</p>
                <div class="stars">
                    <img src="images/star_filled.png.png" alt="Star">
                    <img src="images/star_filled.png.png" alt="Star">
                    <img src="images/star_empty.png.png" alt="Star">
                    <img src="images/star_empty.png.png" alt="Star">
                    <img src="images/star_empty.png.png" alt="Star">
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
 // Toggle the filter menu visibility
 function toggleFilterMenu() {
            const filterMenu = document.getElementById("filterMenu");
            filterMenu.style.display = filterMenu.style.display === "block" ? "none" : "block";
        }

        // Filter feedback cards based on selected star rating
        function filterByStar(starCount) {
            const feedbackCards = document.querySelectorAll(".feedback-card");
            feedbackCards.forEach(card => {
                const stars = parseInt(card.getAttribute("data-stars"));
                card.style.display = stars === starCount ? "block" : "none";
            });
        }
</script>
</body>
</html>
