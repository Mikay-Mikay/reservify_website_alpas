<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PM&JI Reservify</title>
    <link rel="stylesheet" href="portfolio.css">
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
        <img src="images/reservify_logo.png" alt="PM&JI logo" id="logo-pic">
        <h1 class="reservify-text">Our Works</h1>
    </div>

    <div class="birthday">
        <h2 class="reservify-text">Birthdays</h2>
    </div>
    
    <div class="containers">
        <div class="cards">
            <img src="images/pic10.jpg" alt="pic10">
        </div>
        <div class="cards">
            <img src="images/pic11.jpg" alt="pic11">
        </div>
        <div class="cards">
            <img src="images/pic12.jpg" alt="pic12">
        </div>
        <div class="cards">
            <img src="images/pic13.jpg" alt="pic13">
        </div>
        <div class="cards">
            <img src="images/pic14.jpg" alt="pic14">
        </div>
    </div>
    
    <div class="Company">
        <h2 class="reservify-text">Company Anniversary</h2>
    </div>

   <div class="containers1">
        <div class="cards">
            <img src="images/pic1.jpg" alt="pic1">
        </div>
        <div class="cards">
            <img src="images/pic2.jpg" alt="pic2">
        </div>
        <div class="cards">
            <img src="images/pic4.jpg" alt="pic4">
        </div>
        <div class="cards">
            <img src="images/pic15.jpg" alt="pic15">
        </div>
        <div class="cards">
            <img src="images/pic16.jpg" alt="pic16">
        </div>
    </div> 

    <div class="Reunions">
        <h2 class="reservify-text">Reunions</h2>
    </div>
    <div class="containers2">
        <div class="cards">
            <img src="images/pic3.jpg" alt="pic3">
        </div>
        <div class="cards">
            <img src="images/pic6.jpg" alt="pic6">
        </div>
        <div class="cards">
            <img src="images/pic7.jpg" alt="pic7">
        </div>
        <div class="cards">
            <img src="images/pic8.jpg" alt="pic8">
        </div>
        <div class="cards">
            <img src="images/pic17.jpg" alt="pic17">
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