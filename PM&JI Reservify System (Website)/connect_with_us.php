<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'database.php'; // Include the database connection

    // Sanitize and validate user inputs
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $contact = htmlspecialchars(trim($_POST['contact']));
    $concern = htmlspecialchars(trim($_POST['concern']));

    // If "other_concern" is empty, set it to an empty string to avoid NULL
    $other_concern = !empty($_POST['other_concern']) ? htmlspecialchars(trim($_POST['other_concern'])) : '';

    // Generate ticket number
    $date = date("Ymd"); // Current date in YYYYMMDD format
    $prefix = "Ticket CS-$date-";

    try {
        // Get the current count of tickets for the day to avoid duplicate ticket numbers
        $query = "SELECT COUNT(*) AS ticket_count FROM customer_service WHERE DATE(created_at) = CURDATE()";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Ensure the ticket count starts from 1 and generate ticket number
        $count = $result['ticket_count'] + 1;
        // Generate ticket number with proper padding (leading zeros)
        $ticket_number = $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);

        // Check if the ticket number already exists (optional step to ensure uniqueness)
        $query = "SELECT COUNT(*) AS ticket_count FROM customer_service WHERE ticket_number = :ticket_number";
        $stmt = $conn->prepare($query);
        $stmt->execute([':ticket_number' => $ticket_number]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['ticket_count'] > 0) {
            // Generate a new ticket number if there's a duplicate
            $ticket_number = $prefix . str_pad($result['ticket_count'] + 1, 4, '0', STR_PAD_LEFT);
        }

        // Insert data into the database
        $query = "INSERT INTO customer_service
                  (ticket_number, first_name, last_name, contact, concern, other_concern) 
                  VALUES (:ticket_number, :first_name, :last_name, :contact, :concern, :other_concern)";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            ':ticket_number' => $ticket_number,
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':contact' => $contact,
            ':concern' => $concern,
            ':other_concern' => $other_concern
        ]);

        // Display the ticket number to the user
        echo "<p class='success-message'>Thank you for your submission! Your inquiry Ticket is <strong>$ticket_number</strong> has been sent. Our support team will get back to you shortly.</p>";
    } catch (PDOException $e) {
        // Error handling
        echo "<p class='error-message'>An error occurred: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connect with Us - PM&JI Reservify</title>
    <link rel="stylesheet" href="customer_support.css">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</head>
<body>
    <!-- Navigation Bar -->
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
            <li><a href="Contact us.php">Contact Us</a></li>
            <li><a href="customer_mybookings.php">My Bookings</a></li>
            <li class="user-logo">
                <img src="images/user_logo.png" alt="User Logo">
            </li>
        </ul>
    </nav>

    <!-- Connect with Us Section -->
    <div class="connect-container">
        <a href="javascript:history.back()">
            <img src="images/back button.png" alt="Back Button" class="back-button">
        </a>
        <h1>Connect with us!</h1>
    </div>

    <!-- Chat Form Section -->
    <div class="chat-container">
        <h2>PM&JI Customer Service Online</h2>
        <p>Hey! We see you might have some concerns, and we're here to help. Whether it's about services or something else, feel free to share your thoughts. Your feedback is important, and we will do our best to provide a solution or direct you to the right support!</p>
        <form method="POST" action="">
        <div class="form-group">
                <select name="concern" required>
                    <option value="" disabled selected>Concern</option>
                    <option value="Service Inquiry">Service Inquiry</option>
                    <option value="Feedback">Feedback</option>
                    <option value="Complaint">Complaint</option>
                    <option value="Others">Others</option>
                </select>
                <input type="text" name="other_concern" placeholder="Others (if applicable)" id="otherConcernField" style="display: none;">
            </div>
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="last_name" placeholder="Last Name" required>
            <input type="text" name="contact" placeholder="Contact Number" required>
            <button type="submit">Submit</button>
        </form>
        <p class="disclaimer">PM&JI Photography is governed by R.A. 10172, otherwise known as the Data Privacy Act of 2021. By clicking 'SUBMIT,' you signify your consent to PM&JI Photography to collect and process the personal information that you entered.</p>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        // Show/Hide "Others" input field
        $('select[name="concern"]').on('change', function() {
            if ($(this).val() === 'Others') {
                $('#otherConcernField').show().attr('required', true);
            } else {
                $('#otherConcernField').hide().removeAttr('required');
            }
        });
    </script>
</body>
</html>
