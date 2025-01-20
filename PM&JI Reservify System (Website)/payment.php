<?php
// Start the session to track the user's information
session_start();

// Initialize the variables
$errors = array();

// Check if the submit button was clicked
if (isset($_POST["submit"])) {
    // Get the inputs from the form
    $payment_method = $_POST["paymentType"] ?? ''; // The selected payment method by the user
    $Amount = $_POST["amount"] ?? ''; // The selected payment amount by the user
    $ref_no = $_POST["reference"] ?? '';
    $Payment_type = $_POST["paymentclass"] ?? '';
    $reservation_id = $_SESSION["reservation_id"] ?? null; // Get the reservation_id from the session

    // Validate the form inputs
    if (empty($payment_method)) {
        array_push($errors, "Please select a payment method.");
    }
    if (empty($reservation_id)) {
        array_push($errors, "No reservation ID found. Please log in again or contact support.");
    }

    // Handle file upload
    $file_name = '';
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
        }

        // SQL query to insert payment details (payment_method and reservation_id) into the payment table
        $sql = "
            INSERT INTO payment (reservation_id, payment_method, Amount, ref_no, Payment_type, image) 
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
                echo "<script>alert('Payment details saved successfully.');</script>";
                echo "<script>window.location.href='booking summary.php';</script>";
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
    <link rel="stylesheet" href="payment.css?v=1.2">
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
        <form action="payment.php" method="POST" enctype="multipart/form-data">
            <div class="user-details">
                <!-- Payment Method -->
                <div class="input-box">
                <label for="paymentType" class="form-label">Payment Method:</label>
                <select id="paymentType" name="paymentType" class="form-input" required>
            <option value="" disabled selected>Select Payment Method:</option>
        </select>
        </div>


                <!-- Reference Number -->
                <div class="input-box">
                    <label for="reference" class="form-label">Reference Number:</label>
                    <input type="text" id="reference" name="reference" class="form-input" placeholder="Enter Reference Number" required>
                </div>

                <!-- Amount to Pay -->
                <div class="input-box">
                    <label for="amount" class="form-label">Amount to Pay:</label>
                    <input type="number" id="amount" name="amount" class="form-input" placeholder="Enter Amount" required>
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

             <!-- Upload Image Section -->
             <div class="upload-container">
                <h2>Upload Payment Proof</h2>
                <p>Attach proof of payment below:</p>
                <input type="file" name="image" class="upload-input" required>
            </div>


            <!-- Submit Button -->
            <div class="form-actions">
                <button type="submit" name="submit" class="btn">Submit Payment</button>
            </div>

        
        </form>
    </div>
</div>
        <div class="prices">
        <p><strong>Here are the 50% down payment amounts for each event based on the original prices:</strong>
                <br>Wedding: Original Price: ₱25,000. 50% Down Payment: ₱12,500<br>Reunion: Original Price: ₱20,000. 50% Down Payment: ₱10,000<br>Baptism: Original Price: ₱18,000. 50% Down Payment: ₱9,000
                <br>Birthday: Original Price: ₱17,500. 50% Down Payment: ₱8,750<br>Company Event: Original Price: ₱30,000. 50% Down Payment: ₱15,000.
            </p>
        </div>

          <!--For payment option images-->
        <div class="payment-type">
            <img src="images/Gcash.jpg" alt="Gcash" class="zoomable">
            <img src="images/Maya.jpg" alt="Maya" class="zoomable">
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
  
       
    <a href="customer_support.php" class="message-link">
    <div class="message-icon">
        <i class="fa fa-message"></i>
        <span>Connect with Us</span>
    </div>
</a>
    
</body>
</html>
