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
                </ul>
                <hr class="divider">
                <ul>
                    <li>
                        <a href="admin_calendar.php"style="text-decoration: none; color: white; display: flex; justify-content: space-between; align-items: center;">
                        <span>Calendar</span>
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                    <li>
                        <a href="admin_progress.php"style="text-decoration: none; color: white; display: flex; justify-content: space-between; align-items: center;">
                        <span>Progress</span>
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
        <div class="booking-list">
                <div class="booking-item" onclick="showDetails('PMJI-20241130-CUST001')">
                    PMJI-20241130-CUST001
                    <img src="images/click_here_black.png.png" alt="Click here" class="click-icon">
                </div>
                <div class="booking-item" onclick="showDetails('PMJI-20241130-CUST002')">
                    PMJI-20241130-CUST002
                    <img src="images/click_here_black.png.png" alt="Click here" class="click-icon">
                </div>
                <div class="booking-item" onclick="showDetails('PMJI-20241130-CUST003')">
                    PMJI-20241130-CUST003
                    <img src="images/click_here_black.png.png" alt="Click here" class="click-icon">
                </div>
                <div class="booking-item" onclick="showDetails('PMJI-20241130-CUST004')">
                    PMJI-20241130-CUST004
                    <img src="images/click_here_black.png.png" alt="Click here" class="click-icon">
                </div>
                <div class="booking-item" onclick="showDetails('PMJI-20241130-CUST005')">
                    PMJI-20241130-CUST005
                    <img src="images/click_here_black.png.png" alt="Click here" class="click-icon">
                </div>
                <div class="booking-item" onclick="showDetails('PMJI-20241130-CUST006')">
                    PMJI-20241130-CUST006
                    <img src="images/click_here_black.png.png" alt="Click here" class="click-icon">
                </div>
                <div class="booking-item" onclick="showDetails('PMJI-20241130-CUST007')">
                    PMJI-20241130-CUST007
                    <img src="images/click_here_black.png.png" alt="Click here" class="click-icon">
                </div>
                <div class="booking-item" onclick="showDetails('PMJI-20241130-CUST008')">
                    PMJI-20241130-CUST008
                    <img src="images/click_here_black.png.png" alt="Click here" class="click-icon">
                </div>
                <div class="booking-item" onclick="showDetails('PMJI-20241130-CUST009')">
                    PMJI-20241130-CUST009
                    <img src="images/click_here_black.png.png" alt="Click here" class="click-icon">
                </div>
                <div class="booking-item" onclick="showDetails('PMJI-20241130-CUST010')">
                    PMJI-20241130-CUST010
                    <img src="images/click_here_black.png.png" alt="Click here" class="click-icon">
                </div>
                <div class="booking-item" onclick="showDetails('PMJI-20241130-CUST011')">
                    PMJI-20241130-CUST011
                    <img src="images/click_here_black.png.png" alt="Click here" class="click-icon">
                </div>
                <div class="booking-item" onclick="showDetails('PMJI-20241130-CUST012')">
                    PMJI-20241130-CUST012
                    <img src="images/click_here_black.png.png" alt="Click here" class="click-icon">
                </div>
                <div class="booking-item" onclick="showDetails('PMJI-20241130-CUST013')">
                    PMJI-20241130-CUST013
                    <img src="images/click_here_black.png.png" alt="Click here" class="click-icon">
                </div>
                <div class="booking-item" onclick="showDetails('PMJI-20241130-CUST014')">
                    PMJI-20241130-CUST014
                    <img src="images/click_here_black.png.png" alt="Click here" class="click-icon">
                </div>
                <div class="booking-item" onclick="showDetails('PMJI-20241130-CUST015')">
                    PMJI-20241130-CUST015
                    <img src="images/click_here_black.png.png" alt="Click here" class="click-icon">
                </div>
                <div class="booking-item" onclick="showDetails('PMJI-20241130-CUST016')">
                    PMJI-20241130-CUST016
                    <img src="images/click_here_black.png.png" alt="Click here" class="click-icon">
                </div>
                <div class="booking-item" onclick="showDetails('PMJI-20241130-CUST017')">
                    PMJI-20241130-CUST017
                    <img src="images/click_here_black.png.png" alt="Click here" class="click-icon">
                </div>
                <div class="booking-item" onclick="showDetails('PMJI-20241130-CUST018')">
                    PMJI-20241130-CUST018
                    <img src="images/click_here_black.png.png" alt="Click here" class="click-icon">
                </div>
                <div class="booking-item" onclick="showDetails('PMJI-20241130-CUST019')">
                    PMJI-20241130-CUST019
                    <img src="images/click_here_black.png.png" alt="Click here" class="click-icon">
                </div>
                <div class="booking-item" onclick="showDetails('PMJI-20241130-CUST020')">
                    PMJI-20241130-CUST020
                    <img src="images/click_here_black.png.png" alt="Click here" class="click-icon">
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Container -->
<div id="details-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <a href="admin_bookinghistory.php">
                <img src="images/back button.png" alt="Back" class="close-btn">
            </a>
            <h2 id="modal-title"></h2>
        </div>
        <p id="modal-content"></p>
    </div>
