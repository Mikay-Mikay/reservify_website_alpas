
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="Edit Password.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
</head>

<body>
    <form action="" method="POST" id="EditPassword">
        <h1>Edit Password</h1>
        <div class="input-box">
            <input type="email" name="Email" placeholder="Current Password:" id="currentpassword" required>
            
        </div>
        <div class="input-box">
            <input type="email" name="Email" placeholder="New Password:" id="newPassword" required>
            
        </div>
        <div class="input-box">
            <input type="email" name="Email" placeholder="Confirm Password:" id="confirmPassword" required>
            
        </div>
       
        <button type="submit" class="btn">Next</button>

   

    <style>
        .error-message {
            margin-top: 10px;
            text-align: center;
            color: red !important; /* Red color for the error message */
            font-size: 12px; /* Adjust font size */
            font-weight: bold;
            font-family: 'Poppins', sans-serif; /* Ensure consistent font */
        }
    </style>
</body>

</html>
