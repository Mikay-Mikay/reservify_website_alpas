<?php
session_start();

// Initialize the admin name from the session
$admin_name = $_SESSION['fullname'] ?? 'Admin'; // Default to 'Admin' if session variable is not set
$errors = array();

// Handle form submission
if (isset($_POST["submit"])) {
    // Retrieve form inputs
    $ref_no = $_POST["ref_no"] ?? '';
    $date = $_POST["date"] ?? '';
    $client_name = $_POST["client_name"] ?? '';
    $amount = $_POST["amount"] ?? '';
    $payment_method = $_POST["payment_method"] ?? '';

    // Validate the inputs
    if (empty($ref_no) || empty($date) || empty($client_name) || empty($amount) || empty($payment_method)) {
        array_push($errors, "All fields are required.");
    }

    // If errors exist, display them
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {
        // Database connection
        require_once "databasee.php";

        // SQL query to insert payment details
        $sql = "INSERT INTO admin_payments (ref_no, date, client_name, amount, payment_method) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        // Check if statement preparation was successful
        if ($stmt === false) {
            die("MySQL prepare failed: " . mysqli_error($conn));
        }

        // Bind parameters to the SQL query
        mysqli_stmt_bind_param($stmt, "sssss", $ref_no, $date, $client_name, $amount, $payment_method);

        // Execute the query and handle any errors
        if (!mysqli_stmt_execute($stmt)) {
            die("Error executing query: " . mysqli_error($conn));
        }

        // Success message and redirect
        echo "<script>
            alert('Payment details successfully added.');
            window.location.href = 'testing.php';
        </script>";
    }
}

