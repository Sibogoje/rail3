<?php
session_start();
include "../database/conn.php";

if (!empty($_POST["prints"])) {

    $tnt=$_POST['tenanted'];
	$month=$_POST['mnm'];
	

$result1 = mysqli_query($conn,"SELECT * FROM `invoices` WHERE `house_code`='$tnt' AND `month`='$month' AND `id`NOT LIKE '%-Wat%' AND `id`NOT LIKE '%-Elec%'    ");



if ($result1->num_rows > 0) {
  // output data of each row


while($rows = mysqli_fetch_array($result1)) {
	
 $tena =  $rows['tenant'];
                        $w_units =  $rows['w_units'];
						$dfdf = $rows['invoicenumber'];
						$e_units =  $rows['e_units'];
						$electricity_charge =  $rows['electricity_charge'];
						$water_charge =  $rows['water_charge'];
						$year =  $rows['year'];
						$billdate =  $rows['billdate'];
						$house_code =  $rows['house_code'];
						 $prevwater =  $rows['prevwater'];
						  $prevelec =  $rows['prevelec'];
						   $currentelec =  $rows['currentelec'];
						   $currentwat =  $rows['currentwat'];
						   $steshi = $rows['station'];
						   
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
						   
						   
						  
						   
						   $B1 = 00.0;
						    $B2 = 00.0;
							 $B3 = 00.0;
							  $B4 = 00.0;
							  
							  $SB1 = 0.00;
							  $SB2 = 0.00;
							  $SB3 = 0.00;
							 $SB4 = 0.00;
							 $temp0 = 0;
							 $temp1 = 0;
							 $temp2 = 0;
							 $temp3 = 0;
							 
							 $tempa0 = 0;
							 $tempa1 = 0;
							 $tempa2 = 0;
							 $tempa3 = 0;
							 
							 $w = 0.0;
                             $vw = 0;
                             $bn = 0;
							 
							 
if ($e_units == 0){
	
	$w = 0.0;
	$vw = 0;
	 $bn = 0;
	
}else if ($e_units<101 && $e_units>0){
	$w = $electricity_charge;
	 $bn = 180.00;
	
}else{
	
	$vw = 	($e_units - 100) * 1.8;
	$w = $vw + 180;
	$bn = 180.00;
	
}



							 
							 
if ($w_units == 0.00){
    	
	$subtotal = 0;
	$total = $subtotal + $w;
	
	
		
}else if ($w_units < 11){
	
	$temp0 = $w_units;	

	


    $B1 = 80.92;
	
	$subtotal = $B1 + 93.88;
	$total = $subtotal + $w;	
	

}else if ($w_units > 10 && $w_units < 16){
	$temp0 = 10;
	$B1 = 80.92;
	
$RR = $w_units;
$temp1 = $RR - $temp0;

	$B2 = $temp1 * 21.08; 
	
	$subtotal = $B1 + $B2 + 93.88;
	$total = $subtotal + $w;
	
	
	
}else if ($w_units > 15 && $w_units < 51  ){
	
	$temp0 = 10;
	$B1 = 80.92;
	
	
	$temp1 = 5;
	$B2 = $temp1 * 21.08;
	
	$temp2 = $w_units - 15;
	$B3 = $temp2 * 31.74; 
	
	
	
$RR = $w_units;

$RR = $RR - $temp0;
$RR = $RR - $temp1;

	$subtotal = $B1 + $B2 + $B3 + 93.88;
	$total = $subtotal + $w;
    
    
}else if ($w_units == 0.00){
   	$subtotal = 0;
	$total = $subtotal + $w;
}else {
	
	$temp0 = 10;
	$B1 = 80.92;
	
	
	$temp1 = 5;
	$B2 = $temp1 * 21.08;
	
	$temp2 = 34;
	$B3 = $temp2 * 31.74; 
	
	
	
$RR = $w_units;

$RR = $RR - $temp0;
$RR = $RR - $temp1;
$temp3 = $RR - $temp2;

	
//	$temp3 = $electricity_units - 50;
	$B4 = $temp3 * 35.22;    
	
	$subtotal = $B1 + $B2 + $B3 + $B4 + 93.88;
	$total = $subtotal + $w;
    
}


$days = "";
	$due = "";
if ($month == ""){
	
}else if ($month == "January"){
	$days = 31;
		$due = "February";
	
}else if ($month == "February"){
	$days = 28;
	$due = "March";
	
}else if ($month == "March"){
	$days = 31;
		$due = "April";
	
}else if ($month == "April"){
	$days = 30;
		$due = "May";
	
}else if ($month == "May"){
	$days = 31;
		$due = "June";
	
}else if ($month == "June"){
	$days = 30;
		$due = "July";
	
}else if ($month == "July"){
	$days = 31;
		$due = "August";
	
}else if ($month == "August"){
	$days = 31;
		$due = "September";
	
}else if ($month == "September"){
	$days = 30;
		$due = "October";
	
}else if ($month == "October"){
	$days = 31;
		$due = "November";
	
}else if ($month == "November"){
	$days = 30;
		$due = "December";
	
}else if ($month == "December"){
	$days = 31;
		$due = "January";
	
}


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
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title><?php echo $tnt." ".$month; ?></title>
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>

   <style>
        @media print {
            body {
                font-size: 10px;
                margin: 0;
                padding: 0;
            }
        }

        .table {
            width: 100%;
            font-family:'Arial';
    border-collapse: separate;
  border-spacing: 1x;
  *border-collapse: expression('separate', cellSpacing='1px'); 

        }

        td {
            border: 1px solid #ccc;
        }     

        @page {
            margin: 0;
        }

        body {
            padding-top: 72px;
            padding-bottom: 72px;
        }

        .container {
            width: 100%;
            height: 100%;
        }

        .table img {
            width: 100%;
            height: auto;
        }

        h2 {
            font-weight: bold;
            font-size: 10px;
        }

        h4 {
            font-size: 12px;
        }

        .table-responsive {
            margin-left: 10px;
            margin-right: 10px;
        }

        .table th,
        .table td {
            font-size: 10px;
            padding: 2px;
        }

        .tbody td {
            border: 1;
        }

    </style>


</head>

<body style="">


<div class="container"  style="width: 100% height: 100%">
<!-- button to print -->
<div class="row">
    <div class="col-md-12">
        <div class="pull-right">
            <button class="btn btn-primary" onclick="convertAndDownload()">Print</button>
        </div>
    </div>
</div>

<script>
    function convertAndDownload() {
        $.post("conv.php", function(data) {
            if (data.status === "success") {
                window.location.href = data.file;  // This will initiate a download
            } else {
                alert("Error: " + data.message);
            }
        }, "json");
    }
</script>

<!-- end button to print -->



    <div class="table" style="margin-right: 10px;margin-left: 10px;">
        <table class="table">
            <thead>
                <tr></tr>
            </thead>
            <tbody>
			 <tr>
				    
                    <td colspan="" style="width: 20%;text-align: left;font-size: 18px; border: 0px;">
					<img class="img-responsive" style="width: 100%; height: 157px;" src="logo.PNG">
					</td>
					 <td style="width: 20%;text-align: left;font-size: 18px; border: 0px;"><h2 style="font-weight: bold;" >ESWATINI RAILWAY</h2>
					 <h4>Postal Office Box 475
					 <br>Eswatini Railway Building, Dzeliwe Street Mbabane
					 <br>00268 2411 7400 Fax 00268 2411 7400</h4></td>
                     <td style="border-bottom: 0px; border: 0px;"></td>
                    <td colspan="2" style="width: 40%;font-size: 18px; border: 0px; font-weight: bold;" ><br> <br>Invoice Number<br><?php echo $dfdf; ?></td>
                   
                    
                </tr>
			
			 <tr>
				    
                    <td colspan="4" style="width: 20%;text-align: left;font-size: 18px; border: 0px;">
					<img class="img-responsive" style="width: 100%;" src="tax3.jpg">
					</td>
                    
                </tr>
                <tr>
				    
                    <td style="width: 20%;text-align: left;font-size: 18px; border-right: 0px; border-bottom: 0px;">Client ID</td>
                    <td style="width: 40%;font-size: 18px; border-left: 0px; border-bottom: 0px;" ><?php echo $tnt; ?></td>
                    <td style="width: 20%;font-size: 18px; border-bottom: 0px; border-right:0px;">Region</td>
                    <td style="width: 20%;font-size: 18px;   border-bottom: 0px; border-left: 0px;"><?php echo $reg; ?></td>
                </tr>
                <tr>
                    <td style="width: 20%;text-align: left;font-size: 18px; border-top: 0px; border-right: 0px; border-bottom: 0px;" >Client Name</td>
                    <td style="width: 40%;font-size: 18px; border-top: 2px; border-left: 0px; border-bottom: 0px;"><?php echo $tt; ?></td>
                    <td style="width: 20%;font-size: 18px; border-top: 0px; border-right: 0px; border-bottom: 0px;">Water Meter</td>
                    <td style="width: 20%;font-size: 18px; border-top: 2px; border-left: 0px; border-bottom: 0px;"><?php echo $wmeter; ?></td>
                </tr>
                <tr>
                    <td style="width: 20%;text-align: left;font-size: 18px; border-top: 0px; border-right: 0px; border-bottom: 0px;">House Number</td>
                    <td style="width: 40%;font-size: 18px; border-top: 0px; border-left: 0px; border-bottom: 0px;"><?php echo $tnt; ?></td>
                    <td style="width: 20%;font-size: 18px; border-right: 0px; border-bottom: 0px; border-top: 0px;">Electricity Meter</td>
                    <td style="width: 20%;font-size: 18px; border-left: 0px; border-bottom: 0px; border-top: 0px;"><?php echo $emeter; ?></td>
                </tr>
                <tr>
                    <td style="width: 20%;text-align: left;font-size: 18px; border-top: 0px; border-right: 0px; border-bottom: 0px;">Phone</td>
                    <td style="width: 40%;font-size: 18px; border-top: 0px;  border-left: 0px; border-bottom: 0px;"><?php  ?></td>
                    <td style="width: 20%;font-size: 18px; border-top: 0px; border-right: 0px; border-bottom: 0px;">W/Order No</td>
                    <td style="width: 20%;font-size: 18px; border-top: 0px;  border-left: 0px; border-bottom: 0px;"></td>
                </tr>
                <tr>
                    <td style="width: 20%;text-align: left;font-size: 18px; border-top: 0px; border-right: 0px; border-bottom: 0px;">Email</td>
                    <td style="width: 40%;font-size: 18px; border-top: 0px;  border-left: 0px; border-bottom: 0px;"><?php  ?></td>
                    <td style="width: 20%;font-size: 18px; border-top: 0px; border-right: 0px; border-bottom: 0px;">S/Order No</td>
                    <td style="width: 20%;font-size: 18px; border-top: 0px;  border-left: 0px; border-bottom: 0px;"></td>
                </tr>
                <tr>
                    <td style="width: 20%;text-align: left;font-size: 18px; border-top: 0px; border-right: 0px;">Address</td>
                    <td style="width: 40%;font-size: 18px; border-left: 0px; border-top: 0px;"><?php  ?></td>
                    <td style="width: 20%;font-size: 18px; border-top: 0px; border-right: 0px; ">Bill Date</td>
                    <td style="width: 20%;font-size: 18px;  border-left: 0px; border-top: 0px;"><?php echo $billdate; ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="table" style="">
        <table class="table">
            <thead>
               
            </thead>
            <tbody style="width: 100%;">
                <tr>
                    <td style="width: 16.6%;;font-size: 18px; ">House No</td>
                    <td style="width: 16.6%;;font-size: 18px;">Station</td>
                    <td style="width: 16.6%;;font-size: 18px;">Bill Month</td>
                    <td style="width: 16.6%;;font-size: 18px;">Payment Due Date</td>
                    <td style="width: 16.6%;;font-size: 18px;">Start Date</td>
                    <td style="width: 16.6%;;font-size: 18px;">End Date</td>
                </tr>
                <tr>
                    <td style=";font-size: 18px;"><?php echo $house_code;?></td>
                    <td style=";font-size: 18px;"><?php echo $steshi; ?></td>
                    <td style=";font-size: 18px;"><?php echo $month;?></td>
                    <td style=";font-size: 18px;"><?php echo "10 -".$due."-".$year;?></td>
                    <td style=";font-size: 18px;"><?php echo "01 -".$month."-".$year;?></td>
                    <td style=";font-size: 18px;"><?php echo $days." -".$month."-".$year;?></td>
                </tr>
            </tbody>
        </table>
    </div>
   
        <table class="table">
            <thead>
                <tr>
                    <th class="text-center" colspan="7" style="font-size: 18px;">Description of Readings</th>
                </tr>
            </thead>
            <tbody>
                <tr style="font-weight: bold;">
                    <td style="width: 14.28%;font-size: 18px; border-top: 0px; border-right: 0px; border-left: 0px;">Quantity</td>
                    <td style="width: 14.28%;font-size: 18px; border-top: 0px; border-right: 0px; border-left: 0px;">Actual Units</td>
                    <td style="width: 14.28%;font-size: 18px; border-top: 0px; border-right: 0px; border-left: 0px;">Previous Units</td>
                    <td style="width: 14.28%;font-size: 18px; border-top: 0px; border-right: 0px; border-left: 0px;">Calculation</td>
                    <td style="width: 14.28%;font-size: 18px; border-top: 0px; border-right: 0px; border-left: 0px;">Tarrif</td>
                    <td style="width: 14.28%;font-size: 18px; border-top: 0px; border-right: 0px; border-left: 0px;">Unit Price</td>
                    <td style="width: 14.28%;font-size: 18px; border-top: 0px; border-right: 0px; border-left: 0px;">Total</td>
                </tr>
				 <tr style="font-weight: bold; border: 1px;">
                    <td style="width: 14.28%;font-size: 18px; border-top: 0px; border-right: 0px; border-left: 0px;"></td>
                    <td style="width: 14.28%;font-size: 18px; border-top: 0px; border-right: 0px; border-left: 0px; font-size: 21px; font-weight: bold;">Water</td>
                    <td style="width: 14.28%;font-size: 18px; border-top: 0px; border-right: 0px; border-left: 0px;"></td>
                    <td style="width: 14.28%;font-size: 18px; border-top: 0px; border-right: 0px; border-left: 0px; "></td>
                    <td style="width: 14.28%;font-size: 18px; border-top: 0px; border-right: 0px; border-left: 0px;"></td>
                    <td style="width: 14.28%;font-size: 18px; border-top: 0px; border-right: 0px; border-left: 0px;"></td>
                    <td style="width: 14.28%;font-size: 18px; border-top: 0px; border-right: 0px; border-left: 0px;"></td>
                </tr>
                
            </tbody>
        </table>
   
    <div class="table-responsive" style="margin-right: 0px;margin-left: 10px;">
        <table class="table test gg">
            <tbody>
                <tr>
                    <td style="width: 5.14%;font-size: 18px;">1</td>
                    <td style="width: 5.14%;font-size: 18px;">0</td>
                    <td style="width: 12%;font-size: 18px;" colspan="2">Basic Charge per Month</td>
                    <td style="width: 7.28%;font-size: 18px;"></td>
                    <td style="width: 8.28%;: 0px; font-size: 18px;">0</td>
                    <td style="width: 9.5%;: 0px; font-size: 18px;">93.88</td>
                    <td style="width: 9.5%;: 0px; font-size: 18px;">82.92</td>
                    <td style="width: 9.5%;: 0px; font-size: 18px;">E 93.88</td>
                </tr>
					 
                    
           
            
                <tr>
                    <td style="width: 5.14%;font-size: 18px; border: 0px;"></td>
                    <td style="width: 5.14%;font-size: 18px; border: 0px;"></td>
                    <td style="width: 12%;font-size: 18px;" colspan="2"><?php echo $currentwat; ?></td>
                    <td style="width: 7.28%;font-size: 18px;"><?php echo  $prevwater;?></td>
                    <td style="width: 8.28%;: 0pxfont-size: 18px;"><?php echo  $w_units;?></td>
                    <td style="width: 9.5%;: 0pxfont-size: 18px; border: 0px;"></td>
                    <td style="width: 9.5%;: 0pxfont-size: 18px; border: 0px;"></td>
                    <td style="width: 9.5%;: 0pxfont-size: 18px; border: 0px;"></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="table" style="margin-left: 10px;margin-right: 10px;margin-bottom: -13px;">
        <table class="table">
            <thead>
                <tr></tr>
            </thead>
            <tbody>
			<tr>
                    <td style="width: 5.28%;font-size: 18px; border: 0px;"></td>
                    <td style="width: 5.28%;font-size: 18px; border: 0px; "></td>
                    <td colspan="2" style="width: 12%;font-size: 18px;  ">Charges per Metre Cubic (Kilolitre)</td>
                    <td style="width: 7.28%;font-size: 18px;">0</td>
                    
                    <td style="width: 9.5%;font-size: 18px; border: 0px;"></td>
                    <td style="width: 9.5%;font-size: 18px; border: 0px;"></td>
                    <td style="width: 9.5%;font-size: 18px; border: 0px;"></td>
                </tr>
                <tr>
                    <td style="width: 5.28%;font-size: 18px;"></td>
                    <td style="width: 5.28%;font-size: 18px;"><?php echo $temp0;?></td>
                    <td style="width: 12%;font-size: 18px;">B1(1-10)</td>
                    <td style="width: 7.28%;font-size: 18px;">10</td>
                    <td style="width: 8.28%;font-size: 18px;"><?php echo $B1;?></td>
                    <td style="width: 9.5%;font-size: 18px;">80.92</td>
                    <td style="width: 9.5%;font-size: 18px;">80.92</td>
                    <td style="width: 9.5%;font-size: 18px; ">E&nbsp;  <?php echo $B1;?></td>
                </tr>
                <tr>
                    <td style="width: 5.28%;font-size: 18px;"></td>
                    <td style="width: 5.28%;font-size: 18px;"><?php echo $temp1;?></td>
                    <td style="width: 12%;font-size: 18px;">B2(11-15)</td>
                    <td style="width: 7.28%;font-size: 18px;">5</td>
                    <td style="width: 8.28%;font-size: 18px;"><?php echo $B2;?></td>
                    <td style="width: 9.5%;font-size: 18px;">21.08</td>
                    <td style="width: 9.5%;font-size: 18px;">21.08</td>
                    <td style="width: 9.5%;font-size: 18px;">E&nbsp; <?php echo $B2;?></td>
                </tr>
                <tr>
                    <td style="width: 5.28%;font-size: 18px;"></td>
                    <td style="width: 5.28%;font-size: 18px;"><?php echo $temp2;?></td>
                    <td style="width: 12%;font-size: 18px;">B3(16-50)</td>
                    <td style="width: 7.28%;font-size: 18px;">34</td>
                    <td style="width: 8.28%;font-size: 18px;"><?php echo $B3;?></td>
                    <td style="width: 9.5%;font-size: 18px;">31.74</td>
                    <td style="width: 9.5%;font-size: 18px;">31.74</td>
                    <td style="width: 9.5%;font-size: 18px;">E&nbsp; <?php echo $B3;?></td>
                </tr>
                <tr>
                    <td style="width: 5.28%;font-size: 18px;"></td>
                    <td style="width: 5.28%;font-size: 18px;"><?php echo $temp3;?></td>
                    <td style="width: 12%;font-size: 18px;">B1(&gt;50)</td>
                    <td style="width: 7.28%;font-size: 18px;">&gt;50</td>
                    <td style="width: 8.28%;font-size: 18px;"><?php echo $B4;?></td>
                    <td style="width: 9.5%;font-size: 18px;">31.87</td>
                    <td style="width: 9.5%;font-size: 18px;">31.87</td>
                    <td style="width: 9.5%;font-size: 18px;">E&nbsp; <?php echo $B4;?></td>
                </tr>
                <tr>
                    <td style="width: 5.28%;: 0px;font-size: 18px; border: 0px;"></td>
                    <td style="width: 5.28%;: 0px;font-size: 18px;  border: 0px;"></td>
                    <td style="width: 12%;: 0px; font-size: 21px; font-weight: bold;  border: 0px;"><strong>Electricity</strong><br></td>
                    <td style="width: 7.28%;: 0px;font-size: 18px;  border: 0px;"></td>
                    <td style="width: 8.28%;: 0px;font-size: 18px;  border: 0px;"></td>
                    <td style="width: 9.5%;: 0px;font-size: 18px;  border: 0px;"></td>
                    <td style="width: 9.5%;font-size: 18px; font-weight: bold;">Subtotal</td>
                    <td style="width: 9.5%;font-size: 18px; font-weight: bold;">E&nbsp; <?php echo $subtotal;?></td>
                </tr>
                <tr>
                    <td style="width: 5.28%;: 0px;font-size: 18px; border: 0px;"></td>
                    <td style="width: 5.28%;: 0px;font-size: 18px; border: 0px;"></td>
                    <td style="width: 12%;: 3px;font-size: 18px; "><?php echo $currentelec;?></td>
                    <td style="width: 7.28%;: 3px;font-size: 18px;"><?php echo $prevelec;?></td>
                    <td style="width: 8.28%;: 3px;font-size: 18px;"><?php echo $e_units;?></td>
                    <td style="width: 9.5%;: 0px;font-size: 18px; border: 0px;"></td>
                    <td style="width: 9.5%;font-size: 18px; border: 0px;"></td>
                    <td style="width: 9.5%;font-size: 18px; border: 0px;"></td>
                </tr>
                <tr>
                    <td style="width: 5.28%;: 3px;font-size: 18px;">0</td>
                    <td style="width: 5.28%;: 3px;font-size: 18px;">100</td>
                    <td style="width: 12%;: 3px;font-size: 18px;">Minimum Charge</td>
                    <td style="width: 7.28%;: 3px;font-size: 18px;">0</td>
                    <td style="width: 8.28%;: 3px;font-size: 18px;">100</td>
                    <td style="width: 9.5%;: 3px;font-size: 18px;">180.00</td>
                    <td style="width: 9.5%;: 3px;font-size: 18px;">180.00</td>
                    <td style="width: 9.5%;font-size: 18px;">E <?php echo $bn ?></td>
                </tr>
                <tr>
                    <td style="width: 5.28%;: 3px;font-size: 18px; ">0</td>
                    <td style="width: 5.28%;: 3px;font-size: 18px;"><?php echo $e_units - 100; ?></td>
                    <td style="width: 12%;: 0pxfont-size: 18px; border: 0px;"></td>
                    <td style="width: 7.28%;: 3px;font-size: 18px; border: 0px;"></td>
                    <td style="width: 8.28%;: 3px;font-size: 18px;"><?php echo $vw; ?></td>
                    <td style="width: 9.5%;font-size: 18px;">1.8</td>
                    <td style="width: 9.5%;font-size: 18px;">E 1.80</td>
                    <td style="width: 9.5%;font-size: 18px; "><?php echo "E ". $vw; ?></td>
                </tr>
				 <tr>
                    <td style="width: 5.28%;: 3px;font-size: 18px; border: 0px; "></td>
                    <td style="width: 5.28%;: 3px;font-size: 18px; border: 0px;"></td>
                    <td style="width: 12%;: 0pxfont-size: 18px; border: 0px;"></td>
                    <td style="width: 7.28%;: 3px;font-size: 18px; border: 0px;"></td>
                    <td style="width: 8.28%;: 3px;font-size: 18px; border: 0px;"></td>
                    <td style="width: 9.5%;font-size: 18px; border: 0px;"></td>
                    <td style="width: 9.5%;font-size: 18px; border: 0px;"></td>
                    <td style="width: 9.5%;font-size: 18px; font-weight: bold;"><?php echo "E ". $w; ?></td>
                </tr>
               
                <tr>
                    <td colspan="2" style="width: 5.28%;: 3px;padding: 0px;font-size: 18px; border-bottom: 0px; border-right: 0px;">Payment Details<br><br></td>
                    
                    <td style="width: 5.28%;: 3px;padding: 0px;font-size: 18px; border-left: 0px; border-bottom: 0px; border-right: 0px;">Internet Transfer</td>
                    <td style="width: 7.28%;: 3pxfont-size: 18px; border-left: 0px; border-bottom: 0px; border-right: 0px;"></td>
                    <td style="width: 8.28%;: 3px;font-size: 18px;  border-left: 0px; border-bottom: 0px; border-right: 0px;"></td>
                    <td style="width: 9.5%font-size: 18px;  border-left: 0px; border-bottom: 0px; border-right: 0px;"></td>
                    <td style="width: 9.5%;font-size: 18px;">Subtotal</td>
                    <td style="width: 9.5%;font-size: 18px;">E <?php echo $total; ?></td>
                </tr>
                <tr>
                    <td  colspan="2" style="width: 5.28%;font-size: 18px; border-top: 0px; border-right: 0px; border-bottom: 0px;">Name</td>
                    
                    <td style="width: 5.28%;: 3px;padding: 0px;font-size: 18px; border: 0px;">Eswatini Railway</td>
                    <td style="width: 7.28%font-size: 18px; border: 0px;"></td>
                    <td style="width: 8.28%;font-size: 18px; border: 0px;"></td>
                    <td style="width: 9.5%font-size: 18px; border: 0px;"></td>
                    <td style="width: 9.5%;font-size: 18px;">Shipping</td>
                    <td style="width: 9.5%;font-size: 18px;">0.00</td>
                </tr>
                <tr>
                    <td  colspan="2" style="width: 5.28%;font-size: 18px; border-top: 0px; border-right: 0px; border-bottom: 0px;">CC#</td>
                    
                    <td style="width: 12%font-size: 18px; border: 0px;"></td>
                    <td style="width: 7.28%font-size: 18px; border: 0px;"></td>
                    <td style="width: 8.28%;font-size: 18px; border: 0px;"></td>
                    <td style="width: 9.5%font-size: 18px; border: 0px;"></td>
                    <td style="width: 9.5%;font-size: 18px;">VAT</td>
                    <td style="width: 9.5%;font-size: 18px;">0.00</td>
                </tr>
                <tr>
                    <td  colspan="2" style="width: 5.28%;font-size: 18px; border-top: 0px; border-right: 0px; border-bottom: 0px;"></td>
                  
                    <td style="width: 12%font-size: 18px; border: 0px;"></td>
                    <td style="width: 7.28%font-size: 18px; border: 0px;"></td>
                    <td style="width: 8.28%;font-size: 18px; border: 0px;"></td>
                    <td style="width: 9.5%font-size: 18px; border: 0px;"></td>
                    <td style="width: 9.5%;font-size: 18px;">Sales Tax</td>
                    <td style="width: 9.5%;font-size: 18px;">0.00</td>
                </tr>
                <tr style="margin-bottom: -18px;">
                    <td style="width: 5.28%;font-size: 18px; border-top: 0px; border-right: 0px;"></td>
                    <td style="width: 5.28%;font-size: 18px; border-top: 0px; border-right: 0px; border-left: 0px;"></td>
                    <td style="width: 12%font-size: 18px; border-top: 0px; border-right: 0px; border-left: 0px;"></td>
                    <td style="width: 7.28%font-size: 18px; border-top: 0px; border-right: 0px; border-left: 0px;"></td>
                    <td style="width: 8.28%;font-size: 18px; border-top: 0px; border-right: 0px; border-left: 0px;"></td>
                    <td style="width: 9.5%font-size: 18px; border-top: 0px; border-right: 0px; border-left: 0px;"></td>
                    <td style="width: 9.5%;font-size: 18px; font-weight: bold;">TOTAL</td>
                    <td style="width: 9.5%;font-size: 18px; font-weight: bold;">E <?php echo $total; ?></td>
                </tr>
				<tr style="margin-bottom: -18px;">
                    
                    <td colspan="8" style="text-align: center; border: 0px; width: 9.5%;font-size: 18px; font-weight: bold;">ISO Standard Compliant</td>
                </tr>
            </tbody>
        </table>
    </div>

	</div>
 

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script>
	$(document).ready(function () {
 window.print();
});
</script>
 </div>
  </div>
   </div>
    </form>  
	
  
<!-- You might already have these, just ensure you do -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>

</html>

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