<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PM&JI Reservify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="Contact us.css">
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
        <li><a href="Home.php">Home</a></li> <!-- ginawa kong Home.php -->
            <li><a href="About Us.php">About Us</a></li> <!-- ginawa kong About Us.php -->
            <li><a href="Contact us.php">Contact Us</a></li> <!-- ginawa kong Contact Us.php -->
            <li><a href="customer_mybookings.php">My Bookings</a></li> <!-- dinagdag ko -->
            <li class="user-logo">
            <a href="login.php">
             <img src="images/user_logo.png" alt="User Logo">
            </a>
            </li> 
        </ul>
    </nav>

    <div class="container">
        <img src="images/reservify_logo.png" alt="PM&JI logo" id="logo-pic">
        <h1 class="reservify-text"><b>Contact Us</b></h1>
    </div>


    <div class="review">
        <a href="#" class="clickable-text">View Reviews</a>
    </div>

    <div class="container1">
        <div class="info-section">
          <div class="details">
            <h2><b>PM&JI Pictures</b></h2>
            <p><h4>Phase 5Y Bagong Silang<br>North Caloocan, 1428</h4></p>
            <h2><b>Working Hours</b></h2>
            <p><h4>Tuesday - Saturday<br>9:00 AM - 5:30 PM</h4></p>
          </div>

          <div class="contact">
            <h2><b>Contacts</b></h2>
            <p><h4>0915 613 8722</h4></p>
            <h2><b>Social Media</b></h2>
            <a href="https://www.facebook.com/pmandjipictures" class="social-media">
              <i class="fab fa-facebook"></i>
            </a>
          </div>
        </div>
        <div class="map-section">
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d32698.972314073213!2d121.02108024027982!3d14.771002984267508!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397afdff2dede6b%3A0xd7c2cfcf062090ab!2sPhase%205Y%20Covered%20court!5e0!3m2!1sen!2sph!4v1732110997192!5m2!1sen!2sph" "
            width="400" height="350" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        
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
