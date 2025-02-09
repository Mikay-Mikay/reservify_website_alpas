<?php
// Start the session to retrieve user information
session_start();

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    die("You must be logged in to view the booking summary.");
}

// Database connection
require_once "database.php";

// Retrieve the user ID from the session
$user_id = $_SESSION['id'];

// Query to fetch the required data from test_registration, reservation, and payment tables
$sql = "
    SELECT 
        tr.First_Name, 
        tr.Middle_Name, 
        tr.Last_Name, 
        tr.Email, 
        r.reservation_id, 
        r.event_type, 
        r.event_place, 
        r.photo_size_layout, 
        r.contact_number, 
        r.start_time,
        r.end_time,
        r.image,
        p.payment_method
    FROM 
        test_registration tr
    LEFT JOIN 
        reservation r ON tr.id = r.user_id
    LEFT JOIN 
        payment p ON r.reservation_id = p.reservation_id
    WHERE 
        tr.id = ?
";

// Prepare and execute the query
$stmt = mysqli_prepare($conn, $sql);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    if ($data) {
        // Assign fetched data to variables
        $first_name = $data['First_Name'];
        $middle_name = $data['Middle_Name'];
        $last_name = $data['Last_Name'];
        $email = $data['Email'];
        $reservation_id = $data['reservation_id'];
        $event_type = $data['event_type'];
        $event_place = $data['event_place'];
        $photo_size_layout = $data['photo_size_layout'];
        $contact_number = $data['contact_number'];
        $start_time = $data["start_time"];
        $end_time = $data["end_time"];
        $image = $data['image'];
        $payment_method = !empty($data['payment_method']) ? $data['payment_method'] : 'Not Specified';
    } else {
        echo "No booking summary available.";
        exit();
    }
} else {
    echo "Database query error: " . mysqli_error($conn);
    exit();
}

// Check if form is submitted and insert data into the booking_summary table
if (isset($_POST['submit'])) {
    // Convert start_time and end_time to correct format if needed
    if (!empty($start_time)) {
        $start_time = date('Y-m-d H:i:s', strtotime($start_time));
    }
    if (!empty($end_time)) {
        $end_time = date('Y-m-d H:i:s', strtotime($end_time));
    }

    // Insert booking summary
    $insert_sql = "
        INSERT INTO booking_summary 
        (user_id, first_name, middle_name, last_name, email, event_type, event_place, 
        photo_size_layout, contact_number, start_time, end_time, image, payment_method, reservation_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";
    
    $stmt = mysqli_prepare($conn, $insert_sql);
    if ($stmt) {
        // Bind parameters (fixing the incorrect type string)
        mysqli_stmt_bind_param($stmt, "issssssssssssi", 
            $user_id, $first_name, $middle_name, $last_name, $email, 
            $event_type, $event_place, $photo_size_layout, $contact_number, 
            $start_time, $end_time, $image, $payment_method, $reservation_id
        );

        if (mysqli_stmt_execute($stmt)) {
            // Success message and redirection
            echo "<script>alert('Your reservation request has been successfully submitted.');</script>";
            header("Location: thankyoupage.php");
            exit();
        } else {
            echo "Error saving reservation: " . mysqli_error($conn);
        }
    } else {
        echo "Error preparing booking summary SQL: " . mysqli_error($conn);
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PM&JI Reservify - Booking Summary</title>
    <link rel="stylesheet" href="Booking summary.css">
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
        <li><a href="Home.php">Home</a></li>
        <li><a href="About Us.php">About Us</a></li>
        <li><a href="portfolio.php">Portfolio</a></li>
        <li><a href="Contact us.php">Contact Us</a></li>
        <li><a href="login.php">Log In</a></li>
        <li class="user-logo">
            <img src="images/user_logo.png" alt="User Logo">
        </li> 
    </ul>
</nav>

<main>
    <div class="summary-box">
        <h2>Booking Summary</h2>
        <form method="POST" action="">
            <div class="summary-item">
                <label>Name:</label>
                <input type="text" value="<?php echo htmlspecialchars($first_name . ' ' . $middle_name . ' ' . $last_name); ?>" disabled />
            </div>
            <div class="summary-item">
                <label>Email:</label>
                <input type="text" value="<?php echo htmlspecialchars($email); ?>" disabled />
            </div>
            <div class="summary-item">
                <label>Event Type:</label>
                <input type="text" value="<?php echo htmlspecialchars($event_type); ?>" disabled />
            </div>
            <div class="summary-item">
                <label>Event Place:</label>
                <input type="text" value="<?php echo htmlspecialchars($event_place); ?>" disabled />
            </div>
            <div class="summary-item">
                <label>Photo Size and Layout:</label>
                <input type="text" value="<?php echo htmlspecialchars($photo_size_layout); ?>" disabled />
            </div>
            <div class="summary-item">
                <label>Contact Number:</label>
                <input type="text" value="<?php echo htmlspecialchars($contact_number); ?>" disabled />
            </div>
            <div class="summary-item">
                <label>Start Time:</label>
                <input type="text" value="<?php echo htmlspecialchars($start_time); ?>" disabled />
            </div>
            <div class="summary-item">
                <label>End Time:</label>
                <input type="text" value="<?php echo htmlspecialchars($end_time); ?>" disabled />
            </div>
            <div class="summary-item">
                <label>Image:</label>
                <?php 
                if (!empty($image)) { ?>
                    <img src="Images/<?php echo htmlspecialchars($image); ?>" alt="Uploaded Image" style="max-width: 100px; max-height: 100px;">
                <?php } else { ?>
                    <p>No image uploaded</p>
                <?php } ?>
            </div>
            <div class="summary-item">
                <label>Mode of Payment:</label>
                <input type="text" value="<?php echo htmlspecialchars($payment_method); ?>" disabled />
            </div>
            <div class="buttons">
                <button type="submit" class="confirm-btn" name="submit">Confirm and Submit</button>
                <button type="button" class="cancel-btn">Cancel</button>  
            </div>
        </form>
    </div>
</main>
</body>
</html>
