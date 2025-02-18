<?php
// Start the session to track user information
session_start();

require_once "database.php";

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch feedback details along with user information
$sql = "SELECT f.message, f.rating, tr.First_name, tr.Middle_name, tr.Last_name, tr.Email 
        FROM feedback f
        JOIN test_registration tr ON f.user_id = tr.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedbacks</title>
    <link rel="stylesheet" href="customer_feedback.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>

<button class="back-button" onclick="window.history.back()">Back</button>
    <!-- Feedback Section -->
    <section id="testimonials">

        <!-- Heading -->
        <div class="testimonial-heading">
            <span>Comments</span>
            <h1>Customer says</h1>
        </div>

        <!-- Testimonial Box Container -->
        <div class="testimonial-box-container">

            <?php
            // Check if there are feedback results
            if ($result->num_rows > 0) {
                // Output feedback data
                while($row = $result->fetch_assoc()) {
                    // Get user details and feedback message
                    $name = $row['First_name'] . ' ' . $row['Middle_name'] . ' ' . $row['Last_name'];
                    $email = $row['Email'];
                    $message = $row['message'];
                    $rating = $row['rating'];

                    // Mask email: keep the first 4 characters and the domain part
                    $localPart = substr($email, 0, 4); // First 4 characters
                    $domain = substr(strrchr($email, "@"), 0); // Domain part (e.g., @gmail.com)
                    $maskedEmail = $localPart . str_repeat('*', strlen(substr($email, 4, strpos($email, '@') - 4))) . $domain;

                    // Display testimonial box
                    echo "
                    <div class='testimonial-box'>
                        <div class='box-top'>
                            <div class='profile'>
                                <!-- Profile Image -->
                                <div class='profile-img'>
                                    <img src='images/profile_user_icon.png' alt='Profile Image' />
                                </div>
                                <!-- Name and Username -->
                                <div class='name-user'>
                                    <strong>$name</strong>
                                    <span>@$maskedEmail</span> <!-- Display masked email -->
                                </div>
                            </div>
                            <!-- Reviews (Stars) -->
                            <div class='reviews'>";

                    // Display stars based on rating
                    for ($i = 0; $i < 5; $i++) {
                        if ($i < $rating) {
                            echo "<i class='fas fa-star'></i>";
                        } else {
                            echo "<i class='far fa-star'></i>";
                        }
                    }

                    echo "</div>
                        </div>
                        <!-- Comments -->
                        <div class='client-comment'>
                            <p>$message</p>
                        </div>
                    </div>";
                }
            } else {
                echo "<p>No feedback available.</p>";
            }

            // Close the database connection
            $conn->close();
            ?>

        </div>

    </section>
    <div class="centered-link">
        <a href="add_reviews1.php">Add feedback</a>
    </div>
</body>

</html>
