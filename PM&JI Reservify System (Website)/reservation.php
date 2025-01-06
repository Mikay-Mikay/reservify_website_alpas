<?php
// Start the session to track user information
session_start();

// Initialize variables
$errors = array();


// Check if the user is logged in
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    die("You must be logged in to make a reservation.");
}

// Get the user ID from the session
$user_id = $_SESSION['id']; 

// Validate the submit button
if (isset($_POST["submit"])) {
    // Retrieve form inputs
    $event_type = $_POST["event_type"] ?? '';  
    $event_place = $_POST["event_place"] ?? '';
    $number_of_participants = $_POST["number_of_participants"] ?? '';
    $contact_number = $_POST["contact_number"] ?? '';
    $date_and_schedule = $_POST["dob"] ?? '';

    // Validate form data
    if (empty($event_type) || empty($event_place) || empty($number_of_participants) || empty($contact_number) || empty($date_and_schedule)) {
        $errors[] = "All fields are required.";
    }

    // Check if the file is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $folder = 'Images/' . $file_name;

        // Check if the uploaded file is an image
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

        if (!in_array(strtolower($file_extension), $allowed_extensions)) {
            $errors[] = "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
        } elseif (!move_uploaded_file($file_tmp, $folder)) {
            $errors[] = "File upload failed.";
        }
    } else {
        $errors[] = "Please upload an image.";
    }

    // Display errors or process the form
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {
        // Database connection
        require_once "database.php";

        // Fetch user details from test_registration table
        $user_details_query = "SELECT First_name, Middle_name, Last_Name, Email FROM test_registration WHERE id = ?";
        $user_details_stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($user_details_stmt, $user_details_query)) {
            die("Database error: Unable to prepare user details query.");
        }

        mysqli_stmt_bind_param($user_details_stmt, "i", $user_id);
        mysqli_stmt_execute($user_details_stmt);
        $result = mysqli_stmt_get_result($user_details_stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $first_name = $row['First_name'];
            $middle_name = $row['Middle_name'];
            $last_name = $row['Last_Name'];
            $email = $row['Email'];
        } else {
            die("User details not found.");
        }

        mysqli_stmt_close($user_details_stmt);

        // SQL query to insert reservation
        $sql = "INSERT INTO reservation 
                (user_id, event_type, event_place, number_of_participants, contact_number, date_and_schedule, image) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            die("Database error: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "issssss", $user_id, $event_type, $event_place, $number_of_participants, $contact_number, $date_and_schedule, $file_name);

        if (mysqli_stmt_execute($stmt)) {
            $reservation_id = mysqli_insert_id($conn);
            $_SESSION['reservation_id'] = $reservation_id;

           // Create a notification for the admin
            $notification_message = "A new reservation has been made. \n"
                . "Customer: $first_name $middle_name $last_name, \n"
                . "Email: $email, \n"
                . "Event Type: $event_type, \n"
                . "Location: $event_place, \n"
                . "Participants: $number_of_participants, \n"
                . "Date: $date_and_schedule, \n"
                . "Image: $file_name.";

            $notification_sql = "INSERT INTO admin_notifications 
                                 (user_id, reservation_id, First_name, Middle_name, Last_Name, Email, event_type, event_place, contact_number, image, message, is_read, created_at) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, FALSE, CURRENT_TIMESTAMP)";

            $notification_stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($notification_stmt, $notification_sql)) {
                die("Database error: Unable to prepare notification query.");
            }

            mysqli_stmt_bind_param($notification_stmt, "iisssssssss", $user_id, $reservation_id, $first_name, $middle_name, $last_name, $email, $event_type, $event_place, $contact_number, $file_name, $notification_message);

            if (!mysqli_stmt_execute($notification_stmt)) {
                die("Database error: Unable to execute notification query.");
            }

            mysqli_stmt_close($notification_stmt);

            // Redirect user with a success message
            echo "<script>
                alert('Reservation submitted successfully! Please wait for your approval.');
                window.location.href = 'reservation.php';
                </script>";
        } else {
            die("Database error: Unable to execute query.");
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
    
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PM&JI Reservify</title>
    <link rel="stylesheet" href="reservation.css?v=1.1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <link rel="stylesheet" href="jquery.datetimepicker.min.css">
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

        /* Upload Container */
        .upload-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-top: 20px;
        }

        .form-group {
            margin-top: 10px;
        }

        .form-group input[type="file"] {
            margin: 0 auto;
        }

        /* Submit Button Container */
        .parent-container {
            margin-top: 20px;
            text-align: center;
        }
        /*for payment*/
        .payment-link {
    color: #007bff;  /* Blue color */
    text-decoration: underline; /* Underlined text */
    cursor: pointer;
}

