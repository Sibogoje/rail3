<?php
session_start();
include "database/conn.php";

if (!empty($_POST["prints"])) {

    $tnt=$_POST['tenanted'];
	$month=$_POST['mnm'];
   
			
?>

<head>
<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>  
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script> 
   <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<style>  
        table {  
            .invoice-title h2, .invoice-title h3 {
    display: inline-block;
}

.table > tbody > tr > .no-line {
    border-top: none;
}

.table > thead > tr > .no-line {
    border-bottom: none;
}

.table > tbody > tr > .thick-line {
    border-top: 2px solid;
} 
    </style>
    </head>
    <input type="button" id="create_pdf" value="Print Invoice"> 
    
    
<form class="form" style="max-width: none; width: 1005px;">  
<div class="container" style="width: 100%">
    <div class="row">
        <div class="col-xs-12">
    		<div class="invoice-title">
    		<img src="invoicelogo.png" width="100%">
                
                <h3 class="pull-right">Invoice # 12345</h3>
    		</div>
    		<hr>
    		<div class="row">
    			<div class="col-xs-6">
    				<address>
    				<strong>Billed To:</strong><br>
    					Eswatini Railways<br>
    					Manzini <br>
    					<strong>Payment Method:</strong> Internet Transfer<br>
    					
    				</address>
    			</div>
    			<div class="col-xs-6 text-right">
    				<address>
        			<strong>Shipped To:</strong><br>

                    <?php
				           $result3 = mysqli_query($conn,"SELECT * FROM `tenant` WHERE `tcode`='$tnt' ");

                        while($rows = mysqli_fetch_array($result3)) {
                        echo $rows['name']." <br>";
                        echo $rows['surname']." <br>";
                        echo $rows['phone']." <br>";
                       echo $rows['email']." <br>";}
				       ?>

    					
    				</address>
    			</div>
    		</div>
    		<div class="row">
    			<div class="col-xs-6">
    				<address>
    					<strong>House Details:</strong><br>
                        <?php
				           $result3 = mysqli_query($conn,"SELECT * FROM `house` WHERE `tcode`='$tnt' ");

                        while($rows = mysqli_fetch_array($result3)) {
                        echo "Station ".$rows['station']." <br>";
                        echo "Electricity Meter ".$rows['electricity_meter']." <br>";
                        echo "Water Meter       ".$rows['water_meter']." <br>";
                       }
				       ?>
    					
    				</address>
    			</div>
    			<div class="col-xs-6 text-right">
    				<address>
    					<strong>Process Date:</strong><br>
    					<p id="date"></p>
                        <br>
                        <strong>Bill Month: <?php echo $month;?> </strong><br>
    				</address>
    			</div>
    		</div>
    	</div>
    </div>
    
    <div class="row">
    	<div class="col-md-12">
    		<div class="panel panel-default">
    			<div class="panel-heading">
    				<h3 class="panel-title"><strong>Order summary</strong></h3>
    			</div>
    			<div class="panel-body">
    				<div class="table-responsive">
    					<table class="table table-condensed">
    						<thead>
                                <tr>
        							<td><strong>Item</strong></td>
        							<td class="text-center"><strong>Units For <?php echo $month; ?></strong></td>
        							<td class="text-center"><strong>Price</strong></td>
        							<td class="text-right"><strong>Totals</strong></td>
                                </tr>
    						</thead>
    						<tbody>
    							<!-- foreach ($order->lineItems as $line) or some such thing here -->
                      <?php
                      $result6 = mysqli_query($conn,"SELECT * FROM `invoices` where `tenant`='$tnt' and    `month`='$month' ");
                                while($row = mysqli_fetch_array($result6)) {
                      
                      
                            echo    "<tr>";
    						echo 		"<td> Water</td>";
    						
    						echo		"<td class='text-center'>".$row['electricity_units']."</td>";
    						echo		"<td class='text-center'>".$row['electricity_charge']."</td>";
                            echo		"<td class='text-right'>".$row['electricity_charge']."</td>";
    						echo	"</tr>";

                            echo    "<tr>";
    						echo 		"<td> Electricity</td>";
    						
    						echo		"<td class='text-center'>".$row['water_units']."</td>";
    						echo		"<td class='text-center'>".$row['water_charge']."</td>";
                            echo		"<td class='text-right'>".$row['water_charge']."</td>";
    						echo	"</tr>";

                            $r = $row['water_charge'];
                            $g = $row['electricity_charge'];

                           $tr =  $r +$g ;

                           ?>  
                             

                                <tr>
    								<td class="no-line"><strong>Prev. Water Reading</strong></td>
                                    <td class="no-line text-center"><strong>Prev. Electr. Reading</strong></td>
    								<td class="no-line"><strong>Curr. Water Reading</strong></td>
    								
    								<td class="no-line text-right"><strong>Curr. Electr. Reading</strong> </td>
    							</tr>
                                <tr>

                                <td class="thick-line text-center"><?php echo $row['prevelec'];  ?></td>
    								<td class="thick-line text-center"><?php echo $row['prevwater'];  ?></td>
    								<td class="thick-line text-center"><?php echo $row['currentwat'];  ?></td>
    								<td class="thick-line text-center"><?php echo $row['currentelec'];  ?></td>
                                   
    								
    							</tr>
    							<tr>
    								<td class="thick-line"></td>
    								<td class="thick-line"></td>
                                   
    								<td class="thick-line text-center"><strong>Subtotal</strong></td>
    								<td class="thick-line text-right">E <?php echo $tr; ?></td>
    							</tr>
    							<tr>
    								<td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line text-center"><strong>Tax</strong></td>
    								<td class="no-line text-right">E0</td>
    							</tr>
    							<tr>
    								<td class="no-line"></td>
    								<td class="no-line"></td>
    								<td class="no-line text-center"><strong>Total To Be Paid</strong></td>
    								<td class="no-line text-right"><strong>E <?php echo $tr; ?> </strong></td>
    							</tr>

                                


<?php
  }

?> 
<tr>
    <td class="no-line" colspan="4"><strong>Tarrifs Index</strong></td>
</tr>
                                   <tr>
    								<td class="no-line"><strong>Electricity Min. Charge</strong> | E180.00 for 100kwh </td>
                                    <td class="no-line text-right" colspan="3">Above 100kwh = E180 + (Units x 1.8)</td>
    								
    								
    								
    							</tr>
                                <tr>
 <td class="no-line" colspan="4"><strong>Water Min. Charge</strong> | E82.62 </td></td>
</tr>
    							<tr>
                                <td class="no-line text-left">B1(1-10) - E71.21</td>
    								<td class="no-line text-left">B2(11-15) - E18.55</td>
    								<td class="no-line text-left">B3(16-50) - E27.93</td>
    								<td class="no-line text-left">B4(>50) - E31.87 </td>
    							</tr>


    						</tbody>
    					</table>
    				</div>
    			</div>
    		</div>
    	</div>


        

</div>  
    </div>
</div> 
    </form>  


    <script>  
    (function () {  
        var  
         form = $('.form'),  
         cache_width = form.width(),  
         a4 = [595.28, 841.89]; // for a4 size paper width and height  
  
        $('#create_pdf').on('click', function () {  
            $('body').scrollTop(0);  
            createPDF();  
        });  
        //create pdf  
        function createPDF() {  
            getCanvas().then(function (canvas) {  
                var  
                 img = canvas.toDataURL("image/png"),  
                 doc = new jsPDF({  
                     unit: 'px',  
                     format: 'a4'  
                 });  
                doc.addImage(img, 'JPEG', 20, 20);  
                doc.save('Bhavdip-html-to-pdf.pdf');  
                form.width(cache_width);  
            });  
        }  
  
        // create canvas object  
        function getCanvas() {  
            form.width((a4[0] * 1.33333) - 80).css('max-width', 'none');  
            return html2canvas(form, {  
                imageTimeout: 2000,  
                removeContainer: true  
            });  
        }  
  
    }());  
</script>  
<script>  
    /* 
 * jQuery helper plugin for examples and tests 
 */  
    (function ($) {  
        $.fn.html2canvas = function (options) {  
            var date = new Date(),  
            $message = null,  
            timeoutTimer = false,  
            timer = date.getTime();  
            html2canvas.logging = options && options.logging;  
            html2canvas.Preload(this[0], $.extend({  
                complete: function (images) {  
                    var queue = html2canvas.Parse(this[0], images, options),  
                    $canvas = $(html2canvas.Renderer(queue, options)),  
                    finishTime = new Date();  
  
                    $canvas.css({ position: 'absolute', left: 0, top: 0 }).appendTo(document.body);  
                    $canvas.siblings().toggle();  
  
                    $(window).click(function () {  
                        if (!$canvas.is(':visible')) {  
                            $canvas.toggle().siblings().toggle();  
                            throwMessage("Canvas Render visible");  
                        } else {  
                            $canvas.siblings().toggle();  
                            $canvas.toggle();  
                            throwMessage("Canvas Render hidden");  
                        }  
                    });  
                    throwMessage('Screenshot created in ' + ((finishTime.getTime() - timer) / 1000) + " seconds<br />", 4000);  
                }  
            }, options));  
  
            function throwMessage(msg, duration) {  
                window.clearTimeout(timeoutTimer);  
                timeoutTimer = window.setTimeout(function () {  
                    $message.fadeOut(function () {  
                        $message.remove();  
                    });  
                }, duration || 2000);  
                if ($message)  
                    $message.remove();  
                $message = $('<div ></div>').html(msg).css({  
                    margin: 0,  
                    padding: 10,  
                    background: "#000",  
                    opacity: 0.7,  
                    position: "fixed",  
                    top: 10,  
                    right: 10,  
                    fontFamily: 'Tahoma',  
                    color: '#fff',  
                    fontSize: 12,  
                    borderRadius: 12,  
                    width: 'auto',  
                    height: 'auto',  
                    textAlign: 'center',  
                    textDecoration: 'none'  
                }).hide().fadeIn().appendTo('body');  
            }  
        };  
    })(jQuery);  
  
</script>  

<script>
n =  new Date();
y = n.getFullYear();
m = n.getMonth() + 1;
d = n.getDate();
document.getElementById("date").innerHTML = d + "/" + m + "/" + y;
</script>

 <?php   
} else {  
echo "there was an error";
}



?>