</div>


    <script>
    // Booking details data
    const bookingData = {
        "PMJI-20241130-CUST001": {
            title: "PMJI-20241130-CUST001",
            content: `
                Event Type: Wedding<br>
                Event Place: XYZ Garden, Quezon City<br>
                Event Date: Saturday, December 1, 2024<br>
                Status: Approved<br>
                Mode of Payment: Credit Card<br>
                Total Amount: P20,000.00<br>
                Payment Status: Paid
            `
        },
        "PMJI-20241130-CUST002": {
            title: "PMJI-20241130-CUST002",
            content: `
                Event Type: Birthday Party<br>
                Event Place: ABC Banquet Hall, Manila<br>
                Event Date: Sunday, December 2, 2024<br>
                Status: Pending<br>
                Mode of Payment: Cash<br>
                Total Amount: P10,000.00<br>
                Payment Status: Unpaid
            `
        },
        "PMJI-20241130-CUST003": {
            title: "PMJI-20241130-CUST003",
            content: `
                Event Type: Company Christmas Party<br>
                Event Place: ABC Corporation Center, Marikina Philippines<br>
                Event Date: Monday, December 2, 2024<br>
                Status: Approved<br>
                Mode of Payment: GCash<br>
                Total Amount: P15,000.00<br>
                Payment Status: Paid
            `
        },
        "PMJI-20241130-CUST004": {
            title: "PMJI-20241130-CUST004",
            content: `
                Event Type: Baby Shower<br>
                Event Place: Little Wonders Venue, Makati<br>
                Event Date: Thursday, December 5, 2024<br>
                Status: Approved<br>
                Mode of Payment: Bank Transfer<br>
                Total Amount: P12,000.00<br>
                Payment Status: Paid
            `
        },
        "PMJI-20241130-CUST005": {
            title: "PMJI-20241130-CUST005",
            content: `
                Event Type: Corporate Meeting<br>
                Event Place: Global Business Tower, Taguig<br>
                Event Date: Friday, December 6, 2024<br>
                Status: Approved<br>
                Mode of Payment: Credit Card<br>
                Total Amount: P30,000.00<br>
                Payment Status: Paid
            `
        },
        "PMJI-20241130-CUST006": {
            title: "PMJI-20241130-CUST006",
            content: `
                Event Type: Anniversary Celebration<br>
                Event Place: Grand Ballroom, Quezon City<br>
                Event Date: Saturday, December 7, 2024<br>
                Status: Pending<br>
                Mode of Payment: Cash<br>
                Total Amount: P18,000.00<br>
                Payment Status: Unpaid
            `
        },
        "PMJI-20241130-CUST007": {
            title: "PMJI-20241130-CUST007",
            content: `
                Event Type: Charity Gala<br>
                Event Place: Metro Convention Center, Makati<br>
                Event Date: Sunday, December 8, 2024<br>
                Status: Approved<br>
                Mode of Payment: Credit Card<br>
                Total Amount: P50,000.00<br>
                Payment Status: Paid
            `
        },
        "PMJI-20241130-CUST008": {
            title: "PMJI-20241130-CUST008",
            content: `
                Event Type: Product Launch<br>
                Event Place: Innovation Center, Quezon City<br>
                Event Date: Monday, December 9, 2024<br>
                Status: Pending<br>
                Mode of Payment: GCash<br>
                Total Amount: P25,000.00<br>
                Payment Status: Unpaid
            `
        },
        "PMJI-20241130-CUST009": {
            title: "PMJI-20241130-CUST009",
            content: `
                Event Type: Wedding Reception<br>
                Event Place: Sapphire Hotel, Manila<br>
                Event Date: Tuesday, December 10, 2024<br>
                Status: Approved<br>
                Mode of Payment: Bank Transfer<br>
                Total Amount: P40,000.00<br>
                Payment Status: Paid
            `
        },
        "PMJI-20241130-CUST010": {
            title: "PMJI-20241130-CUST010",
            content: `
                Event Type: Holiday Party<br>
                Event Place: Elite Hotel, Pasig<br>
                Event Date: Thursday, December 12, 2024<br>
                Status: Approved<br>
                Mode of Payment: Credit Card<br>
                Total Amount: P22,000.00<br>
                Payment Status: Paid
            `
        },
        "PMJI-20241130-CUST011": {
            title: "PMJI-20241130-CUST011",
            content: `
                Event Type: Corporate Seminar<br>
                Event Place: Greenfield Conference Hall, Mandaluyong<br>
                Event Date: Friday, December 13, 2024<br>
                Status: Approved<br>
                Mode of Payment: Cash<br>
                Total Amount: P35,000.00<br>
                Payment Status: Paid
            `
        },
        "PMJI-20241130-CUST012": {
            title: "PMJI-20241130-CUST012",
            content: `
                Event Type: Concert<br>
                Event Place: Metro Arena, Quezon City<br>
                Event Date: Saturday, December 14, 2024<br>
                Status: Pending<br>
                Mode of Payment: GCash<br>
                Total Amount: P45,000.00<br>
                Payment Status: Unpaid
            `
        },
        "PMJI-20241130-CUST013": {
            title: "PMJI-20241130-CUST013",
            content: `
                Event Type: Graduation Party<br>
                Event Place: Prestige Hall, Pasay<br>
                Event Date: Sunday, December 15, 2024<br>
                Status: Approved<br>
                Mode of Payment: Credit Card<br>
                Total Amount: P28,000.00<br>
                Payment Status: Paid
            `
        },
        "PMJI-20241130-CUST014": {
            title: "PMJI-20241130-CUST014",
            content: `
                Event Type: Team Building<br>
                Event Place: Adventure Resort, Tagaytay<br>
                Event Date: Monday, December 16, 2024<br>
                Status: Pending<br>
                Mode of Payment: Cash<br>
                Total Amount: P18,500.00<br>
                Payment Status: Unpaid
            `
        },
        "PMJI-20241130-CUST015": {
            title: "PMJI-20241130-CUST015",
            content: `
                Event Type: Charity Auction<br>
                Event Place: Luxury Ballroom, Makati<br>
                Event Date: Wednesday, December 17, 2024<br>
                Status: Approved<br>
                Mode of Payment: Bank Transfer<br>
                Total Amount: P55,000.00<br>
                Payment Status: Paid
            `
        },
        "PMJI-20241130-CUST016": {
            title: "PMJI-20241130-CUST016",
            content: `
                Event Type: Product Demo<br>
                Event Place: City Hall Conference Center, Quezon City<br>
                Event Date: Thursday, December 18, 2024<br>
                Status: Approved<br>
                Mode of Payment: Credit Card<br>
                Total Amount: P27,000.00<br>
                Payment Status: Paid
            `
        },
        "PMJI-20241130-CUST017": {
            title: "PMJI-20241130-CUST017",
            content: `
                Event Type: Birthday Party<br>
                Event Place: Palm Garden Hotel, Quezon City<br>
                Event Date: Friday, December 19, 2024<br>
                Status: Pending<br>
                Mode of Payment: GCash<br>
                Total Amount: P12,000.00<br>
                Payment Status: Unpaid
            `
        },
        "PMJI-20241130-CUST018": {
            title: "PMJI-20241130-CUST018",
            content: `
                Event Type: Workshop<br>
                Event Place: Knowledge Hub, Makati<br>
                Event Date: Saturday, December 20, 2021<br>
                Status: Approved<br>
                Mode of Payment: Bank Transfer<br>
                Total Amount: P5,000.00<br>
                Payment Status: Paid
            `
        },
        "PMJI-20241130-CUST019": {
            title: "PMJI-20241130-CUST019",
            content: `
                Event Type: Retirement Party<br>
                Event Place: Golden Hall, Pasig<br>
                Event Date: Sunday, December 21, 2024<br>
                Status: Approved<br>
                Mode of Payment: Cash<br>
                Total Amount: P10,000.00<br>
                Payment Status: Paid
            `
        },
        "PMJI-20241130-CUST020": {
            title: "PMJI-20241130-CUST020",
            content: `
                Event Type: New Year's Eve Party<br>
                Event Place: Sky Lounge, Makati<br>
                Event Date: Wednesday, December 31, 2024<br>
                Status: Pending<br>
                Mode of Payment: Credit Card<br>
                Total Amount: P35,000.00<br>
                Payment Status: Unpaid
            `
        }
    };
    // Show modal with booking details
    function showDetails(bookingId) {
        const modal = document.getElementById('details-modal');
        const modalTitle = document.getElementById('modal-title');
        const modalContent = document.getElementById('modal-content');

        // Update modal content
        if (bookingData[bookingId]) {
            modalTitle.innerHTML = bookingData[bookingId].title;
            modalContent.innerHTML = bookingData[bookingId].content;
            modal.style.display = "flex";
        }
    }

    // Close modal
    function closeDetails() {
        const modal = document.getElementById('details-modal');
        modal.style.display = "none";
    }

    // Close modal when clicking outside the content
    window.onclick = function (event) {
        const modal = document.getElementById('details-modal');
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };

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
