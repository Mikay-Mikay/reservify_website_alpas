<?php
// Start the session to retrieve user information
session_start();

// Database connection
require_once "database.php";

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve the user ID from the session
$user_id = $_SESSION['id'];

// Query to fetch the required data from the test_registration table
$sql = "
    SELECT
        tr.Date_of_Birth,
        tr.Address,
        tr.Last_Name,
        tr.Middle_Name,
        tr.First_Name,
        tr.Gender,
        tr.Phone_Number,
        tr.Email
    FROM test_registration tr
    WHERE tr.id = ?";

// Prepare and execute the query
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id); // Binding the user_id parameter
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    if ($data) {
        // Data fetched successfully
        $First_Name = $data['First_Name'];
        $Middle_Name = $data['Middle_Name'];
        $Last_Name = $data['Last_Name'];
        $Email = $data['Email'];
        $Phone_Number = $data['Phone_Number'];
        $Address = $data['Address'];
        $Date_of_Birth = $data['Date_of_Birth'];
        $Gender = $data['Gender'];
    } else {
        echo "No data found for the user.";
        exit();
    }
} else {
    echo "Database query error: " . mysqli_error($conn);
    exit();
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PM&JI Reservify</title>
    <link rel="stylesheet" href="profile_user.css?v=1.2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</head>
<style>
        /* Dropdown Styling */
        .notification-dropdown {
            display: none;
            position: absolute;
            top: 40px;
            right: 20px;
            width: 300px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .notification-dropdown.show {
            display: block;
        }

        .notification-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
        }

        .notification-item:hover {
            background-color: #f9f9f9;
        }

        .notification-item .time {
            font-size: 0.8rem;
            color: #666;
        }

        .notification-bell {
            position: relative;
            cursor: pointer;
        }

        .notification-bell .notification-count {
            position: absolute;
            top: 0;
            right: 0;
            background-color: red;
            color: white;
            border-radius: 50%;
            font-size: 0.8rem;
            padding: 3px 7px;
        }

        #notif-bell {
            width: 40px;
            height: auto;
            cursor: pointer;
            display: inline-block;
        }
</style>
<body>
    <nav>
        <div class="logo">
            <a href="Home.php">
                <img src="images/reservify_logo.png" alt="PM&JI logo">
                <span class="logo-text">PM&JI<br>Reservify</span>
            </a>
        </div>
        <div class="toggle">
            <a href="#"><ion-icon name="menu-outline"></ion-icon></a>
        </div>
        <ul class="menu">
            <li><a href="About Us.php">About Us</a></li> <!-- ginawa kong About Us.php -->
            <li><a href="reservation.php">Reserve Now</a></li> <!-- ginawa kong About Us.php -->
            <li><a href="customer_mybookings.php">My Bookings</a></li> <!-- nag lagay ako ng my bookings sa navbar -->
            <li><a href="contact_us1.php">Contact Us</a></li>
            <li class="user-logo">
                <a href="profile_user.php">
                    <img src="images/user_logo.png" alt="User Logo">
                </a>
            </li>
            <li>
            <div class="notification-bell">
                    <img src="images/notif_bell.png.png" alt="Notification Bell" id="notif-bell" onclick="toggleNotification()">
                    <span class="notification-count"></span>
                </div>
                <div class="notification-dropdown">
                    <p>Loading notifications...</p>
                </div>
            </li>
        </ul>
    </nav>

    <div class="profile-header">
        <h1>Profile</h1>
    </div>

    <div class="profile-container">
        <form action="update_profile.php" method="POST">
            <div class="profile-info">
                <div class="profile-row">
                    <label for="dob"><img src="images/dob.png" alt="Date of Birth Icon"> Date of Birth:</label>
                    <input type="text" id="dob" name="dob" value="<?php echo htmlspecialchars($Date_of_Birth); ?>" placeholder="MM/DD/YY">
                </div>
                <div class="profile-row">
                    <label for="address"><img src="images/country_region.png" alt="Country/Region Icon"> Full Address:</label>
                    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($Address); ?>">
                </div>
                <div class="profile-row">
                    <label for="display_name"><img src="images/profile_user_icon.png" alt="Display Name Icon"> Display Name:</label>
                    <input type="text" id="display_name" name="display_name" value="<?php echo htmlspecialchars($First_Name . ' ' . $Middle_Name . ' ' . $Last_Name); ?>">
                </div>
                <div class="profile-row">
                    <label for="gender"><img src="images/gender.png" alt="Gender Icon"> Gender:</label>
                    <input type="text" id="gender" name="gender" value="<?php echo htmlspecialchars($Gender); ?>">
                </div>
                <div class="profile-row">
                    <label for="tel"><img src="images/tel.png" alt="Phone Icon"> Tel#:</label>
                    <input type="text" id="tel" name="tel" value="<?php echo htmlspecialchars($Phone_Number); ?>">
                </div>
                <div class="profile-row">
                    <label for="email"><img src="images/email.png" alt="Email Icon"> Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($Email); ?>">
                </div>
            </div>
            <div class="profile-footer">
    <a href="Edit Password.php">
        <button type="button" class="btn-edit-password">Edit Password</button>
    </a>
    <a href="reservation.php">
        <button type="button" class="btn-cancel">Cancel</button>
    </a>
    <button type="submit" class="btn-save">Save</button>

    <a href="?logout=true" class="btn-logout">Log Out</a>
