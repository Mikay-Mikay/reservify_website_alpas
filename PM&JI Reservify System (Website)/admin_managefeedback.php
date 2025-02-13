<?php
session_start();
require_once "database.php";

// Get admin ID from session, default to 'AD-0001' if not set
$admin_ID = $_SESSION['admin_ID'] ?? 'AD-0001';

// Handle logout if the logout parameter is present in the URL
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin_login.php');
    exit();
}

// SQL query to fetch feedback details along with user information
$sql = "
    SELECT f.message, f.rating, tr.First_name, tr.Middle_name, tr.Last_name, tr.Email
    FROM feedback f
    JOIN test_registration tr ON f.user_id = tr.id
";

// Execute the query
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Fetch all results as an associative array
$feedbacks = mysqli_fetch_all($result, MYSQLI_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin PM&JI Reservify</title>
    <link rel="stylesheet" href="admin_dashboard.css?v=1.2">
    <link rel="stylesheet" href="admin_bookinghistory.css">
    <link rel="stylesheet" href="admin_profile.css?v=1.1">
    <link rel="stylesheet" href="admin_bookings.css?v=1.2">
    <link rel="stylesheet" href="admin_payments.css?v=1.1">
    <link rel="stylesheet" href="admin_managefeedback.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .stars {
            display: flex;
            font-size: 20px;
        }

        .stars i {
            color: lightgray; /* Default color for empty stars */
        }

        .stars .filled {
            color: gold; /* Color for filled stars */
        }
    </style>
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
                        <a href="admin_payments.php" style="text-decoration: none; color: black; display: flex; justify-content: space-between; align-items: center;">
                            <span>Payments</span>
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                    <li>
                        <a href="admin_bookinghistory.php" style="text-decoration: none; color: black; display: flex; justify-content: space-between; align-items: center;">
                            <span>Booking History</span>
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                    <li>
                        <a href="admin_managefeedback.php" style="text-decoration: none; color: black; display: flex; justify-content: space-between; align-items: center;">
                            <span>Manage Feedback</span>
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                    <li>
                        <a href="admin_calendar.php" style="text-decoration: none; color: black; display: flex; justify-content: space-between; align-items: center;">
                            <span>Calendar</span>
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                </ul>
                <hr class="divider">
                <ul>
                    <li>
                        <a href="admin_manageinq.php" style="text-decoration: none; color: black; display: flex; justify-content: space-between; align-items: center;">
                            <span>Manage Inquiries</span>
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>
        <main class="content">
            <header class="header">
                <h1 style="color: black;">Manage Feedback</h1>
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
    <?php if (!empty($feedbacks)): ?>
        <?php foreach ($feedbacks as $feedback): ?>
            <div class="feedback-card" data-stars="<?php echo $feedback['rating']; ?>">
                <div class="profile">
                    <img src="images/profile_user_icon.png" alt="Profile Icon">
                    <span><?php echo $feedback['First_name'] . ' ' . $feedback['Middle_name'] . ' ' . $feedback['Last_name']; ?></span>
                </div>
                <p><?php echo $feedback['message']; ?></p>
                <div class="stars">
                    <?php
                    $rating = $feedback['rating'];
                    for ($i = 1; $i <= 5; $i++) {
                        echo '<i class="fa fa-star ' . ($i <= $rating ? 'filled' : '') . '"></i>';
                    }
                    ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No feedback available.</p>
    <?php endif; ?>
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
            if (!event.target.matches('.profile-icon') && !event.target.closest('.profile-container')) {
                const dropdown = document.getElementById('profile-dropdown');
                if (dropdown && dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            }

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
