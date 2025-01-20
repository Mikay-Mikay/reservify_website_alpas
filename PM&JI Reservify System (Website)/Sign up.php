<?php
// Start the session to track user information
session_start();

// Initialize variables
$errors = array();

// Validate the submit button
if (isset($_POST["submit"])) {
    // Retrieve form inputs with null coalescing operator to avoid undefined key warnings
    $First_Name = $_POST["first_name"] ?? '';
    $Middle_Name = $_POST["middle_name"] ?? '';
    $Last_Name = $_POST["last_name"] ?? '';
    $Email = $_POST["email"] ?? '';
    $Phone_Number = $_POST["phone"] ?? '';
    $Address = $_POST["address"] ?? '';
    $Date_of_Birth = $_POST["dob"] ?? '';
    $Password = $_POST["password"] ?? '';
    $Confirm_Password = $_POST["confirm_password"] ?? '';
    $Gender = $_POST["gender"] ?? '';
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

    // reCAPTCHA validation
    $recaptchaSecret = '6Le6rr0qAAAAALs9WJj78sqgHxZ2IvQCOFp825iL';  // Replace with your secret key
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecret&response=$recaptchaResponse");
    $responseKeys = json_decode($response, true);

    // If reCAPTCHA validation fails
    if (!$responseKeys["success"]) {
        array_push($errors, "Please verify that you are not a bot.");
    }

    // Sanitize and hash the password
    $passwordHash = password_hash($Password, PASSWORD_DEFAULT);

    // Validation checks
    if (
        empty($First_Name) || empty($Last_Name) || empty($Email) || empty($Phone_Number) ||
        empty($Address) || empty($Date_of_Birth) ||
        empty($Password) || empty($Confirm_Password) || empty($Gender)
    ) {
        array_push($errors, "All fields are required.");
    }

    if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email is not valid.");
    }

    if (strlen($Password) < 8) {
        array_push($errors, "Password must be at least 8 characters long.");
    }

    if ($Password !== $Confirm_Password) {
        array_push($errors, "Passwords do not match.");
    }

    // Display errors or process the form
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {
        // Database connection
        require_once "database.php";

        // SQL query
        $sql = "INSERT INTO test_registration 
        (First_Name, Middle_Name, Last_Name, Email, Phone_Number, Address, Date_of_Birth, Password, Gender) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Prepare and execute the statement
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            die("Database error: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "sssssssss", $First_Name, $Middle_Name, $Last_Name, $Email, $Phone_Number,  $Address, $Date_of_Birth, $passwordHash, $Gender);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
                alert('Registration Successfully! You will be redirected to log in.');
                window.location.href = 'login.php';
            </script>";
        } else {
            die("Database error: Unable to execute query.");
        }
    }
}

?>




<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
  <link rel="stylesheet" type="text/css" href="Sign up.css?v=1.0">
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script src="Sign up.js"></script>
</head>
<body>
  <div class="container">
    <div class="title">Registration</div>
    <div class="content">
      <form action="" method="POST">
        <div class="user-details">
          <div class="input-box">
            <span class="details">First Name</span>
            <input type="text" name="first_name" placeholder="Enter your first name" required>
          </div>
          <div class="input-box">
            <span class="details">Middle Name</span>
            <input type="text" name="middle_name" placeholder="Enter your middle name">
          </div>
          <div class="input-box">
            <span class="details">Last Name</span>
            <input type="text" name="last_name" placeholder="Enter your last name" required>
          </div>
          <div class="input-box">
            <span class="details">Email</span>
            <input type="email" name="email" placeholder="example@gmail.com" required>
          </div>
          <div class="input-box">
            <span class="details">Phone Number</span>
            <input type="text" name="phone" placeholder="e.g., 09123456789" required>
          </div>

          <div class="input-box">
            <span class="details">Full Address</span>
            <input type="text" name="address" placeholder="Enter your full address" required>
          </div>
          <div class="input-box">
            <span class="details">Date of Birth</span>
            <input type="date" name="dob" required>
          </div>
          <div class="input-box">
            <span class="details">Password</span>
            <input type="password" name="password" placeholder="Enter your password" required>
          </div>
          <div class="input-box">
            <span class="details">Confirm Password</span>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
          </div>
        </div>
        <div class="gender-details">
          <input type="radio" name="gender" id="dot-1" value="Male">
          <input type="radio" name="gender" id="dot-2" value="Female">
          <input type="radio" name="gender" id="dot-3" value="Prefer not to say">
          <span class="gender-title">Gender</span>
          <div class="category">
            <label for="dot-1">
              <span class="dot one"></span>
              <span class="gender">Male</span>
            </label>
            <label for="dot-2">
              <span class="dot two"></span>
              <span class="gender">Female</span>
            </label>
            <label for="dot-3">
              <span class="dot three"></span>
              <span class="gender">Prefer not to say</span>
            </label>
          </div>
        </div>


        <div class="g-recaptcha" data-sitekey="6Le6rr0qAAAAAHRn-AkSkpYlNlHsQCwg_xh_w32w"></div>

        <div class="button">
          <input type="submit" name="submit" value="Register">
        </div>

        <div class="checkbox-container">
          <input type="checkbox" id="terms" name="terms" required>
          <label for="terms">
            I have read, understood, and agree to the <a href="TermsandConditions.php">Terms & Conditions</a>.
            <br>Do you have an account? <a href="login.php">Log In</a>
          </label>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
