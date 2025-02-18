<?php
// Start the session to track the user's information
session_start();

// Initialize the variables
$errors = array();

if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    echo "<script>alert('Error: You must be logged in to make a payment.');</script>";
    exit;
}

$user_id = $_SESSION['id']; // Siguraduhin na may laman ang user_id

// Check if the submit button was clicked
if (isset($_POST["submit"])) {
    // Get the inputs from the form
    $payment_method = $_POST["paymentType"] ?? ''; // The selected payment method by the user
    $Amount = $_POST["amount"] ?? ''; // The selected payment amount by the user
    $ref_no = $_POST["reference"] ?? ''; // Reference number
    $Payment_type = $_POST["paymentclass"] ?? ''; // Payment type
    $reservation_id = $_SESSION["reservation_id"] ?? null; // Get the reservation_id from the session

    // Validate the form inputs
    if (empty($payment_method)) {
        $errors[] = "Please select a payment method.";
    }
    if (empty($Amount)) {
        $errors[] = "Please enter the payment amount.";
    }
    if (empty($ref_no)) {
        $errors[] = "Reference number is required.";
    }
    if (empty($reservation_id)) {
        $errors[] = "No reservation ID found. Please log in again or contact support.";
    }

    // Handle file upload
    $file_name = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $folder = 'images/' . $file_name; // Ensure the folder name matches your setup

        // Check if the uploaded file is an image
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

        if (!in_array(strtolower($file_extension), $allowed_extensions)) {
            $errors[] = "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
        } elseif (!move_uploaded_file($file_tmp, $folder)) {
            $errors[] = "File upload failed.";
        }
    } else {
        $errors[] = "Please upload an image. Error code: " . $_FILES['image']['error'];
    }

    // Display errors or process the form
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<script>alert('Error: $error');</script>";
        }
    } else {
        // Database connection
        require_once "database.php";

        if (!$conn) {
            echo "<script>alert('Error connecting to the database: " . mysqli_connect_error() . "');</script>";
            exit;
        }

        // SQL query to insert payment details into the payment table
        $sql = "
            INSERT INTO payment (reservation_id, payment_method, Amount, ref_no, Payment_type, payment_image) 
            VALUES (?, ?, ?, ?, ?, ?)";

        // Prepare and execute the statement
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo "<script>alert('Database error: " . mysqli_error($conn) . "');</script>";
        } else {
            // Bind the parameters to the query
            mysqli_stmt_bind_param($stmt, "isssss", $reservation_id, $payment_method, $Amount, $ref_no, $Payment_type, $file_name);

            // Execute the query
            if (mysqli_stmt_execute($stmt)) {
                // Retrieve the last inserted payment ID
                $payment_id = mysqli_insert_id($conn); // Get the auto-incremented ID

                echo "<script>alert('Payment details saved successfully.');</script>";

                // Create a notification for the admin
                $notification_message = "A new payment has been made. Payment ID: $payment_id,  Amount: $Amount, Reference No: $ref_no, Payment Method: $payment_method, Payment Type: $Payment_type.";

                $notification_sql = "
                    INSERT INTO admin_notifications 
                    (user_id, payment_id, Amount, ref_no, payment_method, payment_image, payment_type, payment_received_at, message) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, ?)";

                $notification_stmt = mysqli_stmt_init($conn);

                if (mysqli_stmt_prepare($notification_stmt, $notification_sql)) {
                    // Bind the payment ID and other details for admin notifications
                    mysqli_stmt_bind_param($notification_stmt, "iissssss", $user_id, $payment_id,  $Amount, $ref_no, $payment_method, $file_name, $Payment_type, $notification_message);

                    if (!mysqli_stmt_execute($notification_stmt)) {
                        echo "<script>alert('Failed to create admin notification.');</script>";
                    }
                } else {
                    echo "<script>alert('Database error: Unable to prepare admin notification query.');</script>";
                }

                mysqli_stmt_close($notification_stmt);

                echo "<script>window.location.href='payment.php';</script>";
                exit;
            } else {
                echo "<script>alert('Failed to save payment details. Please try again.');</script>";
            }

            // Close the statement
            mysqli_stmt_close($stmt);
        }

        // Close the database connection
        if (isset($conn)) {
            mysqli_close($conn);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PM&JI Reservify</title>
    <link rel="stylesheet" href="payment.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <link rel="stylesheet" href="jquery.datetimepicker.min.css">
    <script src="payment.js"></script>
</head>
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

            <li><a href="About Us.php">About Us</a></li>
            <li><a href="reservation.php">Reserve Now</a></li>
            <li><a href="customer_mybookings.php">My Bookings</a></li>
            <li><a href="contact_us1.php">Contact Us</a></li>
            <li class="user-logo">
                <a href="profile_user.php">
                    <img src="images/user_logo.png" alt="User Logo">
                </a>
                <li>
                <div class="notification-bell">
                    <img src="images/notif_bell.png.png" alt="Notification Bell" id="notif-bell" onclick="toggleNotification()">
                    <span class="notification-count"></span>
                </div>
                <div class="notification-dropdown">
                    <p>Loading notifications...</p>
                </div>
            </li>
            </li>
        </ul>
    </nav>

<!--For payment process-->
<div class="container">
  <div class="payment-wrapper">
    <!-- Left: Payment Form -->
    <div class="payment-form">
      <div class="title">Payment</div>
      <div class="content">
        <form action="payment.php" method="POST" enctype="multipart/form-data">
          <div class="user-details">
            <!-- Payment Method -->
            <div class="input-box">
              <label for="paymentType" class="form-label">Payment Method:</label>
              <select id="paymentType" name="paymentType" class="form-input" required>
                <option value="" disabled selected>Select Payment Method:</option>
              </select>
            </div>

            <!-- Amount to Pay -->
            <div class="input-box">
              <label for="amount" class="form-label">Amount to Pay:</label>
              <input type="number" id="amount" name="amount" class="form-input" placeholder="Enter Amount" required>
            </div>

            <!-- Reference Number -->
            <div class="input-box">
              <label for="reference" class="form-label">Reference Number:</label>
              <input type="text" id="reference" name="reference" class="form-input" placeholder="Enter Reference Number" required>
            </div>

            <!-- Payment Type -->
            <div class="input-box">
              <label for="paymentclass" class="form-label">Payment Type:</label>
              <select id="paymentclass" name="paymentclass" class="form-input" required>
                <option value="" disabled selected>Select Payment Type</option>
                <option value="Downpayment">Downpayment</option>
                <option value="Full Payment">Full Payment</option>
              </select>
            </div>
          </div>

          <!-- Gcash and Maya Instruction Modules -->
          <div class="tutorial-options">
            <a href="gcash.html" class="button">
              How to Send Using Gcash
              <img src="images/gcash_logo.png" alt="Gcash Logo" class="button-icon">
            </a>
            <a href="maya.html" class="button">
              How to Send Using Maya
              <img src="images/maya_logo.png.png" alt="Maya Logo" class="button-icon">
            </a>
            <a href="payment-rates.php" class="payment-btn">
              Payment Rates
            </a>
          </div>

          <!-- Upload Image Section -->
          <div class="upload-container">
            <h2>Upload Payment Proof</h2>
            <p>Attach proof of payment below:</p>
            <input type="file" id="imageUpload" name="image" class="upload-input" required onchange="previewImage(event)">
          </div>

          <!-- Image Preview -->
          <div class="preview-container">
            <img id="imagePreview" src="" alt="Image Preview" style="display: none; max-width: 100%; height: auto; margin-top: 10px;">
          </div>

          <!-- Submit Button -->
          <div class="form-actions">
            <button type="submit" name="submit" class="btn">Submit Payment</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Right: Scan Me Section -->
    <div class="payment-scan">
      <h2>Scan me!</h2>
      <div class="payment-type">
        <img src="images/Gcash.jpg" alt="Gcash" class="zoomable">
        <img src="images/Maya.jpg" alt="Maya" class="zoomable">
      </div>
    </div>
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
        });
    </script>

<script>
    document.querySelectorAll('.zoomable').forEach((img) => {
        console.log('Detected image:', img);
        img.addEventListener('click', () => {
            console.log('Image clicked:', img);
            img.classList.toggle('zoomed');
        });
    });
    function previewImage(event) {
    var image = document.getElementById('imagePreview');
    var file = event.target.files[0];

    if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            image.src = e.target.result;
            image.style.display = "block"; // Ipakita ang preview
        };
        reader.readAsDataURL(file);
    } else {
        image.style.display = "none"; // Itago ang preview kung walang image
    }
}

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

    <div class="title">
        <h2>Our Work</h2>
    

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
  </div>  
  
       
    <a href="connect_with_us.php" class="message-link">
        <div class="message-icon">
            <i class="fa fa-message"></i>
            <span>Connect with Us</span>
        </div>
    </a>  
    
</body>
</html>
