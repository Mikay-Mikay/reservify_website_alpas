<?php
session_start();

// Initialize variables
$errors = [];

// Check if the user is logged in
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    die("You must be logged in to make a reservation.");
}


// Get the user ID from the session
$user_id = $_SESSION['id']; 

// Validate if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    // Retrieve and sanitize form inputs
    $event_type = trim($_POST["event_type"] ?? '');  
    $others = trim($_POST["other_event"] ?? '');
    $event_place = trim($_POST["event_place"] ?? '');
    $photo_size_layout = trim($_POST["photo_size_layout"] ?? '');
    $contact_number = trim($_POST["contact_number"] ?? '');
    $start_time = trim($_POST["selected_datetime"] ?? '');
    $file_name = NULL;



    // Validate required fields (excluding optional image upload)
    if (empty($event_type) || empty($event_place) || empty($photo_size_layout) || empty($contact_number) || empty($start_time)) {
        $errors[] = "All fields are required except the image.";
    }

    // Check if "Others" is selected and "other_event" field is empty
    if ($event_type === 'others' && empty($others)) {
        $errors[] = "Please specify your event details.";
    }

   // Process image upload only if a file is uploaded
if (!empty($_FILES['image']['name'])) {
    $file_name = $_FILES['image']['name'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_size = $_FILES['image']['size'];

    // Set the upload folder path
    $upload_dir = "images/"; // Change folder name to "images"

    // Check if folder exists, if not, create it
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Allowed file extensions
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Validate file extension
    if (!in_array($file_extension, $allowed_extensions)) {
        $errors[] = "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
    }

    // Validate file size (limit: 5MB)
    if ($file_size > 5 * 1024 * 1024) { // 5MB in bytes
        $errors[] = "File size exceeds 5MB limit.";
    }

    // Define target file path
    $target_file = $upload_dir . basename($file_name);

    // Move uploaded file if no errors
    if (empty($errors)) {
        if (!move_uploaded_file($file_tmp, $target_file)) {
            $errors[] = "File upload failed.";
        }
    }
} else {
    $file_name = ""; // If no image is uploaded, set NULL
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
    (user_id, event_type, others, event_place, photo_size_layout, contact_number, start_time, image) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die("Database error: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "isssssss", $user_id, $event_type, $others, $event_place, $photo_size_layout, $contact_number, $start_time, $file_name);

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
        . "Image: " . ($file_name ?? "No image uploaded") . ".";

        if ($event_type === 'others' && !empty($others)) {
            $notification_message .= "\nOther Event Details: $others";
        }

        // Insert notification into admin_notifications table
        $notification_sql = "INSERT INTO admin_notifications 
        (user_id, reservation_id, First_name, Middle_name, Last_Name, Email, event_type, others, event_place, photo_size_layout, contact_number, image, message) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
;

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
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('customModal').style.display = 'block';
            });
        </script>";

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
<title>PM&JI Reservify - Reservation Form</title>

<!-- Custom CSS -->
<link rel="stylesheet" href="reservation.css?v=1.5">

<!-- Font Awesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>

<!-- FullCalendar -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

<!-- Ionicons -->
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>

    <style>
        /* Dropdown Styling for notification */
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

        /* CSS para sa Upload Container */
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
            text-align: right;
        }

                /*CSS design for payment sa notif ito pag nag approve yung admin 
                sa reservation ni customer lalabas sa notif yung pwede na sya mag proceed sa payment*/
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

        /* CSS ito para don sa mga input box sa container for example event type etc Container to hold the inputs in two columns */
        .input-container {
            display: flex; /* Flexbox layout */
            gap: 20px; /* Space between the columns */
            flex-wrap: wrap; /* Ensures it wraps on smaller screens */
            align-items: center; /* Vertically center the items within the container */
            justify-content: center; /* Horizontally center the container */
            width: 100%; /* Ensure full width for the container */
            max-width: 800px; /* Optional: Set a max-width to prevent it from stretching too wide */
            margin: 0 auto; /* Center the container on the page */
            margin-top: 25px;
        }

        /* ito para ito sa upload image yung preview Labels for clarity */
        label {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
            margin-right: 200px;
            display: block;
            color: black;
        }

        .available-slots-header {
            display: flex;
            align-items: center;
            justify-content: flex-start; /* Para simula ng parehong linya */
        }

        #label-1 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
            margin-left: 340px;
            display: block;
            float: left;
            color: black;
        }

        #label-2 {
            font-size: 16px;
            font-weight: 600;
            margin-top: 10px;
            margin-bottom: 10px;
            margin-left: 135px;
            display: block
            color: black;
        }

        .selected-datetime-1 {
            font-size: 16px;
            font-weight: 600;
            color: black;
        }

        .datetime-container {
            display: block;
            align-items: center;
            margin-top: 10px;
        }

        .selected-datetime-wrapper {
            justify-content: center; /* Center horizontally */
            align-items: center;
            margin-right: 655px;
            margin-top: 10px;
            font-size: 16px;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: white; /* Slightly different bg color */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            font-family: "Poppins", sans-serif;
            font-weight: 600;   
            width: 100%; /* Responsive width */
            max-width: 300px; /* Set max width */
            cursor: not-allowed; /* Indicate it's non-editable */
            text-align: center; /* Center the text inside input */
        }

        .available-slots-header {
            display: flex;
            
        }

        .preview-container {
            margin-top: 10px;
            text-align: center;
        }

        #imagePreview {
            display: none; /* Hide by default */
            max-width: 300px; /* Limit max width */
            height: auto;
            margin-top: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

            #imagePreview {
                display: none; /* Hidden by default */
                width: 500px; /* Set small width */
                height: auto; /* Maintain aspect ratio */
                border-radius: 10px; /* Optional: Rounded corners */
                border: 2px solid #ddd; /* Optional: Border styling */
                padding: 5px; /* Optional: Padding */
            }

            /*Design for calendar yung bagong calendar*/
                body {
            font-family: Arial, sans-serif;
            margin: 20px;
            text-align: center;
            background-color: #f4f4f4;
        }

        .available {
            color: green;
            font-weight: bold;
        }

        .fully-booked {
            color: red;
            font-weight: bold;
        }

        #time-slots {
            display: grid;
            grid-template-columns: repeat(1, 1fr); /* 2 columns */
            gap: 15px;
            max-width: 550px;
            margin: 20px 0 20px 20px;
            margin-left: 150px;
            float: left;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            font-family: "Poppins", sans-serif;
            background-color: #f4a36c; /* Light orange background */
            border-radius: 10px;
        }

        .time-slot {
            display: block;
            justify-content: flex-start;
            align-items: center;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: white;
            font-size: 16px;
            font-family: "Poppins", sans-serif;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            font-weight: 600;
        }

        .time-slot input[type="radio"] {
            margin-right: 10px;
        }

        .time-slot span {
            color: green;
            font-weight: bold;
        }

        h3 {
            margin-top: 20px;
            display: block;
            margin-right: 600px;
            margin-bottom: 20px;
        }

        .fc-day.past {
            background-color: #e0e0e0 !important;
            color: gray !important;
            pointer-events: none;
        }

                /* Main container for layout */
        .main-container {
            display: flex;         /* Enable flexbox */
            align-items: flex-start; /* Align items to the top */
            gap: 40px;           /* Space between left and right columns */
            max-width: 1100px;   /* Adjust as needed */
            margin: 20px auto;
        }

        /* Left column (calendar) */
        .left-column {
            flex: 1;               /* Takes up available space */
            max-width: 500px;      /* Limit width */
        }

        /* Calendar styling */
        #calendar {
            background: white;
            padding: 15px;
            padding-left: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Availability text styling */
        .availability {
            margin-top: 15px;
            margin-left: 3px;
            display: block;
            gap: 10px;
        }

        /* Right column (images) */
        .images-container {
            flex: 1;               /* Takes up available space */
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        /* Images inside the container */
        .images-container img {
            width: 300px;          /* Adjust width */
            height: 420px;
            border: 2px solid white;
            box-shadow: 0 5px 9px rgba(0, 0, 0, 0.3);
        }

  </style>
</head>
<body>
    <nav>
        <div class="logo">
            <a href="#" onclick="redirectUser()">
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
                <option value="Wedding Photobooth service for 3 Hours" data-duration="3">Wedding Photobooth  service for 3 hours</option>
                <option value="Wedding Photobooth service for 5 Hours" data-duration="5">Wedding Photobooth service for 5 hours</option>
                <option value="Reunion Photobooth service for 3 Hours" data-duration="3">Reunion Photobooth service 3 hours</option>
                <option value="Reunion Photobooth service for 4 Hours" data-duration="4">Reunion Photobooth service for 4 Hours</option>
                <option value="baptism Photobooth service for 3 Hours" data-duration="3">Baptism Photobooth service for 3 Hours</option>
                <option value="baptism Photobooth service for 4 Hours" data-duration="4">Baptism Photobooth service for 4 Hours</option>
                <option value="birthday Photobooth service for 3 Hours" data-duration="3">Birthday Photobooth service for 3 Hours</option>
                <option value="birthday Photobooth service for 4 Hours" data-duration="4">Birthday Photobooth service for 4 Hours</option>
                <option value="company_event Photobooth service for 3 Hours" data-duration="3">Company Event service for 3 Hours</option>
                <option value="company_event Photobooth service for 5 Hours" data-duration="5">Company Event service for 5 Hours</option>
                <option value="others" data-duration="1">Others</option>
            </select>
        </div>
        <!--Input box for event place-->
        <div class="input-box">
            <label for="eventPlace">Event Place:</label>
            <input id="eventPlace" type="text" name="event_place" placeholder="Enter Event Place" required>
        </div>
        <!--Input box for Select Photo Size & Layout-->
        <div class="input-box">
            <label for="photo-size">Select Photo Size & Layout:</label>
            <select id="photo-size" name="photo_size_layout" required>
                <option value="" disabled selected>Select Photo Size & Layout</option>
                <option value="4x4 4R Size (3 & 4 Grids) ">4R Size (3 & 4 Grids)</option>
                <option value="6x6 Photo Strip Size (2, 3 & 4 Grids)">Photo Strip Size (2, 3 & 4 Grids)</option>
            </select>
        </div>
        <!--Input box for Contact Number-->
        <div class="input-box">
            <label for="contactNumber">Contact Number:</label>
            <input id="contactNumber" type="number" name="contact_number" placeholder="e.g. 09123456712" required>
        </div>
    </div>


        <!-- Main Container -->
    <div class="main-container">
        <!-- Left Column: Calendar -->
        <div class="left-column">
            <div id="calendar"></div>
            <div class="availability">
                <span class="available">&#9679; Available</span> |
                <span class="fully-booked">&#9679; Fully Booked</span>
            </div>
        </div>

        <!-- Right Column: Images Container -->
        <div class="images-container">
            <img src="images/4r.png.png" alt="4R Example">
            <img src="images/strips.png.png" alt="Strips Example">
        </div>
    </div>


        <!--pag clinick yung calendar ito yung lalabas-->
        <h3>Time Slots</h3>
        
        <div id="label-2">
            <h4 id="available-slots-header" style="display: none;">Available Slots For </h4>
                <div class="selected-datetime-1">
        </div>
    </div>


        <div id="label-1">
            <label for="selected-datetime">Selected Date & Time:</label>
                <div class="selected-datetime-1">
        </div>
    </div>

            
    
    <input type="text" id="selected-datetime" class="selected-datetime-wrapper" name="selected_datetime" readonly required>

        
        
            <div id="time-slots">
            <p><em>Select a Date and Event Type to See Available Time Slots.</em></p>
        </div>



        <!--div para sa pag upload ng image-->
        <div class="upload-container">
            <h2>Upload Image</h2>
            <p>Assist us in creating temporary custom background for your selected image.</p>
            <div class="form-group">
                <input type="file" name="image" id="imageUpload" accept="image/*" onchange="previewImage(event)" />
            </div>
            <!--div para sa image prreview -->
            <div class="preview-container">
                <img id="imagePreview" src="" alt="Image Preview" style="display: none; max-width: 100%; height: auto; margin-top: 10px;">
            </div>
        </div>
        <!--Div para sa button-->
        <div class="parent-container">
            <input type="submit" name="submit" class="btn" value="Submit">
        </div>
    </form>

    
        <!-- Custom Modal -->
        <div id="customModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Success</h2>
            <p>Reservation submitted successfully! Please wait for 1 hour for your approval.</p>
            <button onclick="redirect()">OK</button>
        </div>
    </div>

<!-- CSS for Modal success -->
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

<!-- JavaScript for Modal success-->
<script>
    function closeModal() {
        document.getElementById('customModal').style.display = 'none';
    }

    function redirect() {
        window.location.href = 'reservation.php';
    }

</script>

<!--Script ito para sa function ng bagong calendar-->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var today = new Date().toISOString().split('T')[0]; 
    var eventTypeDropdown = document.getElementById("eventType");
    var selectedDuration = 3;

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true,
        dateClick: function(info) {
            if (info.dateStr >= today) {
                console.log("Date selected:", info.dateStr); // Debugging
                loadTimeSlots(info.dateStr, selectedDuration);
            }
        }
    });
    calendar.render();

    if (eventTypeDropdown) {
        eventTypeDropdown.addEventListener("change", function() {
            selectedDuration = parseInt(eventTypeDropdown.options[eventTypeDropdown.selectedIndex].dataset.duration);
            var selectedDate = document.getElementById("available-slots-header").dataset.selectedDate;
            if (selectedDate) {
                loadTimeSlots(selectedDate, selectedDuration);
            }
        });
    }

    document.querySelector("form").addEventListener("submit", function(e) {
        var selectedDateTimeInput = document.getElementById("selected-datetime");
        if (!selectedDateTimeInput.value) {
            alert("Please select a date and time slot before submitting.");
            e.preventDefault();
        }
    });
});

