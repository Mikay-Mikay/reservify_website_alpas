<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'database.php'; // Include the database connection

    // Sanitize and validate user inputs
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $contact = htmlspecialchars(trim($_POST['contact']));
    $concern = htmlspecialchars(trim($_POST['concern']));
    $other_concern = !empty($_POST['other_concern']) ? htmlspecialchars(trim($_POST['other_concern'])) : '';
    $concern_details = htmlspecialchars(trim($_POST['concern_details']));

    // Generate ticket number
    $date = date("Ymd");
    $prefix = "Ticket CS-$date-";

    // Insert data into the database
    $insert_query = "INSERT INTO customer_service 
                     (first_name, last_name, email, contact, concern, other_concern, concern_details, created_at) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";  

    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, "sssssss", $first_name, $last_name, $email, $contact, $concern, $other_concern, $concern_details);
    
    if (mysqli_stmt_execute($stmt)) {
        $last_id = mysqli_insert_id($conn); 
        $ticket_number = $prefix . str_pad($last_id, 4, '0', STR_PAD_LEFT);

        $update_query = "UPDATE customer_service SET ticket_number = ? WHERE id = ?";
        $update_stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($update_stmt, "si", $ticket_number, $last_id);
        mysqli_stmt_execute($update_stmt);
        mysqli_stmt_close($update_stmt);

        echo "<script>
                alert('Thank you for your submission! Your inquiry Ticket \"$ticket_number\" has been sent.');
                window.location.href = 'connect_with_us.php';
              </script>";
    } else {
        echo "<script>
                alert('An error occurred: " . mysqli_error($conn) . "');
              </script>";
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
    <title>PM&JI Reservify - Connect with Us!</title>
    <link rel="stylesheet" href="customer_support.css?v=1.1">
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
            <li><a href="Contact us.php">Contact Us</a></li>
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

    <input type="text" name="concern_details" placeholder="Concern Details" required> 
    <input type="text" name="first_name" placeholder="First Name" required>
    <input type="text" name="last_name" placeholder="Last Name" required>
    <input type="text" name="email" placeholder="Email" required> <!-- FIXED -->
    <input type="text" name="contact" placeholder="Contact Number" required>
    
    <button type="submit">Submit</button>
</form>

        <p class="disclaimer">PM&JI Photography is governed by R.A. 10172, otherwise known as the Data Privacy Act of 2021. By clicking 'SUBMIT,' you signify your consent to PM&JI Photography to collect and process the personal information that you entered.</p>
    </div>

    <footer>
        <p>&copy; 2025 PM&JI Reservify. All Rights Reserved.</p>
    </footer>

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


