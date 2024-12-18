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

// Query to fetch the required data from both test_registration and reservation tables
$sql = "
    SELECT 
        tr.First_Name, 
        tr.Middle_Name, 
        tr.Last_Name, 
        tr.Email, 
        r.reservation_id, 
        r.event_type, 
        r.event_place, 
        r.number_of_participants, 
        r.contact_number, 
        r.date_and_schedule,
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
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    if ($data) {
        // Data fetched successfully
        $first_name = $data['First_Name'];
        $middle_name = $data['Middle_Name'];
        $last_name = $data['Last_Name'];
        $email = $data['Email'];
        $reservation_id = $data['reservation_id'];  // Reservation ID from reservation table
        $event_type = $data['event_type'];
        $event_place = $data['event_place'];
        $number_of_participants = $data['number_of_participants'];
        $contact_number = $data['contact_number'];
        $date_and_schedule = $data['date_and_schedule'];
        $image = $data['image'];
        $payment_method = $data['payment_method'];
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
    // Insert the booking summary into the booking_summary table
    $insert_sql = "
        INSERT INTO booking_summary (user_id, first_name, middle_name, last_name, email, event_type, event_place, 
                                      number_of_participants, contact_number, date_and_schedule, image, payment_method)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";

    $stmt = mysqli_prepare($conn, $insert_sql);
    mysqli_stmt_bind_param($stmt, "issssssissss", $user_id, $first_name, $middle_name, $last_name, $email, $event_type,
                           $event_place, $number_of_participants, $contact_number, $date_and_schedule, $image, $payment_method);

    if (mysqli_stmt_execute($stmt)) {
        // Add JavaScript alert for success
        echo "<script>alert('Your reservation request has been successfully submitted.');</script>";

        // After successful processing, redirect to the thank you page
        header("Location: thankyoupage.php");
        exit();
    } else {
        echo "Error saving reservation: " . mysqli_error($conn);
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
                <label>Number of Participants:</label>
                <input type="text" value="<?php echo htmlspecialchars($number_of_participants); ?>" disabled />
            </div>
            <div class="summary-item">
                <label>Contact Number:</label>
                <input type="text" value="<?php echo htmlspecialchars($contact_number); ?>" disabled />
            </div>
            <div class="summary-item">
                <label>Date and Schedule:</label>
                <input type="text" value="<?php echo htmlspecialchars($date_and_schedule); ?>" disabled />
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
