<?php
// Include your database connection file
include('database.php'); // Make sure the file path is correct

if (isset($_GET['file'])) {
    $file_id = $_GET['file'];  // Get the ID or identifier for the file (this could be a unique identifier for the image)

    // Fetch the file path from the database using the file ID (assuming 'id' is the identifier for the record)
    $sql = "SELECT payment_image FROM admin_notifications WHERE reservation_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $file_id); // Assuming 'file_id' is an integer
    $stmt->execute();
    $stmt->bind_result($payment_image);
    $stmt->fetch();
    $stmt->close();

    // Check if the image file path exists
    if (!empty($payment_image)) {
        // Construct the file path based on the value from the database
        $file_path = 'images/' . $payment_image;

        // Check if the file exists
        if (file_exists($file_path)) {
            // Set headers to force the download
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
            header('Content-Length: ' . filesize($file_path));
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');

            // Read the file and send it to the user
            readfile($file_path);
            exit;
        } else {
            // If the file doesn't exist, show an error message
            echo 'File not found in the images directory: ' . $file_path;
        }
    } else {
        echo 'No file found in the database for this ID.';
    }
} else {
    echo 'No file specified!';
}
?>
