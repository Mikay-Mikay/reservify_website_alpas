<?php
// Start the session to track user information
session_start();

// Initialize variables
$errors = array();

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
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
        array_push($errors, "All fields are required.");
    }

    // Check if the file is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $folder = 'Images/' . $file_name;

        // Move the uploaded image to the designated folder
        if (!move_uploaded_file($file_tmp, $folder)) {
            array_push($errors, "File upload failed.");
        }
    } else {
        array_push($errors, "Please upload an image.");
    }

    // Display errors or process the form
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {
        // Database connection
        require_once "database.php";

        // SQL query to insert reservation and image file name
        $sql = "INSERT INTO reservation 
                (user_id, event_type, event_place, number_of_participants, contact_number, date_and_schedule, image) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        // Prepare and execute the statement
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            die("Database error: " . mysqli_error($conn));
        }

        // Bind the parameters to the prepared statement
        mysqli_stmt_bind_param($stmt, "sssssss", $user_id, $event_type, $event_place, $number_of_participants, $contact_number, $date_and_schedule, $file_name);

        if (mysqli_stmt_execute($stmt)) {
            // Get the last inserted ID (reservation_id)
            $reservation_id = mysqli_insert_id($conn);
            $_SESSION['reservation_id'] = $reservation_id;  // Store in session for future use

            echo "<script>
                alert('Reservation submitted successfully! Please wait for your approval.');
                window.location.href = 'reservation.php';
                </script>";
        } else {
            die("Database error: Unable to execute query.");
        }

        // Close the statement and connection
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
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <link rel="stylesheet" href="jquery.datetimepicker.min.css">
    <script src="reservation.js"></script>
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

            <i class="fa fa-bell notification-bell"></i>
                <div class="notification-dropdown">
                     <p>No new notifications</p>    
                     <p>Meow</p>   
                </div>
    </nav>

    <div class="container">
        <div class="title">Registration</div>
        <div class="content">
            <form action="reservation.php" method="POST" enctype="multipart/form-data">
                <div class="user-details">
                    <!-- Dropdown for event type -->
                    <div class="input-box">
                        <label for="eventType">Event Type:</label>
                        <select id="eventType" name="event_type" required>
                            <option value="" disabled selected>Select Event Type</option>
                            <!-- Add other options here -->
                        </select>
                    </div>

                    <!-- Hidden textarea that will show when 'Others' is selected -->
                    <div class="input-box" id="otherEventBox" style="display:none;">
                        <label for="otherEvent">Please specify:</label>
                        <textarea id="otherEvent" name="other_event" placeholder="Describe your event needs..." rows="4"></textarea>
                    </div>
                    
                    <!-- Input box for event place -->
                    <div class="input-box">
                        <label for="eventPlace">Event Place:</label>
                        <input id="eventPlace" type="text" name="event_place" placeholder="Enter Event Place" required>
                    </div>

                    <!-- Input for Number of Participants -->
                    <div class="input-box">
                        <label for="participants">Number of Participants:</label>
                        <input id="participants" type="number" name="number_of_participants" min="1" max="120" step="1" placeholder="Enter Number of Participants" required>
                    </div>

                    <!-- Input for Contact Number-->
                    <div class="input-box">
                        <label for="contactNumber">Contact Number:</label>
                        <input id="contactNumber" type="text" name="contact_number" placeholder="e.g. 09123456712" required>
                    </div>

                    <!-- Input for Date and Schedule -->
                    <div class="timepicker">
                        <label for="timedatePicker">Date and Schedule:</label>
                        <input type="text" id="timedatePicker" name="dob" placeholder="Select Date and Time" required>
                    </div>
                    
                     <!-- Date Picker Script -->
              <script src="jquery.js"></script>
              <script src="jquery.datetimepicker.full.min.js"></script>
              <script>
                $("#timedatePicker").datetimepicker({
                    step: 15
                });
              </script>
            </div>
            <!-- For uploading image -->
        <div class="upload-container">
            <h2>Upload Image</h2>
            <p>Assist us in creating temporary a custom background for your selected image.</p>
            <div class="form-group">
                <input type="file" name="image" />
            </div>
        </div>

        <style>
            .upload-container {
                display: flex; /* Enables flexbox layout */
                flex-direction: column; /* Stacks the elements vertically */
                align-items: center; /* Centers content horizontally */
                text-align: center; /* Centers the heading text */
                margin-top: 20px; /* Optional space from the top */
            }

            .form-group {
                margin-top: 10px; /* Optional spacing between the heading and input */
            }

            .form-group input[type="file"] {
                margin: 0 auto; /* Centers the file input */
            }

            /* Adding space between the upload section and the submit button */
            .parent-container {
                margin-top: 20px; /* Adds space between the submit button and the upload section */
                text-align: center; /* Centers the button */
            }
        </style>


            <div class="parent-container">
            <input type="submit" name="submit" class="btn" value="Submit">
              </div>
              
          </form>
        </div>
    </div>

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

            // Show/hide the "Other Event" textarea based on selection
            $('#eventType').change(function() {
                if ($(this).val() == 'Others') {
                    $('#otherEventBox').show();
                } else {
                    $('#otherEventBox').hide();
                }
            });
        });
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
            <img src="images/pic12.png" alt="pic12" class="normal">
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
    
    <!-- Dots for navigation -->
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

</body>
</html>
