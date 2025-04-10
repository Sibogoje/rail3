<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "database/conn.php";

if (!isset($_SESSION['userid']) || !isset($_SESSION['role']))
{
    // Redirect to the login page
    header("Location: index.php"); // Adjust the path if needed
    exit();
}

// Access session variables
$username = $_SESSION['userid'];
$role = $_SESSION['role'];

?>
<html>
<head>
<title>Billing</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="file.css" rel="stylesheet">
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.debug.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>



<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


</head>
<body>
<?php

include "header.php";

?>




<div class="container">
    <div class="row">
    <div class="container" style="margin-top: 80px;">


<div class="container">
    
    <div class="row grid-divider">
    <div class="col-sm-4">
      <div class="col-padding">
        <h3>Calculator</h3>
        <form id="user_form">
										
                    
						<div class="form-group">
							<label>House</label>
							
                            <select id="tenant" name="tenante" oninput="onTextChange()" class="form-control js-example-basic-single" required>
                                <option value="">Select House</option>
                                <?php
$result2 = mysqli_query($conn, "SELECT * FROM `house` ");

while ($row = mysqli_fetch_array($result2))
{
    $hh = $row['housecode'];
    echo "<option value=" . $row['housecode'] . ">" . $row['station'] . " " . $row['housecode'] . " </option>";

}
?>
                             </select>
						</div>
						
							 <div style="width: 100%;">
                            
                              <input type="radio" id="alls" name="wha" value="" checked> 
                              <label for="alls">All  </label>
                              
                              
                             
                              <input type="radio" id="waters" name="wha" value="Wat">
                              <label for="waters">Water  </label>
                              
                              
                              
                              <input type="radio" id="elecs" name="wha" value="Elec">
                              <label for="elecs">Electricity  </label>
                             
                             </div>
					
						<div class="form-group">
							<label>Month To Be Billed</label>
							
                            <select id="month" name="month" class="form-control" required>
                                <option value=""></option>
                                <?php
$result2 = mysqli_query($conn, "SELECT * FROM months  ");

