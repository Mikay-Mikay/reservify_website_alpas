<?php
include_once 'database.php';
session_start();

// Validate admin session
if (!isset($_SESSION['fullname'])) {
    header('Location: admin_login.php');
    exit();
}

// Generate CSRF token if not already set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Get admin's name from session
$admin_name = htmlspecialchars($_SESSION['fullname'], ENT_QUOTES);

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin_login.php');
    exit();
}

// Handle event submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addEvent'])) {
    // CSRF Token Validation
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token mismatch!");
    }
    
    $customer_name = htmlspecialchars($_POST['customer_name'],ENT_QUOTES);
    $event_type = htmlspecialchars($_POST['event_type'], ENT_QUOTES);
    $event_place = htmlspecialchars($_POST['event_place'], ENT_QUOTES);
    $number_of_participants = (int)$_POST['number_of_participants'];
    $contact_number = htmlspecialchars($_POST['contact_number'], ENT_QUOTES);
    $event_start = $_POST['event_start'];
    $event_end = $_POST['event_end'];

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO admin_eventcalendar (customer_name, event_type, event_place, number_of_participants, contact_number, event_start, event_end) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssisss", $customer_name, $event_type, $event_place, $number_of_participants, $contact_number, $event_start, $event_end);

    if ($stmt->execute()) {
        echo "<script>alert('Reservation request saved successfully!');</script>";
    } else {
        echo "<script>alert('Error: Could not save the reservation request. Please try again.');</script>";
    }
    $stmt->close();
}

// Fetch events based on selected date
$events = [];
if (isset($_GET['filter_date'])) {
    $filter_date = $_GET['filter_date'];
    $sql = "SELECT customer_name, event_type, event_place, number_of_participants, contact_number, event_start, event_end 
            FROM admin_eventcalendar 
            WHERE DATE(event_start) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $filter_date);
} else {
    // Fetch all events
    $sql = "SELECT customer_name, event_type, event_place, number_of_participants, contact_number, event_start, event_end 
            FROM admin_eventcalendar";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = [
            'title' => 'Event for ' . $row['customer_name'],  // You can set a descriptive title like "Event for [Customer Name]"
            'start' => $row['event_start'],
            'end' => $row['event_end'],
            'extendedProps' => [
                'customer_name' => $row['customer_name'],
                'event_type' => $row['event_type'],
                'event_place' => $row['event_place'],
                'number_of_participants' => $row['number_of_participants'],
                'contact_number' => $row['contact_number']
            ]
        ];
        
    }
}

// Close the database connection
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
    <link rel="stylesheet" href="admin_calendar.css">
    <link rel="stylesheet" href="admin_dashboard.css?v=1.1">
    <link rel="stylesheet" href="admin_bookinghistory.css">
    <link rel="stylesheet" href="admin_profile.css?v=1.1">
    <link rel="stylesheet" href="admin_bookingstatus.css?v=1.1">
    <link rel="stylesheet" href="admin_payments.css?v=1.1">
    <link rel="stylesheet" href="admin_managefeedback.css">
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
            <h1>Calendar</h1>
            <div class="header-right">
                <!-- Notification Bell -->
                <div class="notification-container">
                    <img src="images/notif_bell.png.png" alt="Notification Bell" id="notif-bell" onclick="toggleNotification()">
                    <div id="notification-dropdown" class="notification-dropdown">
                        <h2>Notifications</h2>
                        <?php if (!empty($notifications)): ?>
                            <?php foreach ($notifications as $notification): ?>
                                <div class="notification">
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

        <div id="calendar"></div>
    </main>
</div>


