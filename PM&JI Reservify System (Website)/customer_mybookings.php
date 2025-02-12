<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PM&JI Reservify</title>
    <link rel="stylesheet" href="customer_mybookings.css?v=1.1">
</head>
<style>  
        /* Dropdown Styling */
        .notification-dropdown {
            display: none;
            position: absolute;
            top: 40px;
            right: 20px;
            width: 300px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .notification-dropdown.show {
            display: block;
        }

        .notification-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
        }

        .notification-item:hover {
            background-color: #f9f9f9;
        }

        .notification-item .time {
            font-size: 0.8rem;
            color: #666;
        }

        .notification-bell {
            position: relative;
            cursor: pointer;
        }

        .notification-bell .notification-count {
            position: absolute;
            top: 0;
            right: 0;
            background-color: red;
            color: white;
            border-radius: 50%;
            font-size: 0.8rem;
            padding: 3px 7px;
        }

        #notif-bell {
            width: 40px;
            height: auto;
            cursor: pointer;
            display: inline-block;
        }

</style>
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
            <li><a href="About Us.php">About Us</a></li> <!-- ginawa kong About Us.php -->
            <li><a href="reservation.php">Reserve Now</a></li> <!-- ginawa kong About Us.php -->
            <li><a href="customer_mybookings.php">My Bookings</a></li> <!-- nag lagay ako ng my bookings sa navbar -->
            <li><a href="contact_us1.php">Contact Us</a></li>
            <li class="user-logo">
                <a href="profile_user.php">
                    <img src="images/user_logo.png" alt="User Logo">
                </a>
            </li>
            <li>
                <div class="notification-bell">
                    <img src="images/notif_bell.png.png" alt="Notification Bell" id="notif-bell" onclick="toggleNotification()">
                    <span class="notification-count"></span>
                </div>
                <div class="notification-dropdown">
                    <p>Loading notifications...</p>
                </div>
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
                <p><strong>Message:</strong> A new payment has been made. Payment ID: 240, Amount: 21, Reference No: 2147483647, Payment Method: GCash, Payment Type: Downpayment. Customer: Juan Dela Cruz (linrebriley@gmail.com). Event: Reunion at Tagaytay, Participants: 21. Schedule: From 2025-02-05 12:00:00 to 2025-02-05 12:00:00.</p>
                <p><strong>Created At:</strong> 2025-02-04 01:26:03</p>
            </div>
        </div>
    </div>
</body>
</html>
