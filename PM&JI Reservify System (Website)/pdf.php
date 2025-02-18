<?php
require_once(__DIR__ . '/TCPDF-main/tcpdf.php');

// Extend TCPDF with custom functions
class MYPDF extends TCPDF {

    // Add a header with a logo and title
    public function Header() {
        // Get page width
        $pageWidth = $this->GetPageWidth();

        $logoFile = 'images/reservify_logo.jpg'; // Use the new JPG version
        $this->Image($logoFile, 10, 10, 50); // Adjust size as needed

        // Title
        $this->SetFont('helvetica', 'B', 14);
        $this->Ln(10); // Adjust line break after the logo
        $this->Cell(0, 10, 'PM&JI Reservify Transaction Record', 0, 1, 'C');
        $this->Ln(5); // Space before table
    }

    // Load table data from database (with filtering by month)
    public function LoadData($selectedMonth = '') {
        include 'database.php'; // Ensure this connects to DB properly
        $query = "SELECT * FROM payment";

        // Apply filter if a month is selected
        if (!empty($selectedMonth)) {
            $query .= " WHERE DATE_FORMAT(created_at, '%m') = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $selectedMonth);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $conn->query($query);
        }

        $data = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    // Colored table
    public function ColoredTable($header, $data) {
        // Table column widths
        $w = array(30, 29, 37, 37, 30, 30);
        $tableWidth = array_sum($w);
        $pageWidth = $this->GetPageWidth();
        $xStart = ($pageWidth - $tableWidth) / 2; // Calculate center position

        // Set starting position
        $this->SetX($xStart);

        // Colors, line width, and bold font
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');

        // Header
        for ($i = 0; $i < count($header); ++$i) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();

        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');

        // Data
        $fill = 0;
        if (!empty($data) && is_array($data)) {
            foreach ($data as $row) {
                $this->SetX($xStart); // Ensure each row starts at the same position

                // Handle Amount (Ensure correct case and formatting)
                $amount = $row['Amount'] ?? $row['amount'] ?? 'N/A';
                $formattedAmount = is_numeric($amount) ? number_format((float) $amount, 2) : $amount;

                $this->Cell($w[0], 6, $formattedAmount, 'LR', 0, 'C', $fill);
                $this->Cell($w[1], 6, $row['ref_no'] ?? 'N/A', 'LR', 0, 'L', $fill);
                $this->Cell($w[2], 6, $row['payment_method'] ?? 'N/A', 'LR', 0, 'L', $fill);
                $this->Cell($w[3], 6, $row['payment_type'] ?? 'N/A', 'LR', 0, 'L', $fill);
                $this->Cell($w[4], 6, $row['status'] ?? 'N/A', 'LR', 0, 'C', $fill);

                // Format created_at (Short Date: YYYY-MM-DD)
                $formattedDate = !empty($row['created_at']) ? date('Y-m-d', strtotime($row['created_at'])) : 'N/A';
                $this->Cell($w[5], 6, $formattedDate, 'LR', 0, 'C', $fill);

                $this->Ln();
                $fill = !$fill;
            }
            $this->SetX($xStart);
            $this->Cell($tableWidth, 0, '', 'T');
        } else {
            $this->SetX($xStart);
            $this->Cell($tableWidth, 6, 'No payment records found.', 1, 0, 'C', 1);
        }
    }
}

// Get selected month from GET or POST request
$selectedMonth = isset($_GET['month']) ? $_GET['month'] : '';

// Create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('PM&JI Reservify');
$pdf->SetTitle('Payment Records');
$pdf->SetSubject('Payment Report');
$pdf->SetKeywords('TCPDF, PDF, payment, report');

// Set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Set font
$pdf->SetFont('helvetica', '', 12);

// Add a page
$pdf->AddPage();

// Column titles
$header = array('Amount', 'Ref_No', 'Payment Method', 'Payment Type', 'Status', 'Created At');

// Load payment data from database (filtered by month)
$data = $pdf->LoadData($selectedMonth);

// Print colored table
$pdf->ColoredTable($header, $data);

// Output PDF
$pdf->Output('payment_report.pdf', 'I');

?>
