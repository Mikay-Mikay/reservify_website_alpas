<?php
require_once "database.php";

// Get the notification id from the URL
$notification_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($notification_id) {
    $sql = "SELECT * FROM admin_notifications WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $notification_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $notification = $row;
    } else {
        header("Location: error_page.php");
        exit();
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Details</title>
    <link rel="stylesheet" href="admin_view_notification.css">
</head>
<body>
    <div class="notification-details">
        <h1>Notification Details</h1>
        <p><strong>Notification ID:</strong> <?php echo htmlspecialchars($notification['id']); ?></p>
        <p><strong>Message:</strong> <?php echo htmlspecialchars($notification['message']); ?></p>
        <p><strong>Created At:</strong> <?php echo htmlspecialchars($notification['created_at']); ?></p>

        <!-- Add the clickable image download link -->
        <?php
        if (!empty($notification['image'])) {
            // Assuming 'image' field contains the file name
            $file_name = $notification['image'];
            $file_path = 'images/' . $file_name;

            // Check if file exists
            if (file_exists($file_path)) {
                // Create a link to the download script
                echo '<p><strong>image:</strong> <a href="admin_img_download.php?image=' . urlencode($file_name) . '" target="_blank">Click here to download the image</a></p>';
            } else {
                echo '<p>Image file not found.</p>';
            }
        }
        ?>

        <a href="admin_dashboard.php" class="back-button">Back to Dashboard</a>
    </div>
</body>
</html>
