<?php
// Start the session to track the user's information
session_start();

// Initialize the variables
$errors = array();

// Check if the submit button was clicked
if (isset($_POST["submit"])) {
    // Get the inputs from the form
    $payment_method = $_POST["paymentType"] ?? ''; // The selected payment method by the user
    $reservation_id = $_SESSION["reservation_id"] ?? null; // Get the reservation_id from the session

    // Validation checks
    if (empty($payment_method)) {
        array_push($errors, "Please select a payment method.");
    }
    if (empty($reservation_id)) {
        array_push($errors, "No reservation ID found. Please log in again or contact support.");
    }

    // Display errors or process the form
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            // JavaScript alert for error messages
            echo "<script>alert('Error: $error');</script>";
        }
    } else {
        // Database connection
        require_once "database.php";

        if (!$conn) {
            // JavaScript alert for database connection error
            echo "<script>alert('Error connecting to the database: " . mysqli_connect_error() . "');</script>";
        }

        // SQL query to insert payment details (payment_method and reservation_id) into the payment table
        $sql = "
            INSERT INTO payment (reservation_id, payment_method) 
            VALUES (?, ?)";


        // Prepare and execute the statement
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            // JavaScript alert for query preparation error
            echo "<script>alert('Database error: " . mysqli_error($conn) . "');</script>";
        } else {
            // Bind the parameters to the query
            mysqli_stmt_bind_param($stmt, "is", $reservation_id, $payment_method);

           // Execute the query
            if (mysqli_stmt_execute($stmt)) {
                // JavaScript alert for success
                echo "<script>alert('Payment details saved successfully.');</script>";

                // Redirect to booking_summary.php
                echo "<script>window.location.href='booking summary.php';</script>";
                exit;
            } else {
                // Handle query execution failure
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

        </ul>
    </nav>


<!--For payment process-->
    <div class="container">
        <div class="title">Payment</div>
        <div class="content">
            <form action="payment.php" method="POST">
                <div class="user-details">
                    <div class="input-box">
                        <label for="paymentType">Select Payment:</label>
                        <select id="paymentType" name ="paymentType" required> <!-- Changed the id here to paymentType -->
                            <option value="" disabled selected>Select Payment Type</option>
                        </select>
                    </div>
                </div>
                
                <!-- Button container with added margin-top -->
                <div class="parent-container" style="margin-top: 15px;">
                    <button type="submit" name="submit" class="btn">Next</button>
                </div>
            </form>
        </div>
        <div class="prices">
        <p><strong>Here are the 50% down payment amounts for each event based on the original prices:</strong>
                <br>Wedding: Original Price: ₱25,000. 50% Down Payment: ₱12,500<br>Reunion: Original Price: ₱20,000. 50% Down Payment: ₱10,000<br>Baptism: Original Price: ₱18,000. 50% Down Payment: ₱9,000
                <br>Birthday: Original Price: ₱17,500. 50% Down Payment: ₱8,750<br>Company Event: Original Price: ₱30,000. 50% Down Payment: ₱15,000.
            </p>

            <style>
        .prices {
            text-align: center !important;
            font-style: italic !important;
            margin-top: 20px;
            font-family: "Poppins", sans-serif;
            display: flex;
            flex-wrap: wrap; /* allows items to wrap to the next line if necessary */
            justify-content: center; /* centers the content horizontally */
        }

        .prices p {
            display: inline; /* Makes the paragraph inline */
            margin-right: 20px; /* Space between items */
            white-space: nowrap; /* Prevents text from wrapping */
        }
        </style>

        </div>

          <!--For payment option images-->
        <div class="payment-type">
            <img src="images/Gcash.jpg" alt="Gcash" class="zoomable">
            <img src="images/Maya.jpg" alt="Maya" class="zoomable">

        <style>
            .payment-type {
    max-width: 100%;
    display: flex;
    justify-content: flex-start;
    align-items: center;
    gap: 20px;
    padding-left: 20px;
    flex-wrap: wrap;
    margin-top: 20px;
}

.payment-type img {
    max-width: 100%;
    height: auto ;
    max-width: 200px;
    transition: transform 0.3s ease-in-out; /* Smooth zoom transition */
    cursor: pointer; /* Pointer cursor for clickable images */
}

.payment-type img.zoomed {
    transform: scale(1.5); /* Zoom in effect */
    z-index: 10; /* Ensure it appears above other elements */
    position: relative;
}
.zoomable {
    transition: transform 0.3s ease-in-out; /* Smooth zoom transition */
    cursor: pointer; /* Pointer cursor to indicate clickable images */
}

.zoomable.zoomed {
    transform: scale(1.5); /* Zoom effect */
    z-index: 10; /* Ensure it appears above other elements */
    position: relative;
}
.payment-type img {
    max-width: 100%; /* Para mag-adjust ang laki depende sa parent container */
    height: auto;
    max-width: 200px; /* Pinakamalaking laki ng bawat larawan */
}
.upload-container1 h2{
    text-align: center;
   
    
}

/* Media queries para sa responsive design ng payment images */
@media (max-width: 768px) {
    .payment-type img {
        max-width: 150px; /* Mas maliit na laki ng larawan para sa tablet */
    }
}

@media (max-width: 480px) {
    .payment-type {
        justify-content: center; /* Sentro sa mas maliit na screen */
    }

    .payment-type img {
        max-width: 120px; /* Mas maliit na laki ng larawan para sa mobile */
    }
}


        </style>
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
    
  
       
    <a href="customer_support.php" class="message-link">
    <div class="message-icon">
        <i class="fa fa-message"></i>
        <span>Connect with Us</span>
    </div>
</a>
    
</body>
</html>