// Fetch the payment details from the database
require_once "databasee.php";
$sql = "SELECT * FROM admin_payments";  // Query to fetch all payment records
$result = mysqli_query($conn, $sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments - Admin Panel</title>
    <link rel="stylesheet" href="admin_payments.css?v=1.1">
    <link rel="stylesheet" href="admin_dashboard.css?v=1.1">
    <link rel="stylesheet" href="admin_profile.css?v=1.1">
    <link rel="stylesheet" href="admin_activitylog.css?v=1.1">
    <link rel="stylesheet" href="admin_bookingstatus.css?v=1.1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body>
<div class="admin-dashboard">
    <aside class="sidebar">
        <div class="logo">
            <img src="images/reservify_logo.png" alt="Reservify Logo">
            <p>Hello, <?php echo htmlspecialchars($admin_name); ?>!</p>
        </div>
        <nav>
            <ul>
                <li class="dashboard-item">
                    <a href="admin_dashboard.php" style="display: flex; align-items: center; gap: 7px;">
                        <img src="images/home.png (1).png" alt="Home Icon">
                        <span style="margin-left: 1px; margin-top: 4px;">Dashboard</span>
                    </a>
                </li>
            </ul>
            <hr class="divider">
            <ul>
                <li>
                    <a href="admin_bookingstatus.php" style="text-decoration: none; color: white; display: flex; justify-content: space-between; align-items: center;">
                        <span>Booking Status</span>
                        <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                    </a>
                </li>
                <li>
                    <a href="admin_payments.php"style="text-decoration: none; color: white; display: flex; justify-content: space-between; align-items: center;">
                        <span>Payments</span>
                        <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                    </a>
                </li>
                <li>
                    <a href="admin_bookinghistory.php"style="text-decoration: none; color: white; display: flex; justify-content: space-between; align-items: center;">
                        <span>Booking History</span>
                        <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                    </a>
                </li>
                <li>
                    <a href="admin_managefeedback.php"style="text-decoration: none; color: white; display: flex; justify-content: space-between; align-items: center;">
                        <span>Manage Feedback</span>
                        <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                    </a>
                </li>
            </ul>
            <hr class="divider">
            <ul>
                <li>
                    <a href="admin_calendar.php"style="text-decoration: none; color: white; display: flex; justify-content: space-between; align-items: center;">
                        <span>Calendar</span>
                        <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                    </a>
                </li>
                <li>
                    <a href="admin_bookinghistory.php"style="text-decoration: none; color: white; display: flex; justify-content: space-between; align-items: center;">
                        <span>Progress</span>
                        <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                    </a>
                </li>
            </ul>
            <hr class="divider">
            <ul>
                <li>
                    <a href="admin_bookinghistory.php"style="text-decoration: none; color: white; display: flex; justify-content: space-between; align-items: center;">
                        <span>Manage Inquiries</span>
                        <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Content -->
    <div class="content">
       <!-- Content -->
    <div class="content">
        <header>
            <h1>Payments</h1>
            <div class="header-right">
                <input type="text" id="searchBar" placeholder="Search payments..." onkeyup="searchTable()">
                <div>
                    <button class="btn create" onclick="openCreateModal()">Create</button>
                    <button class="btn delete" onclick="openDeleteModal()">Delete</button>
                </div>
                </div>

            <!-- Notification Bell -->
        <div class="notification-container">
                <img src="images/notif_bell.png.png" alt="Notification Bell" id="notif-bell" onclick="toggleNotification()">
                <div id="notification-dropdown" class="notification-dropdown">
                    <h2>Notifications</h2>
                    <!-- Static Notifications (pansamantala lang, gawan mo php to hehe) -->
                    <div class="notification">
                        <p><strong>PMJI-20241130-CUST001</strong> John A. Doe successfully paid PHP 3,500 for Booking ID #56789 via GCash.</p>
                        <span>3:30 PM, Nov 29, 2024</span>
                    </div>
                    <div class="notification">
                        <p><strong>Ticket-CS-20241129-0003</strong> John A. Doe: "Service Inquiry" — Can I reschedule my booking for December 8, 2024? Contact details logged.</p>
                        <span>11:30 AM, Nov 29, 2024</span>
                    </div>
                    <div class="notification">
                        <p><strong>PMJI-20241130-CUST002</strong> Anne C. Cruz attempted payment for booking #56789 but it failed. Please follow up.</p>
                        <span>2:45 PM, Nov 29, 2024</span>
                    </div>
                    <div class="notification">
                        <p><strong>PMJI-20241130-CUST003</strong> Jane D. Smith requested a booking for December 20, 2024. Please review and approve or decline.</p>
                        <span>4:15 PM, Nov 29, 2024</span>
                    </div>
                </div>
            </div>
                        <!-- Profile Icon -->
                        <div class="profile-container">
                            <img class="profile-icon" src="images/user_logo.png" alt="Profile Icon" onclick="toggleDropdown()">
                            <div id="profile-dropdown" class="dropdown">
                                <p class="dropdown-header"><?php echo htmlspecialchars($admin_name); ?></p>
                                <hr>
                                <ul>
                                    <li><a href="admin_profile.php">Profile</a></li>
                                    <li><a href="admin_activitylog.php">Activity Log</a></li>
                                </ul>
                                <hr>
                                <a class="logout" href="?logout">Logout</a>
                            </div>
                        </div>
        </header>

        <!-- Table to Display Payments -->
        <table class="payments-table">
            <thead>
                <tr>
                    <th>ID/Ref No.</th>
                    <th>Date</th>
                    <th>Name of Client</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result) {
                    // Loop through the fetched data and display each row
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['ref_no']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['client_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['amount']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['payment_method']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No data available</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for creating a payment -->
<div id="createPaymentModal" class="modal">
    <div class="modal-content">
        <h2>Create Payment</h2>
        <form method="POST">
            <label for="ref_no">ID/Ref No.</label>
            <input type="text" name="ref_no" required>

            <label for="date">Date</label>
            <input type="date" id="datePicker" name="date" required>

            <label for="client_name">Name of Client</label>
            <input type="text" name="client_name" required>

            <label for="amount">Amount</label>
            <input type="number" name="amount" required>

            <label for="payment_method">Payment Method</label>
            <select name="payment_method" required>
                <option value="Gcash">Gcash</option>
                <option value="Maya">Maya</option>
            </select>

            <div class="form-actions">
                <button type="submit" name="submit" class="btn save">Save</button>
                <button type="button" class="btn cancel" onclick="closeCreateModal()">Cancel</button>
            </div>
        </form>
    </div>

    
</div>

<!-- JavaScript -->
<script>
    // Functions to open and close Create Modal
    function openCreateModal() {
        document.getElementById("createPaymentModal").style.display = "flex";
    }

    function closeCreateModal() {
        document.getElementById("createPaymentModal").style.display = "none";
    }

    // Functions to open and close Delete Modal
    function openDeleteModal() {
        document.getElementById("deletePaymentModal").style.display = "flex";
    }

    function closeDeleteModal() {
        document.getElementById("deletePaymentModal").style.display = "none";
    }

    // Initialize flatpickr
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr("#datePicker", {
            dateFormat: "Y-m-d", // Set the desired date format
        });
    });

    // Search Function
    function searchTable() {
        const input = document.getElementById("searchBar").value.toUpperCase();
        const table = document.querySelector("table tbody");
        const rows = table.getElementsByTagName("tr");

        for (let i = 0; i < rows.length; i++) {
            const cell = rows[i].getElementsByTagName("td")[0];
            if (cell) {
                const textValue = cell.textContent || cell.innerText;
                rows[i].style.display = textValue.toUpperCase().indexOf(input) > -1 ? "" : "none";
            }
        }
    }
</script>

</body>
</html>
