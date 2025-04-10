<?php
session_start();
include "../database/conn.php";
require_once('../tcpdf/tcpdf.php');

$tenant = $_GET['tenant'];

$data = mysqli_query($conn, "SELECT 
  `month`, 
  SUM(`water_charge` +  `sewage_charge`) AS `total_amount`,
  SUM(`paid`) AS `total_paid`
FROM 
  `invoices`
WHERE 
  `tenant` = '$tenant' 
  
  AND w_units > 0
  AND `year` = YEAR(CURDATE())
GROUP BY 
  `month`
ORDER BY 
  FIELD(`month`, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
");

if ($data->num_rows > 0) {
    // Create new PDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set document properties
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Water Invoice Breakdown');

    // Set header data
    class CustomPDF extends TCPDF {
        public function Header() {
            // Logo
            $image_url = 'https://www.grinpath.com/rail/pri/logo2.PNG'; // Direct URL to the image
            $this->Image($image_url, 10, 5, 190, '', 'PNG', '', 'T', false, 10, '', false, false, 0, false, false, false);

            $this->SetFont('helvetica', 'B', 14); // Set the font to bold and size 12.
            $this->SetY(15);
            $this->SetX(10); // Set X to the desired position close to the left margin

            $image_file = 'https://www.grinpath.com/rail/pri/tax3.jpg'; // Direct URL to the image
            $this->Image($image_file, 10, 40, 190, '', 'JPG', '', 'T', false, 10, '', false, false, 0, false, false, false);
        }

        public function Footer() {
            // Position at 15 mm from bottom
            $this->SetY(-15);
            // Set font
            $this->SetFont('helvetica', '', 8);
            // Page number
            $this->Cell(0, 10, 'ISO Standard Compliant', 0, false, 'C', 0, '', 0, false, 'T', 'M');
            // Set font
            $this->SetFont('helvetica', 'I', 8);
            $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        }
    }

    $pdf = new CustomPDF('P', 'mm', 'A4');

    // Set margins
    $pdf->SetMargins(10, 54, 10);
    // Set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // Add a page
    $pdf->AddPage();

    // Set font for the heading
    $pdf->SetFont('helvetica', 'B', 14);
    $currentYear = date('Y');
    $pdf->Cell(0, 10, 'Water and Sewage Invoices Breakdown for ' . $tenant . ' - ' . $currentYear, 0, 1, 'C');

    // Set font for the table
    $pdf->SetFont('helvetica', '', 12);

    // Table header
    $html = '<table cellspacing="0" cellpadding="4" border="1">
                <tr>
                    <th style="font-weight: bold;">Month</th>
                    <th style="font-weight: bold;">Amount Due (E)</th>
                    <th style="font-weight: bold;">Paid (E)</th>
                </tr>';

    // Fetch data and populate table
    $totalAmount = 0;
    $totalPaid = 0;
    while ($row = mysqli_fetch_assoc($data)) {
        $html .= '<tr>
                    <td>' . $row['month'] . '</td>
                    <td>' . number_format($row['total_amount'], 2) . '</td>
                    <td></td>
                  </tr>';
        $totalAmount += $row['total_amount'];
        $totalPaid += $row['total_paid'];
    }

    // Add total row
    $html .= '<tr>
                <td style="font-weight: bold;">Total</td>
                <td style="font-weight: bold;">' . number_format($totalAmount, 2) . '</td>
                <td style="font-weight: bold;"></td>
              </tr>';

    $html .= '</table>';

    // Output the table
    $pdf->writeHTML($html, true, false, true, false, '');

    // Close and output PDF
    $pdf->Output($tenant . '_water_invoice_breakdown.pdf', 'I');
} else {
    echo "<script>alert('No data found for the selected tenant.');
    window.location='../reports.php';
    </script>";
}
?>