<?php
require('../fpdf/fpdf.php');

$pdf = new FPDF('P', 'mm', 'A4');  // Portrait orientation, millimeters, A4 size
$pdf->AddPage();

// Header
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'ESWATINI RAILWAY', 0, 1, 'C');

// Client Info
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Client ID: 189A', 0, 1);
$pdf->Cell(0, 10, 'Client: Sivdokvdo Manzini M216 Eswatini', 0, 1);
// ... Add other fields similarly ...

// Bill Details
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Description Meter Readings', 0, 1, 'C');

// Table Headers
$pdf->SetFont('Arial', 'B', 12);
$colWidth = 30;  // Set a constant column width for the example
$headerTexts = ['Qty', 'Actual', 'Previous Units', 'Calculation', 'Tariff', 'Unit Price', 'Total'];

foreach ($headerTexts as $headerText) {
    $pdf->Cell($colWidth, 10, $headerText, 1, 0, 'C');
}
$pdf->Ln();

// Table Data
$pdf->SetFont('Arial', '', 12);
$data = [
    ['1', 'Basic Charge', '1418', '0', '82.62', 'E82.62', 'E82.62'],
    // ... Add other rows similarly ...
];

foreach ($data as $row) {
    foreach ($row as $cell) {
        $pdf->Cell($colWidth, 10, $cell, 1, 0, 'C');
    }
    $pdf->Ln();
}

// Set the right margin
$pdf->SetRightMargin(10);

$pdf->Output();
?>