while ($row = mysqli_fetch_array($result2))
{
    echo "<option value=" . $row['month'] . ">" . $row['month'] . "</option>";
}
?>
                             </select>
						</div>
						
						
						<div style="display: table" >	
                        
                         <div class="form-group" style="width: 50%; display: table-cell">
							<label>Manual Water Units</label>
							<input type="number" id="manual_water" name="manual_water" class="form-control" value="" placeholder="Water Units" required>
                           
						</div>	
                        <div class="form-group" style="width: 50%; display: table-cell">
							<label>Manual Electriciy Units</label>
							<input type="number" id="manual_elec" name="manual_elec" class="form-control" value="" placeholder="Electricity Units" required>
                           
						</div>
                        </div>	
						
						<hr>
						  
                        <div style="display: table" >	
                        
                         <div class="form-group" style="width: 50%; display: table-cell">
							<label>Current Water Units</label>
							<input type="number" id="calprevwater" name="calprevwater" class="form-control" value="0" placeholder="Last Water Bill" required>
                           
						</div>	
                        <div class="form-group" style="width: 50%; display: table-cell">
							<label>Current Electriciy Units</label>
							<input type="number" id="calprevelec" name="calprevelec" class="form-control" value="0" placeholder="Last Electr.. Bill" required>
                           
						</div>
                </div>	
                
        <!--        <div class="row">-->
        <!-- <div class="form-group col-6">-->
        <!--    <label>Current Sewage Usage</label>-->
        <!--    <input type="number" id="sewage_usage" name="sewage_usage" class="form-control" value="" placeholder="Enter Sewage Units" required>-->
        <!--</div>-->
        <!--</div>-->
                

                        						
					</div>
					<div class="modal-footer">
					    <input type="hidden" value="1" name="type">
					
					</div>
				</form>
      
    </div>



    <div class="col-sm-4">
      <div class="col-padding">
        <h3>Results</h3>
        <form id="" method="post" action="backend/save.php">
				<div style="display: table" >			
                         <div class="form-group" style="width: 50%; display: table-cell">
							<label>Previous Water Bill</label>
							<input type="city" id="ffprevwater" name="ffprevwater" value="" class="form-control" value="0" placeholder="Last Water Bill" required readonly>
                           
						</div>	
                        <div class="form-group" style="width: 50%; display: table-cell">
                        <label>Previous Electriciy Bill</label>
                      
						<input type='city' id='ffprevelec' name='ffprevelec' value="" class='form-control' value="0" placeholder="Last Electricity Bill"  required  readonly>
                      
						</div>
                </div>


                <div style="display: table" >			
                         <div class="form-group" style="width: 50%; display: table-cell">
							<label>Water Units for this month</label>
							<input type="number" id="minprevwater" name="minprevwater" class="form-control" value="0" placeholder="Last Water Bill" required readonly>
                           
						</div>	
                        <div class="form-group" style="width: 50%; display: table-cell">
							<label>Electricity Units for this month</label>
							<input type="number" id="minprevelec" name="minprevelec" class="form-control" value="0" placeholder="Units For the Month" required readonly>
                           
						</div>
                </div>
                
                
                <div style="display: table" >			
                         <div class="form-group" style="width: 50%; display: table-cell">
							<label>Total Water Bill</label>
							<input type="city" id="twater" name="twater" class="form-control" placeholder="Bill for this month" required readonly>
                           
						</div>	
                        <div class="form-group" style="width: 50%; display: table-cell">
							<label>Total Electriciy Bill</label>
							<input type="number" id="telec" name="telec" class="form-control" placeholder="Bill For this Month" required readonly>
                           
						</div>
                </div>
                   <div style="display: table" >			
                         <div class="form-group" style="width: 50%; display: table-cell">
							<label>Prev Sewage Units</label>
							<input type="city" id="pre_sewage" name="pre_sewage" class="form-control" placeholder="Previous Units " required readonly>
                           
						</div>	
                        <div class="form-group" style="width: 50%; display: table-cell">
							<label>Charged Sewage Units</label>
							<input type="number" id="curr_sewage" name="curr_sewage" class="form-control" placeholder="Charged Units" required readonly>
                           
						</div>
			
                </div>
                </br>
		        <div style="width: 100%;">
                            <input type="checkbox" id="zeroSewage" name="zeroSewage">
                            <label for="zeroSewage">Zero Charged Sewage Units</label>
                </div>
						
					 <div style="width: 100%;">
                            
                              <input type="radio" id="enall" name="what" value="All" checked> 
                              <label for="enall">All  </label>
                              
                              
                             
                              <input type="radio" id="enwater" name="what" value="Water">
                              <label for="enwater">Water  </label>
                              
                              
                              
                              <input type="radio" id="enelec" name="what" value="Electricity">
                              <label for="enelec">Electricity  </label>
                             
                             </div>
						
                       	
                        <div class="form-group">
							<label>Comments</label>
							<input type="city" id="comments" name="comments" class="form-control" >
						</div>	
                        						
					</div>
					<div class="modal-footer">
                    <input  type="hidden" value="" name="currentelec" id="currentelec">
                    <input  type="hidden" value="" name="currectwat" id="currectwat">
                    <input  type="hidden" value="" name="previouswater" id="previouswater">
                    <input  type="hidden" value="" name="previouselectricity" id="previouselectricity">
					    <input type="hidden" value="6" name="type">
                        <input  type="hidden" value="" name="tnt" id="tnt">
                        <input  type="hidden" value="" name="mnts" id="mnts">
                        

        <div class="row">
        <div class="form-group col-6">
        <button type="button" class="btn btn-success" id="defwater" name="defwater" value="Save">Default Water</button>
        </div>
        <div class="form-group col-6">
            <button type="button" class="btn btn-success" id="defelect" name="defelect" value="Save">Default Electricity</button>
        </div>
        </div>




        <div class="form-group">
        <button type="submit"  class="btn btn-success"  id="addinv2" name="addinva" value="Save" style="width: 100%">Save</button>
		</div>
						
		</div>

                   
	</form>
      </div>
    

    <div class="col-sm-4">
      <div class="col-padding">
        <h3>Print Invoices</h3>
<form method="POST" action="" id="chuza" target="_blank"  >
                <select id="tenanted" name="tenanted" class="form-control js-example-basic-single" required>
                                <option value="">Select Invoice to Print</option>
                                <?php
$result2 = mysqli_query($conn, "SELECT * FROM  `invoices` ");

while ($row = mysqli_fetch_array($result2))
{
    $fg = $row['month'];
    echo "<option value=" . $row['house_code'] . ">" . $row['id'] . "</option>";

} ?>
                             </select>
                            
                             <br>
                              <br> <br>

                             <select id="mnm" name="mnm" class="form-control" required>
                                <option value="">Select Invoice to Print</option>
                                <?php
$result2 = mysqli_query($conn, "SELECT * FROM  `months` ");