<!-- Modal for Adding Event -->
<div id="eventModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h2>Add Event</h2>

        <form action="admin_calendar.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <label for="customer_name">Customer Name:</label>
            <input type="text" id="customer_name" name="customer_name" required>

            <label for="event_type">Event Type:</label>
            <input type="text" id="event_type" name="event_type" required>

            <label for="event_place">Event Place:</label>
            <input type="text" id="event_place" name="event_place" required>

            <label for="number_of_participants">Number of Participants:</label>
            <input type="number" id="number_of_participants" name="number_of_participants" required>

            <label for="contact_number">Contact Number:</label>
            <input type="number" id="contact_number" name="contact_number" required>

            <label for="event_start">Start Time:</label>
            <input type="datetime-local" id="event_start" name="event_start" required>

            <label for="event_end">End Time:</label>
            <input type="datetime-local" id="event_end" name="event_end" required>

            <button type="submit" name="addEvent">Add Event</button>
            <button type="button" onclick="closeModal()">Cancel</button>
        </form>
    </div>
    <!-- Modal for Event Details -->
<div id="eventDetailsModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeEventDetailsModal()">&times;</span>
        <h2>Reservation Details</h2>
        <div id="eventDetailsContent"></div>
    </div>
</div>

</div>



   
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
        select: function(info) {
            openAddEventModal(info.startStr, info.endStr);
        },
        eventClick: function(info) {
            openEventDetailsModal(info.event);
        }
    });

    calendar.render();
});

// Open Add Event Modal
function openAddEventModal(start, end) {
    document.getElementById('event_start').value = start;
    document.getElementById('event_end').value = end;
    document.getElementById('eventModal').style.display = 'flex';
}

// Open Event Details Modal
function openEventDetailsModal(event) {
    const eventDetails = `
       <strong>Customer Name:</strong> ${event.extendedProps.customer_name}<br>
        <strong>Event Type:</strong> ${event.extendedProps.event_type}<br>
        <strong>Event Place:</strong> ${event.extendedProps.event_place}<br>
        <strong>Number of Participants:</strong> ${event.extendedProps.number_of_participants}<br>
        <strong>Contact Number:</strong> ${event.extendedProps.contact_number}<br>
        <strong>Start Time:</strong> ${event.start.toLocaleString()}<br>
        <strong>End Time:</strong> ${event.end.toLocaleString()}<br>
        
    `;
    
    // Create a popup with the event details
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

    // Display the popup overlay
    popupOverlay.style.display = 'flex';
}

// Close Add Event Modal
function closeModal() {
    document.getElementById('eventModal').style.display = 'none';
}

// Close the modal when clicking outside of the modal content
window.onclick = function(event) {
    if (event.target == document.getElementById('eventModal')) {
        closeModal();
    }
}

</script>
<style>
/* Modal Structure */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4);
    justify-content: center;
    align-items: center;
}

/* Modal Content */
.modal-content {
    background-color: #fff;
    margin: 10% auto;
    padding: 20px;
    border-radius: 10px;
    width: 80%;
    max-width: 600px;
}

/* Close Button */
.close-btn {
    color: #aaa;
    position: absolute;
    top: 10px;
    right: 20px;
    font-size: 28px;
    font-weight: bold;
}

.close-btn:hover,
.close-btn:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

/* Submit Button - Green */
.modal form button[type="submit"] {
    background-color: #4CAF50; /* Green */
    color: white;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    margin-top: 10px;
    font-size: 16px;
    width: 100%;
    border-radius: 5px;
}

/* Cancel Button - Red */
.modal form button[type="button"] {
    background-color: #f44336; /* Red */
    color: white;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    margin-top: 10px;
    font-size: 16px;
    width: 100%;
    border-radius: 5px;
}

/* Button Hover Effects */
.modal form button[type="submit"]:hover {
    background-color: #45a049; /* Darker green */
}

.modal form button[type="button"]:hover {
    background-color: #da190b; /* Darker red */
}

/* Popup Content (Event Details) */
.popup-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
    z-index: 9999; /* Ensure it's on top */
}

.popup-content {
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    width: 80%;
    max-width: 500px;
    text-align: left;
    z-index: 10000; /* Make sure content is above overlay */
    color: black;
}

/* General Button Style */
button {
    background-color: #007bff;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 20px; /* Adjust this value to move the button further down */
    width: 100%;
}

button:hover {
    background-color: #0056b3;
}


</style>


</body>
</html>