<?php
session_start();
require_once "database.php";

// Initialize the admin name from the session
$admin_name = $_SESSION['fullname'] ?? 'Admin'; // Default to 'Admin' if session variable is not set
$errors = array();

// Handle form submission
if (isset($_POST["submit"])) {
    // Retrieve form inputs
    $ref_no = trim($_POST["ref_no"] ?? '');
    $date = trim($_POST["date"] ?? '');
    $amount = trim($_POST["amount"] ?? '');
    $payment_method = trim($_POST["payment_method"] ?? '');
    $reservation_id = trim($_POST["reservation_id"] ?? '');  // Now using reservation_id

    // Validate the inputs
    if (empty($ref_no) || empty($date) || empty($amount) || empty($payment_method) || empty($reservation_id)) {
        array_push($errors, "All fields are required.");
    }

    if (!is_numeric($amount) || $amount <= 0) {
        array_push($errors, "Invalid amount.");
    }

    // If no errors, insert the payment
    if (count($errors) === 0) {
        $sql = "INSERT INTO payment (reservation_id, ref_no, amount, payment_method, created_at) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "isdss", $reservation_id, $ref_no, $amount, $payment_method, $date);
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>
                    alert('Payment details successfully added.');
                    window.location.href = 'admin_payments.php';
                </script>";
            } else {
                array_push($errors, "Error executing query: " . mysqli_error($conn));
            }
            mysqli_stmt_close($stmt);
        } else {
            array_push($errors, "MySQL prepare failed: " . mysqli_error($conn));
        }
    }
}

// Handle filtering by month
$filter_month = $_POST['month'] ?? ''; // Get selected month
$where_clause = "";
if ($filter_month) {
    $where_clause = "WHERE MONTH(created_at) = '$filter_month'";
}

// Fetch the payment details from the database with month filtering
$sql = "SELECT 
            p.amount, 
            p.ref_no, 
            p.payment_method, 
            p.payment_type, 
            p.created_at, 
            p.status, 
            r.user_id
        FROM payment p
        JOIN reservation r ON p.reservation_id = r.reservation_id $where_clause";

$result = mysqli_query($conn, $sql);