.payment-link:hover {
    color: #0056b3;  /* Darker blue on hover */
}
.unavailable {
    background-color: red !important;
    color: white !important;
}



    </style>
</head>
<body>
    <nav>
        <div class="logo">
            <a href="#">
                <img src="images/reservify_logo.png" alt="PM&JI logo">
                <span class="logo-text">PM&JI<br>Reservify</span>
            </a>
        </div>
        <div class="toggle">
            <a href="#"><ion-icon name="menu-outline"></ion-icon></a>
        </div>
        <ul class="menu">
            <li><a href="Home.php">Home</a></li>
            <li><a href="About Us.php">About Us</a></li>
            <li><a href="portfolio.php">Portfolio</a></li>
            <li><a href="Contact us.php">Contact Us</a></li>
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

    <div class="container">
        <div class="title">Registration</div>
        <div class="content">
            <form action="reservation.php" method="POST" enctype="multipart/form-data">
                <div class="user-details">
                    <div class="input-box">
                        <label for="eventType">Event Type:</label>
                        <select id="eventType" name="event_type" required>
                            <option value="" disabled selected>Select Event Type</option>
                            <option value="wedding">Wedding</option>
                            <option value="reunion">Reunion</option>
                            <option value="baptism">Baptism</option>
                            <option value="birthday">Birthday</option>
                            <option value="company_event">Company Event</option>
                            <option value="others">Others</option>
                        </select>
                    </div>

                    <div class="input-box" id="otherEventBox" style="display:none;">
                        <label for="otherEvent">Please specify:</label>
                        <textarea id="otherEvent" name="other_event" placeholder="Describe your event needs..." rows="4"></textarea>
                    </div>

                    <div class="input-box">
                        <label for="eventPlace">Event Place:</label>
                        <input id="eventPlace" type="text" name="event_place" placeholder="Enter Event Place" required>
                    </div>

                    <div class="input-box">
                        <label for="participants">Number of Participants:</label>
                        <input id="participants" type="number" name="number_of_participants" min="1" max="120" placeholder="Enter Number of Participants" required>
                    </div>

                    <div class="input-box">
                        <label for="contactNumber">Contact Number:</label>
                        <input id="contactNumber" type="text" name="contact_number" placeholder="e.g. 09123456712" required>
                    </div>

                    <div class="timepicker">
                        <label for="timedatePicker">Date and Schedule:</label>
                        <input type="text" id="timedatePicker" name="dob" placeholder="Select Date and Time" required>
                    </div>
                </div>

                <div class="upload-container">
                    <h2>Upload Image</h2>
                    <p>Assist us in creating temporary custom background for your selected image.</p>
                    <div class="form-group">
                        <input type="file" name="image" />
                    </div>
                </div>

                <div class="parent-container">
                    <input type="submit" name="submit" class="btn" value="Submit">
                </div>
            </form>
        </div>
    </div>

    <div class="title">
        <h2>Our Work</h2>
    </div>

    <div class="slideshow-container">
        <div class="slide fade">
            <img src="images/pic1.jpg" alt="pic1" class="normal">
        </div>
        <div class="slide fade">
            <img src="images/pic2.jpg" alt="pic2" class="normal">
        </div>
        <div class="slide fade">
            <img src="images/pic3.jpg" alt="pic3" class="normal">
        </div>
        <div class="slide fade">
            <img src="images/pic4.jpg" alt="pic4" class="normal">
        </div>
        <div class="slide fade">
            <img src="images/pic5.jpg" alt="pic5" class="normal">
        </div>
        <div class="slide fade">
            <img src="images/pic6.jpg" alt="pic6" class="normal">
        </div>
        <div class="slide fade">
            <img src="images/pic7.jpg" alt="pic7" class="normal">
        </div>
        <div class="slide fade">
            <img src="images/pic8.jpg" alt="pic8" class="normal">
        </div>
        <div class="slide fade">
            <img src="images/pic9.png" alt="pic9" class="normal">
        </div>
        <div class="slide fade">
            <img src="images/pic10.jpg" alt="pic10" class="normal">
        </div>
        <div class="slide fade">
            <img src="images/pic11.jpg" alt="pic11" class="normal">
        </div>
        <div class="slide fade">
            <img src="images/pic12.jpg" alt="pic12" class="normal">
        </div>
        <div class="slide fade">
            <img src="images/pic13.jpg" alt="pic13" class="normal">
        </div>
        <div class="slide fade">
            <img src="images/pic14.jpg" alt="pic14" class="normal">
        </div>
        <div class="slide fade">
            <img src="images/pic15.jpg" alt="pic15" class="normal">
        </div>
        <div class="slide fade">
            <img src="images/pic16.jpg" alt="pic16" class="normal">
        </div>
        <div class="slide fade">
            <img src="images/pic17.jpg" alt="pic17" class="normal">
        </div>
    
        <!-- Navigation Arrows -->
        <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
        <a class="next" onclick="changeSlide(1)">&#10095;</a>
    </div>

 
    <div class="dots-container">
        <span class="dot" onclick="currentSlide(1)"></span>
        <span class="dot" onclick="currentSlide(2)"></span>
        <span class="dot" onclick="currentSlide(3)"></span>
        <span class="dot" onclick="currentSlide(4)"></span>
        <span class="dot" onclick="currentSlide(5)"></span>
        <span class="dot" onclick="currentSlide(6)"></span>
        <span class="dot" onclick="currentSlide(7)"></span>
        <span class="dot" onclick="currentSlide(8)"></span>
        <span class="dot" onclick="currentSlide(9)"></span>
        <span class="dot" onclick="currentSlide(10)"></span>
        <span class="dot" onclick="currentSlide(11)"></span>
        <span class="dot" onclick="currentSlide(12)"></span>
        <span class="dot" onclick="currentSlide(13)"></span>
        <span class="dot" onclick="currentSlide(14)"></span>
        <span class="dot" onclick="currentSlide(15)"></span>
        <span class="dot" onclick="currentSlide(16)"></span>
    </div>


    <a href="connect_with_us.php" class="message-link">
        <div class="message-icon">
            <i class="fa fa-message"></i>
            <span>Connect with Us</span>
        </div>
    </a>

    <input type="text" id="timedatePicker" placeholder="Select date and time">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    $(document).ready(function () {
        $.ajax({
            url: 'fetch_unavailable_dates.php',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                flatpickr("#timedatePicker", {
                    enableTime: true,
                    dateFormat: "Y-m-d h:i K",
                    time_24hr: false,
                    disable: data,  // Disable unavailable dates
                    onDayCreate: function (dObj, dStr, instance) {
                        console.log("onDayCreate triggered");
                        // Log the current day and its formatted date string
                        console.log("dStr: ", dStr);
                        console.log("Unavailable Dates: ", data);

                        // Get the date from the string in 'YYYY-MM-DD' format
                        var dateString = dStr.split(' ')[0]; // 'YYYY-MM-DD'
                        console.log("Date String: ", dateString);
                        
                        // Check if the current date is in the unavailable dates
                        if (data.includes(dateString)) {
                            // Apply the 'unavailable' class to the day
                            $(dObj).addClass('unavailable');
                            console.log("Adding 'unavailable' class to: ", dateString);
                        }
                    }
                });
            },
            error: function (xhr, status, error) {
                console.error("Error fetching unavailable dates: ", error);
            }
        });
    });
            // Event Type Handling
            const eventTypeSelect = document.getElementById("eventType");
            const otherEventBox = document.getElementById("otherEventBox");
            eventTypeSelect.addEventListener("change", function() {
                otherEventBox.style.display = eventTypeSelect.value === "others" ? "block" : "none";
            });


        // Slideshow functionality
        let currentIndex = 1;  // Initialize currentIndex

// Slideshow functionality
function showSlides() {
    const slides = document.querySelectorAll(".slide");
    const dots = document.querySelectorAll(".dot");

    // Hide all slides and remove active class from dots
    slides.forEach(slide => slide.style.display = "none");
    dots.forEach(dot => dot.classList.remove("active"));

    // Show current slide and highlight corresponding dot
    if (currentIndex > slides.length) currentIndex = 1;
    if (currentIndex < 1) currentIndex = slides.length;
    slides[currentIndex - 1].style.display = "block";
    dots[currentIndex - 1].classList.add("active");

    // Increment currentIndex for the next slide
    currentIndex++;

    setTimeout(showSlides, 3000); // Change slide every 3 seconds
}

function currentSlide(index) {
    currentIndex = index;
    showSlides();
}

function changeSlide(n) {
    currentIndex += n;
    if (currentIndex < 1) currentIndex = slides.length;
    if (currentIndex > slides.length) currentIndex = 1;
    showSlides();
}

// Start the slideshow when the document is ready
document.addEventListener("DOMContentLoaded", () => {
    showSlides();  // Ensure slideshow starts when the page loads
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



    </script>
</body>
</html>
