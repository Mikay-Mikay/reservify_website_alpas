<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PM&JI Reservify</title>
    <link rel="stylesheet" href="customer_mybookings.css?v=1.2">
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
            <li><a href="Contact us.php">Contact Us</a></li>
            <li><a href="customer_mybookings.php">My Bookings</a></li>
            <li class="user-logo">
            <a href="login.php">
             <img src="images/user_logo.png" alt="User Logo">
            </a>
            </li> 
        </ul>
    </nav>
    <div class="back-button">   
            <a href="javascript:history.back()">
                <img src="images/back button.png" alt="Back">
            </a>
        </div>
        <h1 class="page-title">My Bookings</h1>

    <div class="container">
        <div class="buttons">
            <button class="active-bookings">Active Bookings</button>
            <button class="previous-bookings">Previous Bookings</button>
        </div>
        
        <div class="booking-container">
            <div class="booking-card">
                <strong>PMJI-20241130-CUST003</strong> <img src="images/green_active.png.png" alt="Active" class="status-dot">
                <p><strong>Message:</strong> A new payment has been made. Payment ID: 240, Amount: 21, Reference No: 2147483647, Payment Method: GCash, Payment Type: Downpayment. Customer: Dexter Bernil Cabubas (cabubasdexter@gmail.com). Event: Reunion at Tagaytay, Participants: 21. Schedule: From 2025-02-05 12:00:00 to 2025-02-05 12:00:00.</p>
                <p><strong>Created At:</strong> 2025-02-04 01:26:03</p>
            </div>
        </div>
    </div>
</body>
</html>