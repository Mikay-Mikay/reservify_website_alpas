<?php
// admin_img_download.php

if (isset($_GET['image'])) {
    // Retrieve the image file name passed through the URL
    $file_name = $_GET['image'];

    // Define the full file path (make sure the directory is correct)
    $file_path = 'images/' . $file_name;

    // Check if the file exists
    if (file_exists($file_path)) {
        // Set the headers to force a download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Content-Length: ' . filesize($file_path));

        // Read and output the file to the browser
        readfile($file_path);
        exit;
    } else {
        echo "File not found.";
    }
} else {
    echo "No image specified.";
}
?>
