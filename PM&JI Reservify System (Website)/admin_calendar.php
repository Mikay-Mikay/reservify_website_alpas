<?php
session_start();
require_once "database.php";
// Ipinapalagay na ang admin's ID ay naka-store sa session pagkatapos mag-login
$admin_ID = isset($_SESSION['admin_ID']) ? $_SESSION['admin_ID'] : 'AD-0001';

// Pag-handle ng logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin_login.php');
    exit();
}

// Pagkuha ng events mula sa reservation at test_registration tables
$events = [];
$sql = "SELECT 
            tr.first_name, tr.middle_name, tr.last_name, tr.email, 
            r.event_type, r.event_place, r.photo_size_layout, 
            r.contact_number, r.start_time AS event_start, r.end_time AS event_end,
            r.image, r.message, r.status
        FROM test_registration tr
        JOIN reservation r ON tr.id = r.user_id
        WHERE r.status = 'approved'"; // Dapat may single quotes


$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = [
            'title' => 'Event for ' . $row['first_name'] . ' ' . $row['last_name'],  // Deskriptibong pamagat tulad ng "Event for [First Name] [Last Name]"
            'start' => $row['event_start'],
            'end' => $row['event_end'],
            'extendedProps' => [
                'first_name' => $row['first_name'],
                'middle_name' => $row['middle_name'],
                'last_name' => $row['last_name'],
                'email' => $row['email'],
                'event_type' => $row['event_type'],
                'event_place' => $row['event_place'],
                'photo_size_layout' => $row['photo_size_layout'],
                'contact_number' => $row['contact_number'],
                'image' => $row['image'],
                'message' => $row['message']
            ]
        ];
    }
}

// Pagsasara ng database connection
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Calendar</title>
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="admin_calendar.css?v=1.2">
    <link rel="stylesheet" href="admin_dashboard.css?v=1.2">
    <link rel="stylesheet" href="admin_bookinghistory.css">
    <link rel="stylesheet" href="admin_profile.css?v=1.1">
    <link rel="stylesheet" href="admin_bookings.css?v=1.1">
    <link rel="stylesheet" href="admin_payments.css?v=1.1">
    <link rel="stylesheet" href="admin_managefeedback.css">
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
                            <span style="margin-left: 1px; margin-top: 4px; color: black">Dashboard</span>
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

        <main class="content">
            <header class="header">
                <h1 style="color: black;">Calendar</h1>
            <div class="header-right">
                <!-- Notification Bell -->
                <div class="notification-container">
                    <img src="images/notif_bell.png.png" alt="Notification Bell" id="notif-bell" onclick="toggleNotification()">
                    <div id="notification-dropdown" class="notification-dropdown">
                        <h2>Notifications</h2>
                        <!-- Your notification code -->
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
            
                <style>
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .popup-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .popup-content button {
            margin-top: 10px;
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .popup-content button:hover {
            background-color: #0056b3;
        }
        
        /* Change background color and text color for days with events */
.fc-day.fc-day-has-event {
    background-color: #f4a36c !important; /* Background color */
    color: black !important; /* Text color */
}
    </style>
</head>
<body>
<div class="admin-dashboard">
    <!-- Sidebar and content here -->
    <main class="content">
        <header class="header">
            <h1 style="color: black;">Calendar</h1>
        </header>

        <div id="calendar"></div>
    </main>
</div>

<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var events = <?php echo json_encode($events); ?>;

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'timeGridDay,timeGridWeek,dayGridMonth'
            },
            events: events,
            editable: true,
            selectable: true,
            selectHelper: true,
            eventClick: function(info) {
                openEventDetailsModal(info.event);
            }
        });

        calendar.render();
    });

    // Open Event Details Modal
   // Open Event Details Modal
   document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var events = <?php echo json_encode($events); ?>;

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'timeGridDay,timeGridWeek,dayGridMonth'
        },
        events: events,
        editable: true,
        selectable: true,
        selectHelper: true,
        eventClick: function(info) {
            openEventDetailsModal(info.event);
        },
        datesSet: function(info) {
            var dates = info.view.calendar.getEvents();
            dates.forEach(function(event) {
                var eventDate = event.startStr.split('T')[0]; // Get the date part (YYYY-MM-DD)
                var dayCell = document.querySelector('.fc-day[data-date="' + eventDate + '"]');
                if (dayCell) {
                    dayCell.classList.add('fc-day-has-event');
                }
            });
        }
    });

    calendar.render();

    // Open Event Details Modal
    function openEventDetailsModal(event) {
        const eventDetails = `
            <strong>Customer Name:</strong> ${event.extendedProps.first_name} ${event.extendedProps.last_name}<br>
            <strong>Email:</strong> ${event.extendedProps.email}<br>
            <strong>Event Type:</strong> ${event.extendedProps.event_type}<br>
            <strong>Event Place:</strong> ${event.extendedProps.event_place}<br>
            <strong>Photo Size/Layout:</strong> ${event.extendedProps.photo_size_layout}<br>
            <strong>Contact Number:</strong> ${event.extendedProps.contact_number}<br>
            <strong>Start Time:</strong> ${event.start.toLocaleString()}<br>
            <strong>End Time:</strong> ${event.end.toLocaleString()}<br>
        `;
        
        // Create popup overlay
        var popupOverlay = document.createElement('div');
        popupOverlay.classList.add('popup-overlay');
        
        var popupContent = document.createElement('div');
        popupContent.classList.add('popup-content');
        popupContent.innerHTML = eventDetails;
        
        var closeButton = document.createElement('button');
        closeButton.innerText = 'Close';
        closeButton.onclick = function() {
            document.body.removeChild(popupOverlay);
        };

        popupContent.appendChild(closeButton);
        popupOverlay.appendChild(popupContent);
        document.body.appendChild(popupOverlay);

        // Show popup overlay
        popupOverlay.style.display = 'flex';
    }
});

</script>

</body>
</html>
        

       
