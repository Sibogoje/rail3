<?php
session_start();
include "database/conn.php";
require_once('tcpdf/tcpdf.php');

if (!empty($_POST["process"])) {
    $tnt=$_POST['house'];
    
    $result2 = mysqli_query($conn,"SELECT * FROM `house` WHERE `housecode`='$tnt'");

        if ($result2->num_rows > 0) {
          // output data of each row
        while($rows = mysqli_fetch_array($result2)) {

    $station = $rows['station'];
	$emeter = $rows['electricity_meter'];
	$wmeter = $rows['water_meter'];	
	$tt = $rows['tcode'];
	$housecode = $rows['housecode'];        	
        	

$result3 = mysqli_query($conn,"SELECT * FROM `tenant` WHERE `tcode`='$tt'");

while($rowt = mysqli_fetch_array($result3)) {
          
        	$tphone = $rowt['phone'];
        	$temail = $rowt['email'];
        	$taddress = $rowt['address'];
        	$housecodes = $rowt['housecode'];
        	
	
	
	//echo $wmeter;
}



$result4 = mysqli_query($conn,"SELECT * FROM `tenant_info` WHERE `t_code`='$tt'");

while($rowt4 = mysqli_fetch_array($result4)) {
            $tname = $rowt4['fullname'];

        	
	
	
	//echo $wmeter;
}


$tempsss = 'Jethro_Thwala';
$selectpayments = "SELECT balance, credit FROM `payments` WHERE `house`=?";
$stmt2 = $conn->prepare($selectpayments);
$stmt2->bind_param("s", $tnt);
$stmt2->execute();

// Get the result set as an associative array
$result2 = $stmt2->get_result();
$balancez = 0.00;
$creditz = 0.00;
$carryover = 0.00;
$carrybalance = 0.00;
if ($row2 = $result2->fetch_assoc()) {
	$balancez = 0 - $row2['balance'];
	$creditz = $row2['credit'];
	$carryover = $balancez + $creditz;
	$carrybalance = $row2['balance'];
} else {
	$balancez = 0.00;
	$creditz = 0.00;
}

if ($carryover < 0){
	$total = $total + $carrybalance	;
}else{
	$total = $total - $carryover;
}
}

$invoice_no = '';

// Create new PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document properties
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Invoice');
// Assuming $pdf is your TCPDF instance
$left_margin = 10; // Adjust as needed
$top_margin = 54;  // Adjust based on the height of your header. 
                   // Ensure it's sufficient to accommodate your header's content.
$right_margin = 10; // Adjust as needed


// Set header data
class CustomPDF extends TCPDF {


    private $invoice_no;
    public function __construct($invoice_no, $orientation = 'P', $unit = 'mm', $format = 'A4')
    {
        parent::__construct($orientation, $unit, $format);
        $this->invoice_no = $invoice_no;
    }
public function Header() {
    // Logo
    $image_url = 'https://www.liquag.com/dev/rail3/pri/logo2.PNG'; // Direct URL to the image
    $this->Image($image_url, 10, 5, 180, '', 'PNG', '', 'T', false, 10, '', false, false, 0, false, false, false);

    // Set a cell for the invoice number
    $invoice_number = '' . "\n" . $this->invoice_no; // Replace with your invoice number

    $this->SetFont('helvetica', 'B', 14); // Set the font to bold and size 12.

    $this->SetY(15);
    $this->SetX(10); // Set X to the desired position close to the left margin
    $this->MultiCell(0, 10, $invoice_number, 0, 'R', 0, 1, '', '', true);

    $image_file = 'https://www.liquag.com/dev/rail3/pri/tax3.jpg'; // Direct URL to the image
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

// Usage
//$pdf = new CustomPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// ... Rest of the TCPDF settings and content generation

$pdf = new CustomPDF($invoice_no, 'P', 'mm', 'A4');

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetMargins($left_margin, $top_margin, $right_margin);
// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Add a page
$pdf->AddPage();
$pdf->SetFont('', '', 9);
$html = '<table width="100%" style="padding: 2px;">';

$html .= 
'<tr>
<td style="border-top: 1px solid black; border-left: 1px solid black; font-weight: bold;">Client ID</td>
<td style="border-top: 1px solid black; ">'.$tnt.'</td>
<td style="border-top: 1px solid black; border-left: 1px solid black; font-weight: bold;">Station</td>
<td style="border-top: 1px solid black; border-right: 1px solid black;">'.$station.'</td>
</tr>';

$html .= 
'<tr>
<td style="border-left: 1px solid black; font-weight: bold;">Client Name</td>
<td>'.$tname.'</td>
<td style="border-left: 1px solid black; font-weight: bold;">Water Meter</td>
<td style="border-right: 1px solid black;">'.$wmeter.'</td>
</tr>';

$html .= 
'<tr>
<td style="border-left: 1px solid black; font-weight: bold;">House Number</td>
<td>'.$tnt.'</td>
<td style="border-left: 1px solid black; font-weight: bold;">Electricity Meter</td>
<td style="border-right: 1px solid black;">'.$emeter.'</td>
</tr>';

$html .= 
'<tr>
<td style="border-left: 1px solid black; font-weight: bold;">Phone</td>
<td></td>
<td style="border-left: 1px solid black; font-weight: bold;">Email</td>
<td style="border-right: 1px solid black;"></td>
</tr>';


$html .= 
'<tr>
<td style="border-bottom: 1px solid black; border-left: 1px solid black; font-weight: bold;">Address</td>
<td style="border-bottom: 1px solid black;"></td>
<td style="border-bottom: 1px solid black; border-left: 1px solid black; font-weight: bold;">Print Date</td>
<td style="border-bottom: 1px solid black; border-right: 1px solid black;"></td>
</tr>';

$html .= '</table>';

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->SetFont('', '', 9);
// Table 3
$html = '<table cellspacing="2" width="100%" style="font-size: 11px;">
            
        </table>';
$pdf->writeHTML($html, true, false, true, false, '');

// Text "Description of Readings"
$pdf->Ln(1);
$pdf->SetFont('', 'B', 12);
$pdf->Cell(0, 2, 'House Statement', 0, 1, 'L');
$pdf->SetFont('', 'B', 9);
// Table 4
$html = '<table cellspacing="3" width="100%" style="padding: 2px;">

        </table>';
$pdf->writeHTML($html, true, false, true, false, '');

// Text "Water"
$pdf->Ln(1);
$pdf->SetFont('', 'B', 12);

$paid = "";
// Table 5
$pdf->SetFont('', '', 9);
// Assuming you have a MySQLi connection named $conn
$result = mysqli_query($conn, "SELECT `invoicenumber`, `electricity_charge`, `water_charge`, `paid`, `billdate`  FROM invoices where house_code = '$tnt' order by invoicenumber desc");



$html = '<table cellspacing="" width="100%" style="padding: 3px; width: 100%; ">';
$html .= '<tr>
            <td style="border: 1px solid black; font-weight: bold; font-size: 11px;">Invoice Number</td>
             <td style="border: 1px solid black; font-weight: bold; font-size: 11px;">Bill date</td>
            <td style="border: 1px solid black; font-weight: bold; font-size: 11px;">Amount Owed</td>
            <td style="border: 1px solid black; font-weight: bold; font-size: 11px;">Amount Paid</td>
            <td style="border: 1px solid black; font-weight: bold; font-size: 11px;">Balance</td>
         </tr>';

// Loop through the results and add rows to the HTML table
while ($row = mysqli_fetch_assoc($result)) {
    
    
if ($row['paid'] == NULL || $row['paid'] == ""){
    $paid = 0.00;
}else{
    $paid = $row['paid'];
}

$owed     = $row['electricity_charge'] + $row['water_charge'];
$balance = $owed - $paid;
    
    
    $html .= '<tr>
                <td style="border: 1px solid black;">' . htmlspecialchars($row['invoicenumber']) . '</td>
                 <td style="border: 1px solid black;">' . htmlspecialchars($row['billdate']) . '</td>
                <td style="border: 1px solid black;">' . htmlspecialchars($owed) . '</td>
                <td style="border: 1px solid black;">' . htmlspecialchars($paid) . '</td>
                <td style="border: 1px solid black;">' . htmlspecialchars($balance) . '</td>
             </tr>';
}

$html .= '</table> <br> <br> <br> <br>';

$html .= '<table cellspacing="2" width="100%" style="padding: 3px; width: 100%;">
            <tr>
                <td colspan="2"><hr></td>
            </tr>
          </table>';
$pdf->writeHTML($html, true, false, true, false, '');




// Close and output PDF
$pdf->Output($tnt.'.pdf', 'I');

?>

<?php 
} else {
echo	"<script>alert('NO Tenant found');
window.location='house_profile.php';
</script>";
  echo "0 results";
} 
} else {  
echo	"<script>alert('There was an error!!'); window.location='house_profile.php'";
}
?>