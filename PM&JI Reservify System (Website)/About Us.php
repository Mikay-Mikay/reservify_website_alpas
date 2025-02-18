<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PM&JI Reservify</title>
    <link rel="stylesheet" href="About Us.css">
    <link rel="stylesheet" href="portfolio.css?v1=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</head>
<body>
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

            <li><a href="About Us.php">About Us</a></li>
            <li><a href="reservation.php">Reserve Now</a></li>
            <li><a href="customer_mybookings.php">My Bookings</a></li>
            <li><a href="contact_us1.php">Contact Us</a></li>
            <li class="user-logo">
                <a href="profile_user.php">
                    <img src="images/user_logo.png" alt="User Logo">
                </a>
                <div class="notification-bell">
                    <img src="images/notif_bell.png.png" alt="Notification Bell" id="notif-bell" onclick="toggleNotification()">
                    <span class="notification-count"></span>
                </div>
                <!-- Added notification dropdown -->
                <div class="notification-dropdown"></div>
            </li>
        </ul>
    </nav>

    <div class="container">
        <img src="images/reservify_logo.png" alt="PM&JI logo" id="logo-pic">
        <h1 class="reservify-text">PM&JI Reservify</h1>
    </div>

    <div class="container1">
        <div class="about-image">
            <img src="images/pic9.png">
        </div>
        <div class="about-content">
            <p>PM&JI was founded in 2019 with a deep passion for capturing life’s most precious moments through the art of photography. 
                As an independent photography company, PM&JI is dedicated to preserving memories in vivid detail, 
                specializing in high-quality images for a variety of events, including birthdays, weddings, anniversaries, 
                baptisms, corporate gatherings, and beyond. Our team has a keen eye for detail and an unwavering commitment
                to excellence, striving to create stunning visual narratives that reflect the unique essence of each occasion. 
                Whether you’re planning an intimate gathering or a grand celebration, PM&JI is here to provide a seamless photography experience, from initial consultation to the delivery of beautifully edited images.
            </p>
        </div>

    </div>
    <h1 class="reservify-text">Our Works</h1>
    <div class="tag-line">
        <p>At PM&JI Reservify, our work speaks volumes about who we are and the dedication we bring to every project. We don't just take photos; we immerse ourselves in your story, capturing the essence of each moment with passion, creativity, and meticulous attention to detail. Every photograph we create is a unique masterpiece that goes beyond simply documenting an event – it encapsulates the emotions, the atmosphere, and the unforgettable moments that make your experience truly special.</p>
    </div>

    <div class="birthday">
        <h2 class="reservify-text">Birthdays</h2>
    </div>
    
    <div class="containers">
        <div class="cards"><img src="images/pic10.jpg" alt="pic10"></div>
        <div class="cards"><img src="images/pic11.jpg" alt="pic11"></div>
        <div class="cards"><img src="images/pic12.jpg" alt="pic12"></div>
        <div class="cards"><img src="images/pic13.jpg" alt="pic13"></div>
        <div class="cards"><img src="images/pic14.jpg" alt="pic14"></div>
    </div>
    
    <div class="Company">
        <h2 class="reservify-text">Company Anniversary</h2>
    </div>

    <div class="containers1">
        <div class="cards"><img src="images/pic1.jpg" alt="pic1"></div>
        <div class="cards"><img src="images/pic2.jpg" alt="pic2"></div>
        <div class="cards"><img src="images/pic4.jpg" alt="pic4"></div>
        <div class="cards"><img src="images/pic15.jpg" alt="pic15"></div>
        <div class="cards"><img src="images/pic16.jpg" alt="pic16"></div>
    </div> 

    <div class="Reunions">
        <h2 class="reservify-text">Reunions</h2>
    </div>
    
    <div class="containers2">
        <div class="cards"><img src="images/pic3.jpg" alt="pic3"></div>
        <div class="cards"><img src="images/pic6.jpg" alt="pic6"></div>
        <div class="cards"><img src="images/pic7.jpg" alt="pic7"></div>
        <div class="cards"><img src="images/pic8.jpg" alt="pic8"></div>
        <div class="cards"><img src="images/pic17.jpg" alt="pic17"></div>
    </div>

    <a href="customer_support.php" class="message-link">
        <div class="message-icon">
            <i class="fa fa-message"></i>
            <span>Connect with Us</span>
        </div>
    </a>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(function() {
            $(".toggle").on("click", function() {
                var $menu = $(".menu");
                if ($menu.hasClass("active")) {
                    $menu.removeClass("active");
                    $(this).find("ion-icon").attr("name", "menu-outline");
                } else {
                    $menu.addClass("active");
                    $(this).find("ion-icon").attr("name", "close-outline");
                }
            });
        });
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
