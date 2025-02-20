<?php
session_start();
include "../database/conn.php";

$tt2 = 0;
if (!empty($_POST["prints"])) {

    $station=$_POST['stationsrepo'];
	$clients=$_POST['clientsrepo'];
	$months=$_POST['monthsrepo'];
		$yrs=$_POST['yearrepo1'];
		
		$houses=$_POST['houserepo1'];
if ($houses == "all"){
      $clientquery = "WHERE house_code != 'all' ";
  }else{
      foreach ($houses as $b){
        $housearray[] = $b;
        }
        $housearray1 = json_encode($housearray);
        $housearray2 =  str_replace( array('[',']') , ''  , $housearray1 );	

      
  }


	
	
if ($clients != "") {
    $clientquery = "WHERE tenant = '$clients'";
} else {
    $clientquery = "WHERE house_code IN ({$housearray2})";
}

	
foreach ($months as $a){
$mntharray[] = $a;
}
$mntharray1 = json_encode($mntharray);
$mntharray2 =  str_replace( array('[',']') , ''  , $mntharray1 );
   // $inyanga = "AND month = '$months'";
   $inyanga = "AND month in ({$mntharray2})  ";
    //$clientquery = "WHERE tenant = '$clients' ";
    $sterr = "AND station = '$station'";	
if ($station == "" || $clients=="" || $months=="" ){
echo	"<script>alert('There was an error!!'); window.location='../reports.php'";

  
}else{

	
  

 
  if ($clients == "all"){
      $clientquery = "WHERE house_code!='all' ";
  }

   
    if ($months == "all"){
      $inyanga = "AND month !='all' ";
  }

    if ($station == "all"){
      $sterr = "AND station !='all' ";
  }


//$result1 = mysqli_query($conn,"SELECT * station FROM `invoices` $clientquery.' '.$inyanga ");

?>
   
			


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title><?php echo $station." ".$mntharray2." ". $clients; ?></title>
 
	 <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>

    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
	

   <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>



footer {
  font-size: 14px;
  color: black;
  text-align: center;
}

@page {
  size: A4;
  margin: 11mm 17mm 17mm 17mm;
 
}

@media print {
    

  footer {
    position: fixed;
    bottom: 0;
    display: flex;
align-items: center;
justify-content: center;
margin-left: 40%;
  }

  .content-block, p {
    page-break-inside: avoid;
  }

  html, body {
    width: 210mm;
    height: 297mm;
  }
}

@media print {
@page {
           margin-top: 0;
           margin-bottom: 0;
         }
         body  {
           padding-top: 72px;
           padding-bottom: 72px ;
         }

}
.table {
	font-family:'Arial';
  
 

  
}
#bottom {
   
 display: flex;
align-items: center;
justify-content: center;
}
td {
    border: 3px solid black;
  border-collapse: collapse;
  
}




@media print
{
    html
    {
		font-size: 15px;
       
    }
}

@media print
{
    html
    {
        zoom: 53%;
    }
}

</style>

</head>

<body style="">


<div class="container"  style="width: 100% height: 100%">




    <div class="table" style="width: 100%">
        <table class="table">
             <col style="width: 12.5%;" />
                <col style="width: 25%;" />
                <col style="width: 12.5%;" />
                <col style="width: 12.5%;" />
                <col style="width: 12.5%;" />
                <col style="width: 12.5%;" />
                <col style="width: 12.5%;" />
                <col style="width: 12.5%;" />
          
            <tbody>
			 <tr>
				    
                    <td colspan="7" style="border-left: 0px; border-right: 0px; border-top: 0px; margin-top: 1px;">
					<img class="img-responsive" style="" src="report.PNG">
					</td>
					 
                </tr>
			
			 <tr>
				    
                    <td colspan="3" style="font-weight: bold; border: 0px; font-size: 20px;">
					Regional Code: <?php echo $station; ?>
					</td>
					 <td colspan="3" style="font-weight: bold; border: 0px; font-size: 20px;">
					Statement
					</td>
					<td colspan="3" style="font-weight: bold; text-align: right; border: 0px; font-size: 20px;">
					<?php echo "Date: ". date("d/m/Y"); ?>
					</td>
                    
                </tr>
                <tr>
                    
                    
                    <th style="border: 1;">House No:</th>
                    <th style="border: 1;">Occupant</th>
                    <th style="border: 1;">Invoice No</th>
                    <th style="border: 1;">Bill/month</th>
                    <th style="border: 1;">Water</th>
                    <th style="border: 1;">Sewage</th>
                    <th style="border: 1;">Total</th>
                    <th style="border: 1;">Cumulative Total</th>
                </tr>
                
                <?php 
$result1 = mysqli_query($conn,"SELECT * FROM `invoices`  $clientquery $inyanga $sterr  AND `year` = '$yrs' AND w_units > 0 ORDER BY `tenant`");
              // echo $clientquery.$inyanga.$sterr;
if ($result1->num_rows > 0) {

while($row = $result1->fetch_assoc()) {
    
    
  $invoicenumber = $row['invoicenumber'];
   $water = $row['water_charge'];
     $electricty = $row['electricity_charge'];
      $house = $row['tenant'];
      $housec = $row['house_code'];
       $monthz = $row['month'];
       
                        $wp =  $row['wbill'];
						$ep = $row['ebill'];
						$t = $row['tbill'];
						
						
						
                     $w_units =  $row['w_units'];
					
						$e_units =  $row['e_units'];
						$electricity_charge =  $row['electricity_charge'];
						$water_charge =  $row['water_charge'];
						$sewage_charge =  $row['sewage_charge'];
			

$result4 = mysqli_query($conn,"SELECT tcode FROM `house` WHERE housecode='$housec'");
              // echo $clientquery.$inyanga.$sterr;
if ($result4->num_rows > 0) {

while($row4 = $result4->fetch_assoc()) {
 $sewage_charge2 = $sewage_charge;
 
 
if ($water_charge < 1){
    $sewage_charge2 = 0.00;
}
   
       
?>
                
                <tr>
				    
                    <td style="border: 1;"><?php echo  $housec; ?></td>
                    <td style="border: 1;"><?php echo  $row4['tcode']; ?></td>
                    <td style="border: 1;"><?php echo  $invoicenumber; ?></td>
                    <td style="border: 1;"><?php echo  $monthz; ?></td>
                    <td style="border: 1;"><?php echo  $water_charge; ?></td>
                   <td style="border: 1;"><?php echo  $sewage_charge2; ?></td>
                    <?php $tt1 = $water_charge + $sewage_charge2; ?>
                    <td style="border: 1;"><?php echo  $tt1; ?></td>
                     <?php $tt2 = $tt2 + $tt1; ?>
                    <td style="border: 1;"><?php echo  $tt2; ?></td>
               
                </tr>
               
                
                
                <?php } }} ?>
                <tr>
				    
                    <td style="border: 1; font-weight: bold; text-align: right;" colspan="7">Grand Total</td>
                  
                 
                    <td style="border: 1; font-weight: bold;"><?php echo  $tt2; ?></td>
               
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
	
<footer>
      <h3 id="bottom" style="">ISO Standard Compliant</h3>
    </footer>  
	
</body>

</html>

 <?php 
} else {
    
    // echo("No Results: " . $mysqli -> error);
  echo	"<script>alert('There seems to be problem with selected Query, Please match House with right Month');
window.location='../reports.php';
</script>";
 
}
    
    
}
} else {  
//echo	"<script>alert('There was an error!!'); window.location='../reports.php'";
 echo("Error 2: " . $mysqli -> error);
}
?>