// Export to Excel functionality
if (isset($_POST['export_excel'])) {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="payments_data.xls"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Amount', 'Ref No.', 'Payment Method', 'Payment Type', 'Status', 'Date']); // Header row

    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments - Admin Panel</title>
    <link rel="stylesheet" href="admin_payments.css?v=1.2">
    <link rel="stylesheet" href="admin_dashboard.css?v=1.2">
    <link rel="stylesheet" href="admin_profile.css?v=1.1">
    <link rel="stylesheet" href="admin_activitylog.css?v=1.1">
    <link rel="stylesheet" href="admin_bookings.css?v=1.1">
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
                            <img src="images/home.png" alt="Home Icon">
                            <span style="margin-left: 1px; margin-top: 4px; color: black;">Dashboard</span>
                        </a>
                    </li>
                </ul>
                <hr class="divider">
                <ul>
                    <li>
                        <a href="admin_bookings.php" style="text-decoration: none; color: black; display: flex; justify-content: space-between; align-items: center;">
                        <span>Bookings</span>
                        <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                    <li>
                        <a href="admin_payments.php"style="text-decoration: none; color: black; display: flex; justify-content: space-between; align-items: center;">
                        <span>Payments</span>
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                    <li>
                        <a href="admin_bookinghistory.php"style="text-decoration: none; color: black; display: flex; justify-content: space-between; align-items: center;">
                        <span>Booking History</span>
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                    <li>
                        <a href="admin_managefeedback.php"style="text-decoration: none; color: black; display: flex; justify-content: space-between; align-items: center;">
                        <span>Manage Feedback</span>
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                    <li>
                        <a href="admin_calendar.php"style="text-decoration: none; color: black; display: flex; justify-content: space-between; align-items: center;">
                        <span>Calendar</span>
                            <img class="click-here" src="images/click_here.png.png" alt="Click Here">
                        </a>
                    </li>
                </ul>
                <hr class="divider">
                <ul>
                    <li>
                        <a href="admin_manageinq.php"style="text-decoration: none; color: black; display: flex; justify-content: space-between; align-items: center;">
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
    <header>
    <h1>Payments</h1>
    <div class="header-right">
        <div class="filter-container">
            <!-- PDF Button -->
            <form method="POST" action="pdf.php" target="_blank">
                <input type="hidden" id="selectedMonth" name="month" value="">
                <input type="submit" name="pdf_create" value="PDF" class="pdf-button">
            </form>



            <!-- Dropdown for Months -->
            <select id="monthFilter" onchange="filterPayments()">
                <option value="">Select Month</option>
                <option value="01">January</option>
                <option value="02">February</option>
                <option value="03">March</option>
                <option value="04">April</option>
                <option value="05">May</option>
                <option value="06">June</option>
                <option value="07">July</option>
                <option value="08">August</option>
                <option value="09">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>
        </div>
    </div>
</header>
    
<!-- CSS for alignment -->
<style>
    .filter-container {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .pdf-button {
        padding: 8px 12px;
        background-color: #fac08d;
        color: black;
        border: none;
        cursor: pointer;
        font-size: 16px;
        border-radius: 5px;
    }

    .pdf-button:hover {
        background-color: #f4a36c;
    }

    #monthFilter {
        font-size: 16px;
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #ccc;
        background-color: #f8f9fa;
        cursor: pointer;
        outline: none;
        transition: all 0.3s ease;
    }

    #monthFilter:hover {
        background-color: #e9ecef;
    }

    #monthFilter:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }
</style>


        </form>
    </header>

        <!-- Table to Display Payments -->
        <table class="payments-table">
            <thead>
                <tr>
                    <th>Amount</th>
                    <th>Ref No.</th>
                    <th>Payment Method</th>
                    <th>Payment Type</th>
                    <th>status</th>
                    <th>Date</th>

                </tr>
            </thead>


            <tbody>
    <?php if ($result): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($row['amount']) ?></td>
                <td>
                    <button class="open-modal-btn" 
                        data-ref="<?= htmlspecialchars($row['ref_no']) ?>" 
                        data-userid="<?= htmlspecialchars($row['user_id']) ?>">
                        <?= htmlspecialchars($row['ref_no']) ?>
                    </button>
                </td>
                <td><?= htmlspecialchars($row['payment_method']) ?></td>
                <td><?= htmlspecialchars($row['payment_type']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="7">No data available</td></tr>
    <?php endif; ?>
</tbody>
    <!--PARA SA FETCH MODAL-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $(".open-modal-btn").click(function () {
        let refNo = $(this).data("ref");
        let userId = $(this).data("userid");

        $.ajax({
            url: "fetch_reservation_details.php",
            type: "POST",
            data: { ref_no: refNo, user_id: userId },
            dataType: "json",
            success: function (data) {
                if (data.success) {
                    $("#eventType").text(data.event_type);
                    $("#others").text(data.others);
                    $("#eventPlace").text(data.event_place);
                    $("#photoSize").text(data.photo_size_layout);
                    $("#Email").text(data.Email); // ✅ FIXED
                    $("#contactNumber").text(data.contact_number);
                    $("#startTime").text(data.start_time);
                    $("#endTime").text(data.end_time);
                    $("#status").text(data.status);

                    $("#customerName").text(data.first_name + " " + data.middle_name + " " + data.last_name);

                    $("#modal").fadeIn();
                } else {
                    alert("Details not found!");
                }
            },
            error: function () {
                alert("Error fetching details.");
            }
        });
    });

    $(".close-modal").click(function () {
        $("#modal").fadeOut();
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("printPdfBtn").addEventListener("click", function () {
        // Kunin ang reservation details mula sa modal
        const customerName = document.getElementById("customerName").textContent || "None";
        const eventType = document.getElementById("eventType").textContent || "None";
        const others = document.getElementById("others").textContent || "None";
        const eventPlace = document.getElementById("eventPlace").textContent || "None";
        const photoSize = document.getElementById("photoSize").textContent || "None";
        const Email = document.getElementById("Email").textContent || "None";
        const contactNumber = document.getElementById("contactNumber").textContent || "None";
        const startTime = document.getElementById("startTime").textContent || "None";
        const endTime = document.getElementById("endTime").textContent || "None";
        const status = document.getElementById("status").textContent || "None";

        // Gawa ng URL para i-pass ang data sa PHP script
const pdfUrl = `generate_reservation_pdf.php?customerName=${encodeURIComponent(customerName)}&eventType=${encodeURIComponent(eventType)}&others=${encodeURIComponent(others)}&eventPlace=${encodeURIComponent(eventPlace)}&photoSize=${encodeURIComponent(photoSize)}&Email=${encodeURIComponent(Email)}&contactNumber=${encodeURIComponent(contactNumber)}&startTime=${encodeURIComponent(startTime)}&endTime=${encodeURIComponent(endTime)}&status=${encodeURIComponent(status)}`;

        // Open PDF sa bagong tab
        window.open(pdfUrl, '_blank');
    });
});
</script>

        </table>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>



<!-- JavaScript -->
<script>
// Function to filter payments by selected month
function filterPayments() {
    const selectedMonth = document.getElementById("monthFilter").value;
    const table = document.querySelector("table tbody");
    const rows = table.getElementsByTagName("tr");

    for (let i = 0; i < rows.length; i++) {
        const dateCell = rows[i].getElementsByTagName("td")[5]; // Assuming 'Date' is the 6th column (index 5)
        if (dateCell) {
            const dateText = dateCell.textContent.trim() || dateCell.innerText.trim(); // Remove extra spaces

            // Extract the month from the date assuming format is YYYY-MM-DD
            const dateParts = dateText.split("-");
            if (dateParts.length >= 2) {
                const paymentMonth = dateParts[1].padStart(2, '0'); // Ensure two-digit format

                // Show only rows that match the selected month or show all if no month is selected
                rows[i].style.display = (selectedMonth === "" || paymentMonth === selectedMonth) ? "" : "none";
            }
        }
    }

    // Update hidden input for PDF export
    document.getElementById("selectedMonth").value = selectedMonth;
}

</script>

<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2>Reservation Details</h2>
        <p><strong>Customer Name:</strong> <span id="customerName"></span></p>
        <p><strong>Event Type:</strong> <span id="eventType"></span></p>
        <p><strong>Others:</strong> <span id="others"></span></p>
        <p><strong>Event Place:</strong> <span id="eventPlace"></span></p>
        <p><strong>Photo Size/Layout:</strong> <span id="photoSize"></span></p>
        <p><strong>Email:</strong> <span id="Email"></span></p>
        <p><strong>Contact Number:</strong> <span id="contactNumber"></span></p>
        <p><strong>Start Time:</strong> <span id="startTime"></span></p>
        <p><strong>End Time:</strong> <span id="endTime"></span></p>
        <p><strong>Status:</strong> <span id="status"></span></p>

         <!-- 🖨️ Print PDF Button -->
         <button id="printPdfBtn" style="background-color: red; color: white;">Print PDF</button>


    </div>
</div>

<style>
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background: white;
    padding: 20px;
    width: 50%;
    margin: 10% auto;
    border-radius: 5px;
    position: relative;
}

.modal-content p {
    text-align: left;
}

.close-modal {
    position: absolute;
    top: 10px;
    right: 20px;
    cursor: pointer;
    font-size: 20px;
}
</style>

</body>
</html>
