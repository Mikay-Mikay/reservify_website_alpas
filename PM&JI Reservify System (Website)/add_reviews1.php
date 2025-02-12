<?php
session_start();
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['id'] ?? null;
    $message = trim($_POST['opinion']);
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : null;

    if (!$user_id) {
        die("Error: User not logged in.");
    }

    if ($rating < 1 || $rating > 5) {
        die("Error: Invalid rating value.");
    }

    $sql = "INSERT INTO feedback (user_id, message, rating) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $user_id, $message, $rating);

    if ($stmt->execute()) {
        // Set session variable to trigger pop-up
        $_SESSION['feedbackSubmitted'] = true;
        header("Location: add_reviews1.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Review</title>
    <link rel="stylesheet" href="add_reviews1.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        /* Pop-up Styles */
        .popup {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .popup-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            width: 300px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }
        .close-btn {
            background: #ff5a5f;
            color: white;
            padding: 8px 16px;
            border: none;
            cursor: pointer;
            margin-top: 10px;
            border-radius: 5px;
        }
        .review {
    position: absolute;
    top: 20px;
    right: 20px;
}

.review .clickable-text {
    text-decoration: none;
    color: blue; /* Adjust color as needed */
    font-size: 15px; /* Adjust the font size */
}
    </style>
</head>
<body>

<div class="wrapper">
    <h3>Thank you for taking the time to share your experience.</h3>
    <form action="" method="POST">
    <div class="rating">
    <input type="number" name="rating" id="rating" hidden>
    <i class='bx bx-star star' data-value="1"></i>
    <i class='bx bx-star star' data-value="2"></i>
    <i class='bx bx-star star' data-value="3"></i> 
    <i class='bx bx-star star' data-value="4"></i>
    <i class='bx bx-star star' data-value="5"></i> 
</div>

        <textarea class="textarea" name="opinion" cols="30" rows="5" placeholder="Your Review...."></textarea>
        <div class="btn-group">
            <button type="submit" class="btn submit">Submit</button>
            <button type="button" class="btn cancel" onclick="window.location.href='reservation.php';">Cancel</button>
        </div>
    </form>
</div>

<!-- Pop-up Message -->
<div id="feedbackPopup" class="popup">
    <div class="popup-content">
        <p>Thank you for your honest feedback!</p>
        <button class="close-btn">OK</button>
    </div>
</div>

<div class="review">
        <a href="customer_feedback.php" class="clickable-text">View Feedback</a>
    </div>

<!-- Pop-up Message -->
<div id="feedbackPopup" class="popup">
    <div class="popup-content">
        <p>Thank you for your honest feedback!</p>
        <button class="close-btn" onclick="closePopup()">OK</button>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Check if feedback was submitted
        <?php if (isset($_SESSION['feedbackSubmitted']) && $_SESSION['feedbackSubmitted']): ?>
            document.getElementById("feedbackPopup").style.display = "flex";
            <?php unset($_SESSION['feedbackSubmitted']); ?>  // Clear session variable after displaying pop-up
        <?php endif; ?>

        // Get the close button
        const closeBtn = document.querySelector(".close-btn");

        // Function to close pop-up and redirect
        function closePopup() {
            document.getElementById("feedbackPopup").style.display = "none";
            window.location.href = "customer_feedback.php"; // Redirect to feedback page
        }

        // Ensure close button exists before adding event listener
        if (closeBtn) {
            closeBtn.addEventListener("click", closePopup);
        } else {
            console.error("Close button not found!");
        }
    });
</script>

<script src="add reviews1.js"></script>
<script>
    // Show pop-up if feedback was submitted
    <?php if (isset($_SESSION['feedbackSubmitted']) && $_SESSION['feedbackSubmitted']): ?>
        document.getElementById("feedbackPopup").style.display = "flex";
        <?php unset($_SESSION['feedbackSubmitted']); ?>  // Clear session after showing pop-up
    <?php endif; ?>

    function closePopup() {
        document.getElementById("feedbackPopup").style.display = "none";
    }
</script>

</body>
</html>
