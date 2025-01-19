<?php
// Start the session to retrieve user information
session_start();

// Database connection
require_once "database.php";

// Retrieve the user ID from the session
$user_id = $_SESSION['id'];

//change the tr.country to address
// Query to fetch the required data from the test_registration table
 $sql = "
    SELECT
        tr.Date_of_Birth,
        tr.Address,
        tr.Last_Name,
        tr.Middle_Name,
        tr.First_Name,
        tr.Gender,
        tr.Phone_Number,
        tr.Email
    FROM test_registration tr
    WHERE tr.id = ?"; // Assuming you have a user_id column

// Prepare and execute the query
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id); // Binding the user_id parameter
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    if ($data) {
        // Data fetched successfully
        $First_Name = $data['First_Name'];
        $Middle_Name = $data['Middle_Name'];
        $Last_Name = $data['Last_Name'];
        $Email = $data['Email'];
        $Phone_Number = $data['Phone_Number'];
        $Address = $data['Address'];
        $Date_of_Birth = $data['Date_of_Birth'];
        $Gender = $data['Gender'];
    } else {
        echo "No data found for the user.";
        exit();
    }
} else {
    echo "Database query error: " . mysqli_error($conn);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PM&JI Reservify</title>
    <link rel="stylesheet" href="profile_user.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</head>
<body>
    <nav>
        <div class="logo">
            <a href="Home.html">
                <img src="images/reservify_logo.png" alt="PM&JI logo">
                <span class="logo-text">PM&JI<br>Reservify</span>
            </a>
        </div>
        <div class="toggle">
            <a href="#"><ion-icon name="menu-outline"></ion-icon></a>
        </div>
        <ul class="menu">
            <li><a href="Home.html">Home</a></li>
            <li><a href="About Us.html">About Us</a></li>
            <li><a href="portfolio.html">Portfolio</a></li>
            <li><a href="Contact us.html">Contact Us</a></li>
            <li class="user-logo">
                <img src="images/user_logo.png" alt="User Logo">
            </li> 
        </ul>
    </nav>

    <!-- Profile Header Outside the Container -->
    <div class="profile-header">
        <h1>Profile</h1>
    </div>

    <!-- Profile Section Container -->
    <div class="profile-container">
        <form action="save_profile.php" method="POST">
            <div class="profile-info">
                <div class="profile-row">
                    <label for="dob"><img src="images/dob.png" alt="Date of Birth Icon"> Date of Birth:</label>
                    <input type="text" id="dob" name="dob" value="<?php echo $Date_of_Birth; ?>" placeholder="MM/DD/YY">
                </div>
                <div class="profile-row">
                    <label for="address"><img src="images/country_region.png" alt="Country/Region Icon">Full Address:</label>
                    <input type="text" id="address" name="address" value="<?php echo $Address; ?>">
                </div>
                <div class="profile-row">
                    <label for="display_name"><img src="images/profile_user_icon.png" alt="Display Name Icon"> Display Name:</label>
                    <input type="text" id="display_name" name="display_name" value="<?php echo $First_Name . ' ' . $Middle_Name . ' ' . $Last_Name; ?>">
                </div>
                <div class="profile-row">
                    <label for="gender"><img src="images/gender.png" alt="Gender Icon"> Gender:</label>
                    <input type="text" id="gender" name="gender" value="<?php echo $Gender; ?>">
                </div>
                <div class="profile-row">
                    <label for="tel"><img src="images/tel.png" alt="Phone Icon"> Tel#:</label>
                    <input type="text" id="tel" name="tel" value="<?php echo $Phone_Number; ?>">
                </div>
                <div class="profile-row">
                    <label for="email"><img src="images/email.png" alt="Email Icon"> Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $Email; ?>">
                </div>
            </div>
            <div class="profile-footer">
                <a href="Edit Password.php">
                    <button type="button" class="btn-edit-password">Edit Password</button>
                </a>
                <a href="reservation.php">
                    <button type="button" class="btn-cancel">Cancel</button>
                </a>
                <a href="reservation.php">
                    <button type="submit" class="btn-save">Save</button>
                </a>
                <a href="login.php">
                    <button type="submit" class="btn-logout">Log Out</button>
                </a>
            </div>
        </form>
    </div>
</body>
</html>