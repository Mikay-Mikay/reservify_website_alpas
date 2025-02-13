<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PM&JI Reservify</title>
    <link rel="stylesheet" href="customer_mybookings.css?v=1.1">
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


.status-circle {
    display: inline-block;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 5px;
}
.approved {
    background-color: green; /* Green for Approved */
}

.rejected {
    background-color: red; /* Red for Rejected */
}


.booking-container {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.booking-card {
    display: flex;
    flex-direction: column;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
}

.booking-header {
    display: flex;
    align-items: center;
    justify-content: flex-end; /* Aligns status indicator to the right */
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
                <!-- Added notification dropdown -->
                <div class="notification-dropdown"></div>
            </li>
        </ul>
    </nav>
    <div class="back-button">   
            <a href="javascript:history.back()">
                <img src="images/back button.png" alt="Back">
            </a>
        </div>
        <h1 class="page-title">My Bookings</h1>

    <div class="container">
        <div class="buttons">
            <button class="active-bookings">Active Bookings</button>
            <button class="previous-bookings">Previous Bookings</button>
        </div>
        
        <div class="booking-container" id="bookingContainer"></div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    fetch("fetch_reservation.php")
        .then(response => response.json())
        .then(data => {
            let bookingContainer = document.getElementById("bookingContainer");

            if (data.error) {
                bookingContainer.innerHTML = `<p>${data.error}</p>`;
            } else if (data.length === 0) {
                bookingContainer.innerHTML = `<p class="no-bookings">No new bookings</p>`;
            } else {
                let bookingsHTML = "";
                data.forEach(reservation => {
                    let status = reservation.status.trim().toLowerCase(); // Normalize status
                    let statusIndicator = status === "approved"
                        ? '<span class="status-circle approved"></span>'  // Green for approved
                        : '<span class="status-circle rejected"></span>'; // Red for rejected

                    bookingsHTML += `
                        <div class="booking-card">
                            <div class="booking-header">
                                ${statusIndicator}
        
                            </div>
                            <div class="booking-details">
                                <p><strong>Event:</strong> ${reservation.event_type}</p>
                                <p><strong>Location:</strong> ${reservation.event_place}</p>
                                <p><strong>Layout:</strong> ${reservation.photo_size_layout}</p>
                                <p><strong>Contact:</strong> ${reservation.contact_number}</p>
                                <p><strong>Schedule:</strong> ${reservation.start_time} - ${reservation.end_time}</p>
                                 <p><strong>Status:</strong> ${status}</p>
                            </div>
                        </div>
                    `;
                });
                bookingContainer.innerHTML = bookingsHTML;
            }
        })
        .catch(error => {
            console.error("Error fetching reservations:", error);
            document.getElementById("bookingContainer").innerHTML = `<p class="no-bookings">Failed to load bookings</p>`;
        });
});
</script>

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

    //<!-- JavaScript for Image Preview -->
    function previewImage(event) {
    var image = document.getElementById('imagePreview');
    var file = event.target.files[0];

    if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            image.src = e.target.result;
            image.style.display = "block"; // Show the image preview
        };
        reader.readAsDataURL(file);
    } else {
        image.style.display = "none"; // Hide preview if no image selected
    }
}

    </script>
</body>
</html>
