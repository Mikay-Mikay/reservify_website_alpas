<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PM&JI Reservify</title>
    <link rel="stylesheet" href="About Us.css">
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
            <li><a href="login.php">Log In</a></li>
            <li class="user-logo">
                <img src="images/user_logo.png" alt="User Logo">
            </li> 
        </ul>
    </nav>

    <div class="container">
        <img src="images/reservify_logo.png" alt="PM&JI logo" id="logo-pic">
        <h1 class="reservify-text">PM&JI Reservify</h1>
    </div>
    <div class="container1">
        <div class="about-image">
            <img src="images/pic9.png">
        </div>
        <div class="about-content">
            <p>PM&JI was founded in 2019 with a deep passion for capturing life’s most precious moments through the art of photography. 
                As an independent photography company, PM&JI is dedicated to preserving memories in vivid detail, 
                specializing in high-quality images for a variety of events, including birthdays, weddings, anniversaries, 
                baptisms, corporate gatherings, and beyond. Our team has a keen eye for detail and an unwavering commitment
                 to excellence, striving to create stunning visual narratives that reflect the unique essence of each 
                 occasion.Whether you’re planning an intimate gathering or a grand celebration, PM&JI is here to provide a seamless photography experience, from initial consultation to the delivery of beautifully edited images. 
                </p>
            <style>
                .about-content p {
    font-size: 18px;
    line-height: 1.5;
    text-align: justify;
    font-style: italic;

}
            </style>
        </div>
        <a href="customer_support.php" class="message-link">
    <div class="message-icon">
        <i class="fa fa-message"></i>
        <span>Connect with Us</span>
    </div>
</a>
        
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
</body>
</html>
