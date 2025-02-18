<?php
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
    $others = $_POST["other_event"] ?? '';
    $event_place = $_POST["event_place"] ?? '';
    $photo_size_layout = $_POST["photo_size_layout"] ?? '';
    $contact_number = $_POST["contact_number"] ?? '';
    $start_time = $_POST["timedatePickerStart"] ?? '';
    $end_time = $_POST["timedatePickerEnd"] ?? '';
    $file_name = NULL; // Default value kapag walang image

    // Validate form data
    if (empty($event_type) || empty($event_place) || empty($photo_size_layout) || empty($contact_number) || empty($start_time) || empty($end_time)) {
        $errors[] = "All fields are required.";
    }

    // Check if "Others" is selected and the "other_event" field is empty
    if ($event_type == 'others' && empty($others)) {
        $errors[] = "Please specify your event details.";
    }

    // Check if the file is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_size = $_FILES['image']['size'];

        // Set the upload folder path
        $folder = 'uploads/' . $file_name; 

        // Allowed file extensions
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Check file extension
        if (!in_array($file_extension, $allowed_extensions)) {
            $errors[] = "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
        }

        // Check file size (limit to 5MB)
        if ($file_size > 5 * 1024 * 1024) { // 5MB in bytes
            $errors[] = "File size exceeds 5MB limit.";
        }

        // Move uploaded file if no errors
        if (empty($errors)) {
            if (!move_uploaded_file($file_tmp, $folder)) {
                $errors[] = "File upload failed.";
            }
        }
    } else {
        // Walang inupload na image, itatago natin ito bilang NULL
        $file_name = ""; // Avoid "Column 'image' cannot be null" error
    }

    // Display errors or process the form
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
        exit(); // Stop execution if there are errors
    }

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
    (user_id, event_type, others, event_place, photo_size_layout, contact_number, start_time, end_time, image) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die("Database error: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "issssssss", $user_id, $event_type, $others, $event_place, $photo_size_layout, $contact_number, $start_time, $end_time, $file_name);

    if (mysqli_stmt_execute($stmt)) {
        $reservation_id = mysqli_insert_id($conn);
        $_SESSION['reservation_id'] = $reservation_id;

        // Create a notification message
        $notification_message = "A new reservation has been made. \n"
        . "Customer: $first_name $middle_name $last_name, \n"
        . "Email: $email, \n"
        . "Event Type: $event_type, \n"
        . "Location: $event_place, \n"
        . "Photo Layout: $photo_size_layout, \n"
        . "Start Time: $start_time, \n"
        . "End Time: $end_time, \n"
        . "Image: " . ($file_name ?? "No image uploaded") . ".";

        if ($event_type == 'others' && !empty($others)) {
            $notification_message .= "\nOther Event Details: $others";
        }

        // Insert notification into admin_notifications table
        $notification_sql = "INSERT INTO admin_notifications 
                             (user_id, reservation_id, First_name, Middle_name, Last_Name, Email, event_type, others, event_place, photo_size_layout, contact_number, image, message, is_read, created_at) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, FALSE, CURRENT_TIMESTAMP)";

        $notification_stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($notification_stmt, $notification_sql)) {
            die("Database error: Unable to prepare notification query.");
        }

        mysqli_stmt_bind_param($notification_stmt, "iisssssssssss", 
            $user_id, $reservation_id, $first_name, $middle_name, $last_name, $email, 
            $event_type, $others, $event_place, $photo_size_layout, $contact_number, $file_name, $notification_message);

        if (!mysqli_stmt_execute($notification_stmt)) {
            die("Database error: Unable to execute notification query.");
        }

        mysqli_stmt_close($notification_stmt);

        // Redirect user with a success message
        if (mysqli_stmt_execute($stmt)) {
            $reservation_id = mysqli_insert_id($conn);
            $_SESSION['reservation_id'] = $reservation_id;
        
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('customModal').style.display = 'block';
                });
            </script>";
        }
    } else {
        die("Database error: Unable to execute query.");
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PM&JI Reservify</title>
    <link rel="stylesheet" href="reservation.css?v=1.4">
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
/* Container to hold the inputs in two columns */
.input-container {
    display: flex; /* Flexbox layout */
    gap: 20px; /* Space between the columns */
    flex-wrap: wrap; /* Ensures it wraps on smaller screens */
    align-items: center; /* Vertically center the items within the container */
    justify-content: center; /* Horizontally center the container */
    width: 100%; /* Ensure full width for the container */
    max-width: 800px; /* Optional: Set a max-width to prevent it from stretching too wide */
    margin: 0 auto; /* Center the container on the page */
}

/* Common styles for both start and end time inputs */
input[type="text"] {
    flex: 1; /* Make each input take equal width in the container */
    padding: 12px 15px; /* Spacing inside the input field */
    margin-bottom: 15px; /* Space between input fields */
    border: 1px solid #ccc; /* Border color */
    border-radius: 8px; /* Rounded corners */
    font-size: 16px; /* Text size */
    background-color: #fff; /* Background color */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    transition: border-color 0.3s ease, box-shadow 0.3s ease; /* Smooth transition for focus effects */
    height: 45px; /* Add a height to the input fields */
}

/* Ensure both inputs fit in the container */
.input-container input[type="text"] {
    margin-bottom: 0; /* Remove bottom margin on the inputs in the same row */
}

/* Adjust input to take up full width on small screens */
@media (max-width: 768px) {
    .input-container {
        flex-direction: column; /* Stack the inputs vertically on small screens */
        align-items: flex-start; /* Align items to the start in vertical layout */
    }

    input[type="text"] {
        width: 100%; /* Make inputs full width on smaller screens */
    }
}

/* Labels for clarity */
label {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 5px;
    display: block;
    color: #333;
}

.preview-container {
        margin-top: 10px;
        text-align: center;
    }

    #imagePreview {
        display: none; /* Hidden by default */
        width: 500px; /* Set small width */
        height: auto; /* Maintain aspect ratio */
        border-radius: 10px; /* Optional: Rounded corners */
        border: 2px solid #ddd; /* Optional: Border styling */
        padding: 5px; /* Optional: Padding */
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
    <div class="container">
        <div class="title">Reservation Form</div>
        <!-- tinaggal ko na yung sa reservation history -->
        <div class="content">
        <form action="reservation.php" method="POST" enctype="multipart/form-data">
    <div class="user-details">
    <div class="input-box">
            <label for="eventType">Event Type:</label>
            <select id="eventType" name="event_type" required>
                <option value="" disabled selected>Select Event Type</option>
                <option value="Wedding Photobooth service for 3 Hours">Wedding Photobooth service for 3 Hours</option>
                <option value="Wedding Photobooth service for 5 Hours">Wedding Photobooth service for 5 Hours</option>
                <option value="Reunion Photobooth service for 3 Hours">Reunion Photobooth service for 3 Hours</option>
                <option value="Reunion Photobooth service for 4 Hours">Reunion Photobooth service for 4 Hours</option>
                <option value="baptism Photobooth service for 3 Hours">Baptism Photobooth service for 3 Hours</option>
                <option value="baptism Photobooth service for 4 Hours">Baptism Photobooth service for 4 Hours</option>
                <option value="birthday Photobooth service for 3 Hours">Birthday Photobooth service for 3 Hours</option>
                <option value="birthday Photobooth service for 4 Hours">Birthday Photobooth service for 4 Hours</option>
                <option value="company_event Photobooth service for 3 Hours">Company Event Photobooth service for 3 Hours</option>
                <option value="company_event Photobooth service for 5 Hours">Company Event Photobooth service for 5 Hours</option>
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
        <label for="photo-size">Select Photo Size & Layout:</label>
        <select id="photo-size" name="photo_size_layout" required>
            <option value="" disabled selected>Select Photo Size & Layout</option>
            <option value="4x4">4R Size (3 & 4 Grids)</option>
            <option value="6x6">Photo Strip Size (2, 3 & 4 Grids)</option>
        </select>
    </div>
    <div class="input-box">
        <label for="contactNumber">Contact Number:</label>
        <input id="contactNumber" type="number" name="contact_number" placeholder="e.g. 09123456712" required>
    </div>

     <!-- Left Column: Images Container -->
  <div class="images-container">
      <img src="images/4r.png" alt="4R Example">
      <img src="images/strips.png" alt="Strips Example">
  </div>
  
  <!-- Right Column: Time Inputs & Upload Section -->
  <div class="right-section">
      <div class="input-container">
          <div class="input-item">
              <label for="timedatePickerStart">Start Time</label>
              <input type="text" name="timedatePickerStart" id="timedatePickerStart" placeholder="Select start date and time" required>
          </div>
          <div class="input-item">
              <label for="timedatePickerEnd">End Time</label>
              <input type="text" name="timedatePickerEnd" id="timedatePickerEnd" placeholder="Select end date and time">
          </div>
      </div>
  
       <!-- Image Upload Section -->
    <div class="upload-container">
        <h2>Upload Image</h2>
        <p>Assist us in creating temporary custom background for your selected image.</p>
        <div class="form-group">
            <input type="file" name="image" id="imageUpload" accept="image/*" onchange="previewImage(event)" />
        </div>
        <!-- Image Preview -->
        <div class="preview-container">
            <img id="imagePreview" src="" alt="Image Preview" style="display: none; max-width: 100%; height: auto; margin-top: 10px;">
        </div>
    </div>
  </div>

    <div class="parent-container">
        <input type="submit" name="submit" class="btn" value="Submit">
    </div>
</form>
        </div>
    </div>

    <!-- Custom Modal -->
<div id="customModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Success</h2>
        <p>Reservation submitted successfully! Please wait for 1 hour for your approval.</p>
        <button onclick="redirect()">OK</button>
    </div>
</div>

<!-- CSS for Modal -->
<style>
    .modal {
        display: none; /* Default: hidden */
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: white;
        margin: 20% auto;
        padding: 20px;
        width: 300px;
        text-align: center;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        position: relative;
    }

    .close {
        position: absolute;
        right: 15px;
        top: 10px;
        font-size: 20px;
        cursor: pointer;
    }

    button {
        padding: 10px 20px;
        background-color: #28a745;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }

    button:hover {
        background-color: #218838;
    }
</style>

<!-- JavaScript for Modal -->
<script>
    function closeModal() {
        document.getElementById('customModal').style.display = 'none';
    }

    function redirect() {
        window.location.href = 'reservation.php';
    }
</script>


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
    

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
$(document).ready(function () {
    $.ajax({
        url: 'fetch_unavailable_dates.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            console.log("Unavailable Dates:", data); // Debugging

            function initializeFlatpickr(selector) {
                return flatpickr(selector, {
                    enableTime: true,
                    dateFormat: "Y-m-d h:i K",
                    time_24hr: false,
                    disable: data,
                    onReady: function (selectedDates, dateStr, instance) {
                        markUnavailableDates(instance, data);
                    },
                    onMonthChange: function (selectedDates, dateStr, instance) {
                        markUnavailableDates(instance, data);
                    }
                });
            }
            
            initializeFlatpickr("#timedatePickerStart");
            initializeFlatpickr("#timedatePickerEnd");
        },
        error: function (xhr, status, error) {
            console.error("Error fetching unavailable dates: ", error);
        }
    });
});

function markUnavailableDates(instance, unavailableDates) {
    setTimeout(function () {
        instance.calendarContainer.querySelectorAll(".flatpickr-day").forEach(function (dayElem) {
            let dateStr = instance.formatDate(dayElem.dateObj, "Y-m-d");
            if (unavailableDates.includes(dateStr)) {
                dayElem.style.backgroundColor = "red";
                dayElem.style.color = "white";
                dayElem.style.pointerEvents = "none";
            }
        });
    }, 10);
}

let currentIndex = 1;

function showSlides() {
    const slides = document.querySelectorAll(".slide");
    const dots = document.querySelectorAll(".dot");
    slides.forEach(slide => slide.style.display = "none");
    dots.forEach(dot => dot.classList.remove("active"));
    if (currentIndex > slides.length) currentIndex = 1;
    if (currentIndex < 1) currentIndex = slides.length;
    slides[currentIndex - 1].style.display = "block";
    dots[currentIndex - 1].classList.add("active");
    currentIndex++;
    setTimeout(showSlides, 3000);
}

function currentSlide(index) {
    currentIndex = index;
    showSlides();
}

function changeSlide(n) {
    const slides = document.querySelectorAll(".slide");
    currentIndex += n;
    if (currentIndex < 1) currentIndex = slides.length;
    if (currentIndex > slides.length) currentIndex = 1;
    showSlides();
}

document.addEventListener("DOMContentLoaded", () => {
    showSlides();
    fetchNotifications();
});

async function fetchNotifications() {
    try {
        const response = await fetch('fetch_notification.php');
        const notifications = await response.json();
        const dropdown = document.querySelector(".notification-dropdown");
        if (notifications.length > 0) {
            document.querySelector('.notification-count').textContent = notifications.length;
            dropdown.innerHTML = notifications.map(notification => {
                let notificationTime = new Date(notification.time);
                if (isNaN(notificationTime)) {
                    console.error("Invalid date:", notification.time);
                    notificationTime = new Date();
                }
                let formattedTime = notificationTime.toLocaleString('en-US', {
                    weekday: 'short', year: 'numeric', month: 'short', day: 'numeric',
                    hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true
                });
                return `<div class="notification-item">${notification.message} <span class="time">${formattedTime}</span></div>`;
            }).join("");
        } else {
            dropdown.innerHTML = "<p>No new notifications</p>";
        }
    } catch (error) {
        console.error('Error fetching notifications:', error);
        document.querySelector(".notification-dropdown").innerHTML = "<p>Failed to load notifications</p>";
    }
}

document.querySelector(".notification-bell").addEventListener("click", function (event) {
    event.stopPropagation();
    const dropdown = document.querySelector(".notification-dropdown");
    dropdown.classList.toggle("show");
});

document.addEventListener("click", function (event) {
    const dropdown = document.querySelector(".notification-dropdown");
    if (!event.target.closest(".notification-bell")) {
        dropdown.classList.remove("show");
    }
});

document.getElementById("bookingStatusBtn").addEventListener("click", function () {
    fetch("fetch_reservation.php")
        .then(response => response.json())
        .then(data => {
            const bookingDetails = document.getElementById("bookingDetails");
            if (data.error) {
                bookingDetails.innerHTML = `<p>${data.error}</p>`;
            } else {
                bookingDetails.innerHTML = `
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

document.querySelector(".close").addEventListener("click", function () {
    document.getElementById("bookingStatusModal").style.display = "none";
});

window.onclick = function (event) {
    if (event.target == document.getElementById("bookingStatusModal")) {
        document.getElementById("bookingStatusModal").style.display = "none";
    }
};

function previewImage(event) {
    var image = document.getElementById('imagePreview');
    var file = event.target.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function (e) {
            image.src = e.target.result;
            image.style.display = "block";
        };
        reader.readAsDataURL(file);
    } else {
        image.style.display = "none";
    }
}
  // Get the event type dropdown and the 'Others' input box
  const eventTypeSelect = document.getElementById('eventType');
  const otherEventBox = document.getElementById('otherEventBox');

  // Function to toggle visibility of the 'Others' input box based on dropdown selection
  document.getElementById('eventType').addEventListener('change', function() {
    var otherEventBox = document.getElementById('otherEventBox');
    if (this.value === 'others') {
        otherEventBox.style.display = 'block'; // Show the input field
    } else {
        otherEventBox.style.display = 'none'; // Hide the input field
    }
});


    </script>
</body>
</html>