</div>

        </form>

        
    </div>
    <script>
  // Notification functionality
const fetchNotifications = async () => {
    try {
        const response = await fetch('fetch_notification.php');
        const notifications = await response.json();
        
        // Check if there are any notifications
        if (notifications.length > 0) {
            document.querySelector('.notification-count').textContent = notifications.length;

            // Build the dropdown content
            const dropdownContent = notifications.map(notification => {
                let message = notification.message;

                // Validate and format the time and date when the notification was received
                let notificationTime = new Date(notification.time);
                
                // Check if the date is valid
                if (isNaN(notificationTime)) {
                    console.error("Invalid date:", notification.time); // Log the invalid date to the console
                    notificationTime = new Date(); // Set to current date/time if invalid
                }

                let formattedTime = notificationTime.toLocaleString('en-US', { 
                    weekday: 'short', year: 'numeric', month: 'short', day: 'numeric', 
                    hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true 
                });

                // Return the notification item with formatted date and time
                return `
                    <div class="notification-item">
                        ${message} <span class="time">${formattedTime}</span>
                    </div>
                `;
            }).join("");

            // Set the content to the dropdown using innerHTML to parse any HTML tags in the message
            document.querySelector(".notification-dropdown").innerHTML = dropdownContent;
        } else {
            // No notifications
            document.querySelector(".notification-dropdown").innerHTML = "<p>No new notifications</p>";
        }
    } catch (error) {
        console.error('Error fetching notifications:', error);
        document.querySelector(".notification-dropdown").innerHTML = "<p>Failed to load notifications</p>";
    }
};

const toggleNotification = () => {
    document.querySelector(".notification-dropdown").classList.toggle("show");
};

// Close the dropdown when clicked outside
document.addEventListener("click", (e) => {
    if (!e.target.closest(".notification-bell")) {
        document.querySelector(".notification-dropdown").classList.remove("show");
    }
});

// Initialize notifications on page load
document.addEventListener("DOMContentLoaded", fetchNotifications);

document.getElementById("bookingStatusBtn").addEventListener("click", function() {
        fetch("fetch_reservation.php")
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    document.getElementById("bookingDetails").innerHTML = `<p>${data.error}</p>`;
                } else {
                    document.getElementById("bookingDetails").innerHTML = `
                        <p><strong>Event Type:</strong> ${data.event_type}</p>
                        <p><strong>Location:</strong> ${data.event_place}</p>
                        <p><strong>Participants:</strong> ${data.number_of_participants}</p>
                        <p><strong>Contact:</strong> ${data.contact_number}</p>
                        <p><strong>Start Time:</strong> ${data.start_time}</p>
                        <p><strong>End Time:</strong> ${data.end_time}</p>
                        <p><strong>Message:</strong> ${data.message}</p>
                        <p><strong>Status:</strong> ${data.status}</p>
                        <img src="Images/${data.image}" alt="Event Image" width="100%">
                    `;
                }
                document.getElementById("bookingStatusModal").style.display = "block";
            })
            .catch(error => console.error("Error fetching reservation:", error));
    });

    document.querySelector(".close").addEventListener("click", function() {
        document.getElementById("bookingStatusModal").style.display = "none";
    });

    window.onclick = function(event) {
        if (event.target == document.getElementById("bookingStatusModal")) {
            document.getElementById("bookingStatusModal").style.display = "none";
        }
    };

    </script>
</body>
</html>
