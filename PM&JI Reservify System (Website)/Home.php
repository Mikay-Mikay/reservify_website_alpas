<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PM&JI Reservify - Home</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
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
        <li><a href="Contact us.php">Contact Us</a></li>

        <li class="user-logo">
                <a href="login.php">
                    <img src="images/user_logo.png" alt="User Logo">
                </a>
    </ul>
</nav>

    <div class="container">
        <h1 class="welcome-text">Welcome </h1>
        <img src="images/reservify_logo.png" alt="PM&JI logo" id="logo-pic">
        <h1 class="reservify-text">PM&JI Reservify</h1>
    </div>

    <div class="tag-line">
        <p>At PM&JI Reservify, we don’t just capture moments; we craft timeless memories that you’ll cherish forever. With every click of the camera, we transform fleeting moments into cherished memories that last a lifetime. Let us preserve the essence of your special occasions through the art of photography, capturing not only the images but the emotions, the stories, and the unique moments that define your journey. From the laughter and joy to the quiet, intimate moments, we create lasting impressions that tell your unique story, allowing you to relive those precious memories for years to come.</p>
    
<style>

.tag-line {
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: justify;
    padding-top: 30px;
}

.tag-line p {
    max-width: 800px; /* Optional: Sets a maximum width to prevent text from stretching too much */
    width: 100%;
    margin: 0;
    font-style: italic;
}

</style>

        </div>
    <div>
    <button class="book-now" onclick="redirectToSignup()">Book Now!</button>
</div>

<script>
    function redirectToSignup() {
        window.location.href = 'login.php';
    }
</script>
    <div class="img-container">
        <div class="box">
            <img src="images/pic1.jpg" alt="Image 1">
        </div>
        <div class="box">
            <img src="images/pic2.jpg" alt="Image 2">
        </div>
        <div class="box">
            <img src="images/pic3.jpg" alt="Image 3">
        </div>
        <div class="box">
            <img src="images/pic4.jpg" alt="Image 4">
        </div>
    </div>
    <div class="img-container">
        <div class="box">
            <img src="images/pic5.jpg" alt="Image 5">
        </div>
        <div class="box">
            <img src="images/pic6.jpg" alt="Image 6">
        </div>
        <div class="box">
            <img src="images/pic7.jpg" alt="Image 7">
        </div>
        <div class="box">
            <img src="images/pic8.jpg" alt="Image 8">
        </div>
    </div>
    <a href="connect_with_us.php" class="message-link">
    <div class="message-icon">
        <i class="fa fa-message"></i>
        <span>Connect with Us</span>

        <style>
    .box:hover {
        transform: none;  /* Ensure no conflicting styles */
    }
</style>

    </div>
</a>

<footer>
    <p>&copy; 2025 PM&JI Reservify. All Rights Reserved.</p>
</footer>

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
</body>
</html>
