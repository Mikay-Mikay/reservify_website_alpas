<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Required</title>
        <link rel="stylesheet" href="error.css">
    </head>
    <body>
        <div class="error-container">
            <h2>You must be logged in to make a reservation.</h2>
            <button onclick="redirectToAbout()">OK</button>
        </div>

        <script>
            function redirectToAbout() {
                window.location.href = "About Us.php"; 
            }
        </script>
    </body>
    </html>
    <?php
    exit();
}
?>