while ($row = mysqli_fetch_array($result2))
{
    $fg = $row['month'];
    echo "<option value=" . $row['month'] . ">" . $row['month'] . "</option>";

} ?>
                             </select>
                             
                             
                             <br> <br>


    <div id="nd1" style="padding: 5px;">
        <input type="radio" id="typed1" name="typing" value="All" > 
        <label for="typed1">All  </label>
    </div>

    <div id="nd2" style="padding: 5px;">
        <input type="radio" id="typed2" name="typing" value="water">
        <label for="typed2">Water  </label>
    </div>

    <div id="nd3" style="margin-right: 0; padding: 5px;">
        <input type="radio" id="typed3" name="typing" value="Electricity">
        <label for="typed3">Electricity  </label>
    </div>
</div>d
<br><br>

                        
                        <div class="form-group">
                             <input type="submit" formtarget="_blank" class="btn btn-success"  id="prints" name="prints" value="Preview Invoice">
						</div>
						
						 <div class="form-group">
                             <input type="submit" formtarget="" style="background-color: red;" class="btn btn-danger"  id="delete" name="delete" value="Delete Invoice">
						</div>
                        
				      
</form>








        
        </div>

</div>
</div>

</div>
    
</div>
 </div>
</div>


<script>
document.getElementById('zeroSewage').addEventListener('change', function() {
    var curr_sewage = document.getElementById('curr_sewage');
    if (this.checked) {
        curr_sewage.value = '0';
        curr_sewage.setAttribute('readonly', 'readonly');
    } else {
        curr_sewage.removeAttribute('readonly');
    }
});
</script>


<script>
// JavaScript to update previous bill fields
document.getElementById('manual_water').addEventListener('input', function() {
    document.getElementById('ffprevwater').value = this.value;
});

document.getElementById('manual_elec').addEventListener('input', function() {
    document.getElementById('ffprevelec').value = this.value;
});
</script>


