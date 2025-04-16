<?php
session_start();
include "../database/conn.php";
require_once('../tcpdf/tcpdf.php');

if (!empty($_POST["prints"])) {

    $tnt=$_POST['tenanted'];
	$month=$_POST['mnm'];
	
$result1 = mysqli_query($conn,"SELECT * FROM `invoices` WHERE `house_code`='$tnt' AND `month`='$month' AND `id` LIKE '%-Wat%'    ");

if ($result1->num_rows > 0) {
  // output data of each row

while($rows = mysqli_fetch_array($result1)) {
	
 $tena =  $rows['tenant'];
                        $w_units =  $rows['w_units'];
						$invoice_no = $rows['invoicenumber'];
						$e_units =  $rows['e_units'];
						$electricity_charge =  $rows['electricity_charge'];
						$water_charge =  $rows['water_charge'];
						//$year =  $rows['year'];
							$year = "2025";
						$billdate =  $rows['billdate'];
						$house_code =  $rows['house_code'];
						 $prevwater =  $rows['prevwater'];
						  $prevelec =  $rows['prevelec'];
						   $currentelec =  $rows['currentelec'];
						   $currentwat =  $rows['currentwat'];
						   $steshi = $rows['station'];
						   
						   
						    $presewage = $rows['prev_sewage'];
						   $sewage_charge = $rows['sewage_charge'];
						   $curr_sewage = $rows['curr_sewage'];
						   
						   $reg = "";
						   if ($steshi == "Matsapha"){
						       $reg = "Manzini";
						   } else if ($steshi == "Mbabane"){
						       $reg = "Hhohho";
						   } else  if ($steshi == "Mhlume"){
						       $reg = "Lubombo";
						   } else  if ($steshi == "Mpaka"){
						       $reg = "Lubombo";
						   } else  if ($steshi == "Lavumisa"){
						       $reg = "Shiselweni";
						   } else  if ($steshi == "Nsoko"){
						       $reg = "Lubombo";
						   }else  if ($steshi == "Sidvokodvo"){
						       $reg = "Manzini";
						   }
						   
						   
// Initialize counters for each band
$band1 = $band2 = $band3 = $band4 = 0;

// Determine the band based on w_units
if ($w_units >= 0 && $w_units <= 10) {
    $band1 = $w_units; // All units are in band 1
} elseif ($w_units >= 11 && $w_units <= 15) {
    $band2 = $w_units - 10; // Units in band 2
} elseif ($w_units >= 16 && $w_units <= 50) {
    $band3 = $w_units - 15; // Units in band 3
} elseif ($w_units > 50) {
    $band4 = $w_units - 50; // Units in band 4
}						  
						   
$B1 = $B2 = $B3 = $B4 = 0.0;
$SB1 = $SB2 = $SB3 = $SB4 = 0.0;
$temp0 = $temp1 = $temp2 = $temp3 = 0;
$tempa0 = $tempa1 = $tempa2 = $tempa3 = 0;
$w = $vw = $bn = 0.0;

$Unit1 = $Unit2  = $Unit3 = $Unit4 = 0;	
$Unit = 0;
$subtotal = 0;

if ($w_units < 11) {
    $B1 = 84.16;
    $subtotal = $B1 + 97.63;
    if ($w_units > 0) {
    $Unit1 = min(10, $w_units);
}
} elseif ($w_units < 16) {
    $temp0 = 10;
    $B1 = 84.16;
    $RR = $w_units;
    $temp1 = $RR - $temp0;
    $B2 = $temp1 * 21.92; 
    $subtotal = $B1 + $B2 + 97.63;
    if ($w_units > 10) {
        $Unit1 = 10;
    $Unit2 = min(5, $w_units - 10);
}
} elseif ($w_units < 51) {
    $temp0 = 10;
    $B1 = 84.16;
    $temp1 = 5;
    $B2 = $temp1 * 21.92;
    $temp2 = $w_units - 15;
    $B3 = $temp2 * 33.01; 
    $RR = $w_units;
    $RR = $RR - $temp0;
    $RR = $RR - $temp1;
    $subtotal = $B1 + $B2 + $B3 + 97.63;
    if ($w_units > 15) {
        $Unit1 = 10;
        $Unit2 = 5;
    $Unit3 = min(35, $w_units - 15);
}
} elseif ($w_units >= 51) {
    $temp0 = 10;
    $B1 = 84.16;
    $temp1 = 5;
    $B2 = $temp1 * 21.92;
    $temp2 = 35;
    $B3 = $temp2 * 33.01; 
    $RR = $w_units;
    $RR = $RR - $temp0;
    $RR = $RR - $temp1;
    $RR = $RR - $temp2;
    $subtotal = $B1 + $B2 + $B3 + $RR * 37.67 + 97.63;
    
    if ($w_units > 50) {
        $Unit1 = 10;
        $Unit2 = 5;
        $Unit3 = 35;
    $Unit4 = $w_units - 50;
    $charge = $Unit4 * 37.67;
    
}
}


$total = $subtotal + $w;



$monthDays = [
    "January" => 31,
    "February" => 28, // You might want to consider leap years
    "March" => 31,
    "April" => 30,
    "May" => 31,
    "June" => 30,
    "July" => 31,
    "August" => 31,
    "September" => 30,
    "October" => 31,
    "November" => 30,
    "December" => 31
];

$nextMonth = [
    "January" => "February",
    "February" => "March",
    "March" => "April",
    "April" => "May",
    "May" => "June",
    "June" => "July",
    "July" => "August",
    "August" => "September",
    "September" => "October",
    "October" => "November",
    "November" => "December",
    "December" => "January"
];

$days = $monthDays[$month] ?? "";
$due = $nextMonth[$month] ?? "";


$result2 = mysqli_query($conn,"SELECT * FROM `tenant` WHERE `tcode`='$tnt'");

while($rowt = mysqli_fetch_array($result2)) {
	$tname = $rowt['name'];
	$phone = $rowt['phone'];
	$email = $rowt['email'];
	$address = $rowt['address'];
	$housecode = $rowt['housecode'];
	
	
}

$result3 = mysqli_query($conn,"SELECT * FROM `house` WHERE `housecode`='$tnt'");

while($rows = mysqli_fetch_array($result3)) {
	$station = $rows['station'];
	$emeter = $rows['electricity_meter'];
	$wmeter = $rows['water_meter'];	
	$tt = $rows['tcode'];	
	
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


// Query to sum all invoices for the current year for the tenant
$currentYear = date("Y");
$sumInvoicesQuery = "SELECT SUM(water_charge +  sewage_charge) AS total_charges 
                     FROM `invoices` 
                     WHERE `house_code` = ? AND `year` = ?";
$stmt3 = $conn->prepare($sumInvoicesQuery);
$stmt3->bind_param("ss", $tnt, $currentYear);
$stmt3->execute();
$result3 = $stmt3->get_result();

if ($row3 = $result3->fetch_assoc()) {
    $carryover = $row3['total_charges'] ?? 0.00;
}

// Fetch the amount paid by the tenant
$paid = 0.00;
$paidQuery = "SELECT SUM(amount) AS total_paid FROM `paid` WHERE `tenant` = ?";
$stmt4 = $conn->prepare($paidQuery);
$stmt4->bind_param("s", $tena);
$stmt4->execute();
$result4 = $stmt4->get_result();

if ($row4 = $result4->fetch_assoc()) {
    $paid = $row4['total_paid'] ?? 0.00;
}


}



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
    $image_url = 'https://www.grinpath.com/rail/pri/logo2.PNG'; // Direct URL to the image
    $this->Image($image_url, 10, 5, 180, '', 'PNG', '', 'T', false, 10, '', false, false, 0, false, false, false);

    // Set a cell for the invoice number
    $invoice_number = 'Invoice Number' . "\n" . $this->invoice_no; // Replace with your invoice number

    $this->SetFont('helvetica', 'B', 14); // Set the font to bold and size 12.

    $this->SetY(15);
    $this->SetX(10); // Set X to the desired position close to the left margin
    $this->MultiCell(0, 10, $invoice_number, 0, 'R', 0, 1, '', '', true);

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
$html = '<table width="100%" style="padding: 1px;">';

$html .= 
'<tr>
<td style="border-top: 1px solid black; border-left: 1px solid black; font-weight: bold;">Client ID</td>
<td style="border-top: 1px solid black; ">'.$tnt.'</td>
<td style="border-top: 1px solid black; border-left: 1px solid black; font-weight: bold;">Region</td>
<td style="border-top: 1px solid black; border-right: 1px solid black;">'.$reg.'</td>
</tr>';

$html .= 
'<tr>
<td style="border-left: 1px solid black; font-weight: bold;">Client Name</td>
<td>'.$tt.'</td>
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
<td style="border-left: 1px solid black; font-weight: bold;">W/Order No</td>
<td style="border-right: 1px solid black;"></td>
</tr>';

$html .= 
'<tr>
<td style="border-left: 1px solid black; font-weight: bold;">Email</td>
<td></td>
<td style="border-left: 1px solid black; font-weight: bold;">S/Order No</td>
<td style="border-right: 1px solid black;"></td>
</tr>';

$html .= 
'<tr>
<td style="border-bottom: 1px solid black; border-left: 1px solid black; font-weight: bold;">Address</td>
<td style="border-bottom: 1px solid black;"></td>
<td style="border-bottom: 1px solid black; border-left: 1px solid black; font-weight: bold;">Bill Date</td>
<td style="border-bottom: 1px solid black; border-right: 1px solid black;">'.$billdate.'</td>
</tr>';

$html .= '</table>';

$pdf->writeHTML($html, true, false, true, false, '');
//$yr = date('Y');
$yr = "2025";
$pdf->SetFont('', '', 9);
// Table 3
$html = '<table cellspacing="2" width="100%" style="font-size: 11px;">
            <tr>
                <th style="border: 1px solid black; font-weight: bold;">House No</th>
                <th style="border: 1px solid black; font-weight: bold;">Station</th>
                <th style="border: 1px solid black; font-weight: bold;">Bill Month</th>
                <th style="border: 1px solid black; font-weight: bold;">Payment Due</th>
                <th style="border: 1px solid black; font-weight: bold;">Start Date</th>
                <th style="border: 1px solid black; font-weight: bold;">End Date</th>
            </tr>
            <tr>
            <td style="border: 1px solid black;">'.$house_code.'</td>
            <td style="border: 1px solid black;">'.$steshi.'</td>
            <td style="border: 1px solid black;">'.$month.'</td>
            <td style="border: 1px solid black;">'.$due.'-'.$yr.'</td>
            <td style="border: 1px solid black;">'.$month.'-'.$year.'</td>
            <td style="border: 1px solid black;">'.$days.' -'.$shortMonth = substr($month, 0, 3).'-'.$year.'</td>
            </tr>
        </table>';
$pdf->writeHTML($html, true, false, true, false, '');

// Text "Description of Readings"
$pdf->Ln(1);
$pdf->SetFont('', 'B', 12);
$pdf->Cell(0, 5, 'Description of Readings', 0, 1, 'L');
$pdf->SetFont('', 'B', 9);
// Table 4
$html = '<table cellspacing="2" width="100%" style="padding: 1px;">
            <tr>
                <td style="border: 1px solid black; ">Quantity</td>
                <td style="border: 1px solid black; ">Actual Units</td>
                <td style="border: 1px solid black; ">Previous Units</td>
                <td style="border: 1px solid black; ">Calculation</td>
                <td style="border: 1px solid black; ">Tarrif</td>
                <td style="border: 1px solid black; ">Unit Price</td>
                <td style="border: 1px solid black; ">Total</td>
            </tr>
        </table>';
$pdf->writeHTML($html, true, false, true, false, '');

// Text "Water"
$pdf->Ln(1);
$pdf->SetFont('', 'B', 12);
$pdf->Cell(0, 1, 'Water', 0, 1, 'L');


$BASIC_CHARGE = number_format(97.63 * 1.04, 2);
// Table 5
$pdf->SetFont('', '', 9);
$html = '<table cellspacing="2" width="100%" style="padding: 1px;  width: 100%;  ">';
$html .= '<tr>
<td style="width: 7%; border: 1px solid black;"></td>
<td style="width: 7%; border: 1px solid black;"></td>
<td colspan="2" style="width: 28.58%; border: 1px solid black; font-weight: bold;">Basic Charge per Month</td>
<td style="width: 14.28%; border: 1px solid black;">0</td>
<td style="width: 14.28%; border: 1px solid black;">'.$BASIC_CHARGE.'</td>
<td style="width: 14.28%; border: 1px solid black;">'.$BASIC_CHARGE.'</td>
<td style="width: 14.32%; border: 1px solid black;">E '.$BASIC_CHARGE.'</td>
</tr>';



$html .= '<tr>
<td></td>
<td></td>
<td style="border: 1px solid black;">'.$currentwat.'</td>
<td style="border: 1px solid black;">'.$prevwater.'</td>
<td style="border: 1px solid black;">'.$w_units.'</td>
<td></td>
<td></td>
<td></td>
</tr>';

$html .= '<tr>
<td></td>
<td></td>
<td colspan="2" style="border: 1px solid black; font-weight: bold;">Charges/Metre Cubic (Kilolitre)</td>
<td style="border: 1px solid black;"></td>
<td></td>
<td></td>
<td></td>
</tr>';
$FLAT_RATE_1_10 = number_format(84.16 * 1.04, 2);
$html .= '<tr>
<td style="border: 1px solid black;"></td>
<td style="border: 1px solid black;">10</td>
<td style="border: 1px solid black; font-weight: bold;">B1(1-10)</td>
<td style="border: 1px solid black;">'.$Unit1.'</td>
<td style="border: 1px solid black;">'.$FLAT_RATE_1_10.'</td>
<td style="border: 1px solid black;">'.$FLAT_RATE_1_10.'</td>
<td style="border: 1px solid black;">'.$FLAT_RATE_1_10.'</td>
<td style="border: 1px solid black;">'.$FLAT_RATE_1_10.'</td>
</tr>';
$RATE_11_15 = number_format(21.92 * 1.04, 2);
$B2Cal =  number_format($RATE_11_15 * $Unit2, 2);
$html .= '<tr>
<td style="border: 1px solid black;"></td>
<td style="border: 1px solid black;">5</td>
<td style="border: 1px solid black; font-weight: bold;">B2(11-15)</td>
<td style="border: 1px solid black;">'.$Unit2.'</td>
<td style="border: 1px solid black;">'.$B2Cal.'</td>
<td style="border: 1px solid black;">'.$RATE_11_15.'</td>
<td style="border: 1px solid black;">'.$RATE_11_15.'</td>
<td style="border: 1px solid black;">'.$B2Cal .'</td>
</tr>';
$RATE_16_50 = number_format(33.01 * 1.04, 2);
$B3Cal = number_format($RATE_16_50 * $Unit3, 2);
$html .= '<tr>
<td style="border: 1px solid black;"></td>
<td style="border: 1px solid black;">34</td>
<td style="border: 1px solid black; font-weight: bold;">B3(16-50)</td>
<td style="border: 1px solid black;">'.$Unit3.'</td>
<td style="border: 1px solid black;">'.$B3Cal.'</td>
<td style="border: 1px solid black;">'.$RATE_16_50.'</td>
<td style="border: 1px solid black;">'.$RATE_16_50.'</td>
<td style="border: 1px solid black;">'.$B3Cal.'</td>
</tr>';
$RATE_ABOVE_50 = number_format(37.67 * 1.04, 2);
$B4Cal = number_format($RATE_ABOVE_50 * $Unit4, 2);
$html .= '<tr>
<td style="border: 1px solid black;"></td>
<td style="border: 1px solid black;">&gt;50</td>
<td style="border: 1px solid black; font-weight: bold;">B4(&gt;50)</td>
<td style="border: 1px solid black;">'.$Unit4.'</td>
<td style="border: 1px solid black;">'.$B4Cal.'</td>
<td style="border: 1px solid black;">'.$RATE_ABOVE_50.'</td>
<td style="border: 1px solid black;">'.$RATE_ABOVE_50.'</td>
<td style="border: 1px solid black;">'.$B4Cal.'</td>
</tr>';


$html .= '<tr>
<td colspan="6" style="border: none; font-weight: bold; font-size: 12px;"></td>


<td style="border: 1px solid black; font-weight: bold; ">Subtotal</td>
<td style="border: 1px solid black;">'.$water_charge.'</td>
</tr>';



$html .= '<tr>
<td colspan="8" style="border: none; font-weight: bold; font-size: 12px;">Sewage</td>
</tr>';

$html .= '<tr>
<td style="width: 7%; border: 1px solid black;"></td>
<td style="width: 7%; border: 1px solid black;"></td>
<td colspan="2" style="width: 28.58%; border: 1px solid black; font-weight: bold;">Basic Charge per Month</td>
<td style="width: 14.28%; border: 1px solid black;">0</td>
<td style="width: 14.28%; border: 1px solid black;">0</td>
<td style="width: 14.28%; border: 1px solid black;">13.73</td>
<td style="width: 14.32%; border: 1px solid black;">E 80.68</td>
</tr>';

$html .= '<tr>
<td></td>
<td></td>
<td style="border: 1px solid black;">'.$presewage.'</td>
<td style="border: 1px solid black;">'.$presewage - $curr_sewage.'</td>
<td style="border: 1px solid black;">'. $curr_sewage.'</td>
<td></td>
<td></td>
<td></td>
</tr>';

$html .= '<tr>
<td style="border: 1px solid black;"></td>
<td style="border: 1px solid black;"></td>
<td style="border: 1px solid black; font-weight: bold;">B1 (0 - 11.12)</td>
<td style="border: 1px solid black;">'.min($curr_sewage, 11.12).'</td>
<td style="border: 1px solid black;"></td>
<td style="border: 1px solid black;"></td>
<td style="border: 1px solid black;"></td>
<td style="border: 1px solid black;">E  80.68</td>
</tr>';




$html .= '<tr>
<td style="border: 1px solid black;"></td>
<td style="border: 1px solid black;">'.$presewage - $curr_sewage.'</td>
<td style="border: 1px solid black; font-weight: bold;">Above 11.12</td>
<td style="border: 1px solid black;">'.max(0, $curr_sewage - 11.12).'</td>
<td style="border: 1px solid black;"></td>
<td style="border: 1px solid black;">13.73</td>
<td style="border: 1px solid black;">13.73</td>
<td style="border: 1px solid black;">E  '.number_format($sewage_charge, 2).'</td>
</tr>';

$html .= '<tr>
<td colspan="6" style="border: none;"></td>
<td style="border: 1px solid black; font-weight: bold;">Subtotal</td>
<td style="border: 1px solid black;">E  '.number_format($sewage_charge, 2).'</td>
</tr>';


$html .= '<tr>
<td colspan="8" style="border: none;"></td>
</tr>';


$html .= '<tr>
<td colspan="8" style="border: none;"></td>
</tr>';




$html .= '<tr>
<td colspan="2" style="font-weight: bold;">Name </td>
<td></td>
<td>Eswatini Railway</td>
<td></td>
<td></td>
<td style="border: 1px solid black; font-weight: bold;">'.$month.' Total</td>
<td style="border: 1px solid black;">E '.$water_charge+$sewage_charge.'</td>
</tr>';

$html .= '<tr>
<td colspan="3" style="font-weight: bold;" >Payment Details </td>
<td>Internet Transfer</td>
<td></td>
<td></td>
<td style="border: 1px solid black; font-weight: bold;">'.$year.' Total</td>
<td style="border: 1px solid black;">E '.$carryover.' </td>
</tr>';

$html .= '<tr>
<td colspan="2" style="font-weight: bold;">CC# </td>
<td></td>
<td></td>
<td></td>
<td></td>
<td style="border: 1px solid black; font-weight: bold;">Amount Paid</td>
<td style="border: 1px solid black; font-weight: bold;">E '.$paid.'</td>
</tr>';

$html .= '<tr>
<td style="border: none;"></td>
<td style="border: none;"></td>
<td style="border: none;"></td>
<td style="border: none;"></td>
<td style="border: none;"></td>
<td style="border: none;"></td>
<td style="border: 1px solid black; font-weight: bold;">Balance '.$year.'</td>
<td style="border: 1px solid black; font-weight: bold;">E '.$carryover - $paid.'</td>
</tr>';




$html .= '</table>';
$pdf->writeHTML($html, true, false, true, false, '');


// Close and output PDF
$pdf->Output($tnt.'.pdf', 'I');

?>

<?php 
} else {
echo	"<script>alert('There seems to be problem with selected Query, Please match House with right Month');
window.location='../billing.php';
</script>";
  echo "0 results";
} 
} else {  
echo	"<script>alert('There was an error!!'); window.location='../billing.php'";
}
?>