function loadTimeSlots(date, duration) {
    console.log("Loading slots for date:", date, "Duration:", duration); // Debugging

    var timeSlotContainer = document.getElementById('time-slots');
    var slotsHeader = document.getElementById('available-slots-header');
    var selectedDateTimeInput = document.getElementById('selected-datetime');

    slotsHeader.style.display = "block";
    slotsHeader.innerHTML = `Available slots for ${date}`;
    slotsHeader.dataset.selectedDate = date;

    timeSlotContainer.innerHTML = "";

    var startTime = 9;
    var endTime = 17.5;
    var slots = [];

    while (startTime + duration <= endTime) {
        let startFormatted = convertTo12HourFormat(startTime);
        let endFormatted = convertTo12HourFormat(startTime + duration);
        let timeSlotValue = `${date} ${startFormatted} - ${endFormatted}`;
        
        console.log("Generated slot:", timeSlotValue); // Debugging

        let slotElement = document.createElement("div");
        slotElement.classList.add("time-slot");
        slotElement.innerHTML = `<label><input type='radio' name='time_slot' value='${timeSlotValue}'> ${startFormatted} - ${endFormatted} | <span class='available'>Available</span></label>`;
        
        slotElement.querySelector("input").addEventListener("change", function() {
            console.log("Selected time slot:", this.value); // Debugging
            selectedDateTimeInput.value = this.value;
        });
        
        timeSlotContainer.appendChild(slotElement);
        startTime += 1;
    }
}

function convertTo12HourFormat(hour) {
    let period = hour >= 12 ? "PM" : "AM";
    let formattedHour = hour % 12 || 12;
    return `${formattedHour}:00 ${period}`;
}
</script>
      </div>
  

        </div>
    </div>

    <!--Div para sa title na Our work -->
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

    <!--wag mo na muna pansinin ito. function to ng lumang calendar-->
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

/*Function para sa slideshow ng imge*/
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

/*back end function para ma fetch ang reservation at mapunta sa admin*/
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

document.getElementById("imageUpload").addEventListener("change", function (event) {
    const file = event.target.files[0]; 
    const preview = document.getElementById("imagePreview");

    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = "block"; // Show the preview
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = "none"; // Hide preview if no file selected
        preview.src = "";
    }
});

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

function redirectUser() {
        <?php if ($isLoggedIn): ?>
            window.location.href = "AboutUs.php";
        <?php else: ?>
            window.location.href = "Home.php";
        <?php endif; ?>
    }
    </script>
</body>
</html>