<script>
    $(document).ready(function () {
        $("#defwater").click(function () {
            $.ajax({
                url: "get_water_amount.php", // Change this to the actual server-side script
                type: "GET",
                success: function (data) {
                    $("#minprevwater").val(data);
                },
                error: function () {
                    alert("Error fetching water amount");
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function () {
        $("#defelect").click(function () {
            $.ajax({
                url: "get_water_elect.php", // Change this to the actual server-side script
                type: "GET",
                success: function (data) {
                    $("#minprevelec").val(data);
                },
                error: function () {
                    alert("Error fetching water amount");
                }
            });
        });
    });
</script>



<script type="text/javascript">
$(document).ready(function() {
    $('.js-example-basic-single').select2();
});

</script>
<script type="text/javascript">


$(document).on('click','#addinv',function(e) {
		var data = $("#results").serialize();
	//	if (calprevelec != ""){
		$.ajax({
			data: data,
			type: "post",
			url: "backend/save.php",
			success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					console.log(dataResult)
					if(dataResult.statusCode==200){
                      
                      alert("Succesfully Added the Invoice");
                   
                     //location.reload();
					}
					
				// 	else if(dataResult.statusCode==201){
    //                     var month = (dataResult.month);
    //                   var tnt = (dataResult.tnt);
				// 	   alert("Data for "+tnt+" for Month: "+month+" already exists");
				// 	}else if(dataResult.statusCode==202){
    //                     alert("Some fields are missing!");
    //                 }else if(dataResult.statusCode==203){
    //                     alert("No Result Found!");
    //                 }else if(dataResult.statusCode==204){
    //                     alert("An Error Occured!");
    //                 }
			}
		});

	});
   

$('#tenant').change(function() {
       
 var jk = $('#tenant option:selected').val();

 $('#tnt').val(jk);
  
	
});   

$('#month').change(function() {
    var jk2 = $('#month option:selected').val();

$('#mnts').val(jk2);

  var data = $("#user_form").serialize();
		$.ajax({
			data: data,
			type: "post",
			url: "backend/billing.php",
			success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
                       $("#ffprevelec").val(dataResult.prev_elec);
                       $("#ffprevwater").val(dataResult.prev_water);
                       $("#pre_sewage").val(dataResult.pre_sewage);
                        			
                     //   location.reload();						
					}
					else if(dataResult.statusCode==201){
                       $("#ffprevelec").val(0);
                       $("#ffprevwater").val(0);
                       $("#pre_sewage").val(0);
					}
			}
		});
});

$(document).ready(function() {
    const BASIC_CHARGE = 97.63 * 1.04;
    const FLAT_RATE_1_10 = 84.16 * 1.04;
    const RATE_11_15 = 21.92 * 1.04;
    const RATE_16_50 = 33.01 * 1.04;
    const RATE_ABOVE_50 = 37.67 * 1.04;
    const FIXED_ELEC_CHARGE = 0.00; // Assuming no change for electricity charge

    $('#calprevwater').on("input", function() {
        var currentWater = $(this).val();
        $('#currectwat').val(currentWater);
        $('#unitsd').val(currentWater);

        var inp = currentWater - $('#ffprevwater').val();
        $('#minprevwater').val(inp);
        $('#curr_sewage').val(inp);

        var twaterCharge;
        if (inp <= 0) {
            twaterCharge = 0.00; // Set charge to 0.00 if water units are 0 or less than 1
        } else if (inp <= 10) {
            twaterCharge = FLAT_RATE_1_10; // Flat rate for 1-10 units
        } else if (inp <= 15) {
            twaterCharge = FLAT_RATE_1_10 + (inp - 10) * RATE_11_15; // Charge for 11-15 units
        } else if (inp <= 50) {
            twaterCharge = FLAT_RATE_1_10 + 5 * RATE_11_15 + (inp - 15) * RATE_16_50; // Charge for 16-50 units
        } else {
            twaterCharge = FLAT_RATE_1_10 + 5 * RATE_11_15 + 35 * RATE_16_50 + (inp - 50) * RATE_ABOVE_50; // Charge for more than 50 units
        }
        if (inp > 0) {
            twaterCharge += BASIC_CHARGE; // Adding the basic charge only if water units are more than 0
        }
        $('#twater').val(twaterCharge.toFixed(2)); // Set the total water charge

        $('#telec').val(FIXED_ELEC_CHARGE); // Set a fixed electricity charge
    });
});





$('#defwater').on("click", function() {
  $('#twater').val(189.06);
   $('#telec').val(0);
});


// $('#defwater').on("click", function() {
//     $('#currectwat').val($(this).val());
//     $('#unitsd').val($(this).val());
// var inp = $(this).val() - $('#ffprevwater').val();
// $('#minprevwater').val(inp);
// if (inp < 11){
//     $('#twater').val(93.88 + 80.92);
//      // $('#telec').val(180);
// }else if (inp > 10 && inp < 16){
//     $('#twater').val(93.88 + 21.08);
//     //  $('#telec').val(180);
// }else if (inp > 15 && inp < 51  ){
//     $('#twater').val(93.88 + 31.74);
//   //   $('#telec').val(180);
// }else {
//     $('#twater').val(93.88 + 35.22);
//     //  $('#telec').val(180);
// }
	
// });




$('#calprevelec').on("input", function() {
    $('#currentelec').val($(this).val());
var inp2 = $(this).val() - $('#ffprevelec').val();
$('#minprevelec').val(inp2);

$('#telec').val(($('#minprevelec').val() * 2.574).toFixed(2));

	
});



$('#defelect').on("click", function() {
   // $('#currentelec').val($(this).val());
     $('#telec').val(180);
      $('#twater').val(0);
//
});


function generatePDF() {
 var doc = new jsPDF();  //create jsPDF object
  doc.fromHTML(document.getElementById("results"), // page element which you want to print as PDF
  15,
  15, 
  {
    'width': 170  //set width
  },
  function(a) 
   {
    doc.save("HTML2PDF.pdf"); // save file name as HTML2PDF.pdf
  });
}
</script>
<script>
$(document).ready(function () {
    $('#typed1').click(function () {
        if ($(this).is(':checked')) {
            $("#chuza").attr('action', 'pri/conv.php');
        }
    });

    $('#typed2').click(function () {
        if ($(this).is(':checked')) {
             $("#chuza").attr('action', 'pri/conv0.php');
        }
    });
    
     $('#typed3').click(function () {
        if ($(this).is(':checked')) {
           // alert("Transfer Thai Gayo");
            $("#chuza").attr('action', 'pri/conv1.php');

        }
    });
    
         $('#delete').click(function () {
        
           // alert("Transfer Thai Gayo");
            $("#chuza").attr('action', 'delete.php');

        
    });
});





</script>
<script>
  function onTextChange() {
            // Get the value from the first form's input
            var name = document.getElementById('tenant').value;
            // Set the value to the second form's input
            document.getElementById('tnt').value = name;
        }  
</script>


</body>
</html>
