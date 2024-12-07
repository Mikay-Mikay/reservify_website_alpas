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
                    <li>Booking Status <img src="images/click_here.png.png" alt="Click Here"></li>
                    <li>Payments <img src="images/click_here.png.png" alt="Click Here"></li>
                    <li>Booking History <img src="images/click_here.png.png" alt="Click Here"></li>
                    <li>Manage Feedback <img src="images/click_here.png.png" alt="Click Here"></li>
                </ul>
                <hr class="divider">
                <ul>
                    <li>Calendar <img src="images/click_here.png.png" alt="Click Here"></li>
                    <li>Progress <img src="images/click_here.png.png" alt="Click Here"></li>
                </ul>
                <hr class="divider">
                <ul>
                    <li>Manage Inquiries <img src="images/click_here.png.png" alt="Click Here"></li>
                </ul>
            </nav>
        </aside>
        <main class="content">
            <header>
                <h1>Activity Log</h1>
                <div class="header-right">
                    <img src="images/notif_bell.png.png" alt="Notification Bell">
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
    </script>
</body>
</html>
