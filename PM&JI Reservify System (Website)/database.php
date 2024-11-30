<?php

    $hostName = "localhost";
    $dbUser = "root";
    $dbPassword = "";
    $dbName = "test_site";
     // Establish database connection
     $conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
    if (!$conn){
        die("Something went wrong!");
    }

?>