<?php
require_once(__DIR__ . '/TCPDF-main/tcpdf.php');

// Kunin ang reservation details mula sa GET parameters
$customerName = $_GET['customerName'] ?? 'None';
$eventType = $_GET['eventType'] ?? 'None';
$others = $_GET['others'] ?? 'None';
$eventPlace = $_GET['eventPlace'] ?? 'None';
$photoSize = $_GET['photoSize'] ?? 'None';
$Email = $_GET['Email'] ?? 'None';  // ✅ Dapat capital E kung ganito sa JavaScript
$contactNumber = $_GET['contactNumber'] ?? 'None';
$startTime = $_GET['startTime'] ?? 'None';
$endTime = $_GET['endTime'] ?? 'None';
$status = $_GET['status'] ?? 'None';

// Gumawa ng bagong PDF document
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('PM&JI Reservify');
$pdf->SetTitle('Reservation Details');
$pdf->SetHeaderData('', 0, 'Reservation Details', "PM&JI Reservify");

// Set margins
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(TRUE, 15);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Content ng PDF
$html = "
<h2>Reservation Details</h2>
<table border='1' cellspacing='3' cellpadding='5'>
    <tr><td><strong>Customer Name:</strong></td><td>{$customerName}</td></tr>
    <tr><td><strong>Event Type:</strong></td><td>{$eventType}</td></tr>
    <tr><td><strong>Others:</strong></td><td>{$others}</td></tr>
    <tr><td><strong>Event Place:</strong></td><td>{$eventPlace}</td></tr>
    <tr><td><strong>Photo Size/Layout:</strong></td><td>{$photoSize}</td></tr>
    <tr><td><strong>Email:</strong></td><td>{$Email}</td></tr>
    <tr><td><strong>Contact Number:</strong></td><td>{$contactNumber}</td></tr>
    <tr><td><strong>Start Time:</strong></td><td>{$startTime}</td></tr>
    <tr><td><strong>End Time:</strong></td><td>{$endTime}</td></tr>
    <tr><td><strong>Status:</strong></td><td>{$status}</td></tr>
</table>";

// Output PDF
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('Reservation_Details.pdf', 'I'); // 'I' para i-display sa browser
