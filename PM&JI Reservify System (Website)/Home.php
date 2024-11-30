<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PM&JI Reservify</title>
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
            <li><a href="About Us.php">About Us</a></li>
            <li><a href="portfolio.php">Portfolio</a></li>
            <li><a href="Contact us.php">Contact Us</a></li>
            <li><a href="Sign up.php">Log In</a></li>
            <i class="fa fa-user"></i>  
        </ul>
    </nav>

    <div class="container">
        <h1 class="welcome-text">Welcome </h1>
        <img src="images/reservify_logo.png" alt="PM&JI logo" id="logo-pic">
        <h1 class="reservify-text">PM&JI Reservify</h1>
    </div>
    <div>
    <button class="book-now" onclick="redirectToSignup()">Book Now!</button>
</div>

<script>
    function redirectToSignup() {
        window.location.href = 'Sign up.php';
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
    <a href="customer_support.php" class="message-link">
    <div class="message-icon">
        <i class="fa fa-message"></i>
        <span>Connect with Us</span>
    </div>
</a>
    
   
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