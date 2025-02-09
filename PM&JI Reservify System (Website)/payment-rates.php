<?php
session_start();
?>

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

     <!-- Back Button -->
     <a href="javascript:history.back()" class="back-button">
        <img src="images/back button.png" alt="Back Button">
    </a>

    <div class="container">
        <img src="images/reservify_logo.png" alt="PM&JI logo" id="logo-pic">
        <h1 class="reservify-text">PM&JI Payment rates</h1>
    </div>

    <div class="tag-line">
        <p><strong>Baptism price</strong>

Minimum ₱4,500 for 3 Hours downpayment ₱2,250
Maximum ₱4,600 for 4 hours downpayment ₱2,300
Unlimited shots
All pictures will be enhanced and can be sent/ shared via Gmail or Google Drive.<br><br>


<strong>Reunion</strong>

Minimum ₱7,000 for 3 Hours downpayment ₱3,500
Maximum ₱8,500 for 4 Hours downpayment ₱4,250
Unlimited shots
All pictures will be enhanced and can be sent/ shared via Gmail or Google Drive.<br><br>


<strong>Birthday</strong>

Minimum ₱3,500 for 3 Hours downpayment ₱1,750
Maximum ₱4,000 for 5 Hours downpayment ₱2,000
Unlimited shots
All pictures will be enhanced and can be sent/ shared via Gmail or Google Drive.<br><br>


<strong>Company Event</strong> 

Minimum ₱8,500 for 3 Hours downpayment ₱4,250
Maximum ₱10,000 for 6 Hours down payment ₱5000
Unlimited shots
All pictures will be enhanced and can be sent/ shared via Gmail or Google Drive.<br><br>


<strong>Wedding </strong>

Minimum ₱7,500 for 3 Hours downpayment ₱3,750
Maximum ₱11,000 for 5 Hours downpayment ₱5,500
Unlimited shots
All pictures will be enhanced and can be sent/ shared via Gmail or Google Drive.<br><br>

for <strong>Other events</strong>
Price is ₱10,000 downpayment is ₱5000
Unlimited shots
All pictures will be enhanced and can be sent/ shared via Gmail or Google Drive.<br><br>
</p>
    </div>

<a href="connect_with_us.php" class="message-link">
    <div class="message-icon">
        <i class="fa fa-message"></i>
        <span>Connect with Us</span>
    </div>
</a>
    
    <style>
        /* Back Button */
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .back-button img {
            width: 50px; /* Adjust as needed */
            height: auto;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .back-button img:hover {
            transform: scale(1.1);
        }

        .tag-line {
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: justify;
        padding: 20px;
    }

    .tag-line p {
        max-width: 800px;
        width: 100%;
        margin: 0;
    }
    .message-icon {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 50px;
    padding: 10px 15px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    cursor: pointer;
    font-size: 24px;
    color: #f4a36c;
    display: flex;
    align-items: center;
    gap: 10px;
    }

    .message-icon i {
        font-size: 24px;
        color: #f4a36c;
    }

    .message-icon span {
        font-size: 16px;
        color: #333;
        font-weight: bold;
    }

    </style>

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
