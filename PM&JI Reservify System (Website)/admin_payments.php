<?php
session_start();
require_once 'databasee.php'; // Include the external database connection

// Handle form actions (Create, Delete)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create'])) {
    // Retrieve form data
    $ref_no = isset($_POST['ref_no']) ? trim($_POST['ref_no']) : '';
    $date = isset($_POST['date']) ? trim($_POST['date']) : '';
    $client_name = isset($_POST['client_name']) ? trim($_POST['client_name']) : '';
    $amount = isset($_POST['amount']) ? trim($_POST['amount']) : '';
    $payment_method = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : '';

    // Validate payment method input
    $allowed_methods = ['Gcash', 'Maya'];
    if (!in_array($payment_method, $allowed_methods)) {
        echo "<script>alert('Invalid payment method selected!');</script>";
        exit();
    }

    // Validate all fields are present
    if (empty($ref_no) || empty($date) || empty($client_name) || empty($amount) || empty($payment_method)) {
        echo "<script>alert('All fields are required!');</script>";
        exit();
    }

    // Insert the payment record into the database
    try {
        $sql = "INSERT INTO admin_payments (ref_no, date, client_name, amount, payment_method) 
                VALUES (:ref_no, :date, :client_name, :amount, :payment_method)";
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([
            ':ref_no' => $ref_no,
            ':date' => $date,
            ':client_name' => $client_name,
            ':amount' => $amount,
            ':payment_method' => $payment_method
        ]);

        if ($result) {
            echo "<script>alert('Payment added successfully.'); window.location.href='admin_payments.php';</script>";
        } else {
            echo "<script>alert('Failed to add payment. Please try again.');</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Database error: {$e->getMessage()}');</script>";
    }
}

// Handle delete action
if (isset($_POST['confirm_delete']) && isset($_POST['payments_to_delete'])) {
    $payments_to_delete = $_POST['payments_to_delete'];

    try {
        foreach ($payments_to_delete as $id) {
            // Delete payment record
            $stmt = $conn->prepare("DELETE FROM admin_payments WHERE id = :id");
            $stmt->execute([':id' => $id]);
        }

        echo "<script>alert('Selected payments deleted successfully.'); window.location.href='admin_payments.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Failed to delete payments: {$e->getMessage()}');</script>";
    }
}

// Fetch all payment records
try {
    $sql = "SELECT * FROM admin_payments ORDER BY date DESC";
    $stmt = $conn->query($sql);
    $admin_payments = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
} catch (PDOException $e) {
    echo "<script>alert('Failed to fetch payments: {$e->getMessage()}');</script>";
    $admin_payments = [];
}

// Admin name from session
$admin_name = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'Admin';

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin_login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments - Admin Panel</title>
    <link rel="stylesheet" href="admin_payments.css?">
    <link rel="stylesheet" href="admin_dashboard.css">
    <link rel="stylesheet" href="admin_profile.css?">
    <link rel="stylesheet" href="admin_activitylog.css">
    <link rel="stylesheet" href="admin_bookingstatus.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

</head>
<body>
<div class="admin-dashboard">
        <aside class="sidebar">
            <div class="logo">
                <img src="images/reservify_logo.png" alt="Reservify Logo">
                <p>Hello, Admin!</p>
            </div>
            <nav>
                <ul>
                    <li class="dashboard-item">
                        <a href="admin_dashboard.php" style="display: flex; align-items: center; gap: 7px;">
                            <img src="images/home.png.png" alt="Home Icon">
                            <span style="margin-left: 1px; margin-top: 4px;">Dashboard</span>
                        </a>
                    </li>
                </ul>
                <hr class="divider">
                <ul>
                    <li>
                        <a href="admin_bookingstatus.php" style="text-decoration: none; color: white; display: flex; justify-content: space-between; align-items: center;">
                        <span>Bookings</span>
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
                    <li>
                        <a href="admin_calendar.php"style="text-decoration: none; color: white; display: flex; justify-content: space-between; align-items: center;">
                        <span>Calendar</span>
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                </ul>
                <hr class="divider">
                <ul>
                    <li>
                        <a href="admin_manageinq.php"style="text-decoration: none; color: white; display: flex; justify-content: space-between; align-items: center;">
                        <span>Manage Inquiries</span>
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

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
        
<!-- Table -->
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
    <?php foreach ($admin_payments as $payment): ?>
<tr>
    <td><?php echo htmlspecialchars($payment['ref_no']); ?></td>
    <td><?php echo htmlspecialchars($payment['date']); ?></td>
    <td><?php echo htmlspecialchars($payment['client_name']); ?></td>
    <td>PHP <?php echo number_format($payment['amount'], 2); ?></td>
    <td><?php echo htmlspecialchars($payment['payment_method']); ?></td>
</tr>
<?php endforeach; ?>

</tbody>

</table>
<!-- Create Payment Modal -->
<div id="createPaymentModal" class="modal">
    <div class="modal-content">
        <h2>Create Payment</h2>
        <form method="POST">
            <label for="ref_no">ID/Ref No.</label>
            <input type="text" name="ref_no" required>

            <label for="date">Date</label>
            <input type="text" id="datePicker" name="date" required>

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
                <button type="submit" class="btn save">Save</button>
                <button type="button" class="btn cancel" onclick="closeCreateModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>
<!-- Delete Payment Modal -->
<div id="deletePaymentModal" class="modal">
    <div class="modal-content">
        <h2>Select Payment/s to Delete</h2>
        <form method="POST">
            <?php foreach ($admin_payments as $payment): ?>
                <div>
                    <input type="checkbox" name="payments_to_delete[]" value="<?php echo $payment['id']; ?>">
                    <?php echo htmlspecialchars($payment['ref_no']) . ' - ' . htmlspecialchars($payment['client_name']); ?>
                </div>
            <?php endforeach; ?>

            <div class="form-actions">
                <button type="submit" class="btn delete">Delete Selected</button>
                <button type="button" class="btn cancel" onclick="closeDeleteModal()">Cancel</button>
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

document.addEventListener('DOMContentLoaded', function () {
        flatpickr("#datePicker", {
            dateFormat: "Y-m-d", // Set the desired date format
        });
    });

    function showAlert(message) {
    alert(message);
    window.location.reload();
}

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

    function toggleDropdown() {
            const dropdown = document.getElementById('profile-dropdown');
            dropdown.classList.toggle('show');
        }

        window.onclick = function(event) {
            if (!event.target.matches('.profile-icon')) {
                const dropdown = document.getElementById('profile-dropdown');
                if (dropdown && dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            }
        }
        // Toggle Notification Dropdown
        function toggleNotification() {
            const notifDropdown = document.getElementById('notification-dropdown');
            notifDropdown.classList.toggle('show');

            // Close notification dropdown if clicked outside
            if (!event.target.matches('#notif-bell') && !event.target.closest('.notification-container')) {
                const notifDropdown = document.getElementById('notification-dropdown');
                if (notifDropdown && notifDropdown.classList.contains('show')) {
                    notifDropdown.classList.remove('show');
                }
            }
        }
</script>
</body>
</html> 
