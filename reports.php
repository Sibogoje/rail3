<?php
session_start();
if (!isset($_SESSION['userid']) || !isset($_SESSION['role'])) {
    // Redirect to the login page
    header("Location: index.php"); // Adjust the path if needed
    exit();
}

// Access session variables
$username = $_SESSION['userid'];
$role = $_SESSION['role'];

// Create connection
include('database/conn.php');
$connect = mysqli_connect($servername, $username, $password, $db);

?>
<html>
<head>
<title>Reports</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="file.css" rel="stylesheet">
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- Include Bootstrap Modal -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<style>
body {
    font-family: Arial, Helvetica, sans-serif;
    padding: 0;
    margin: 0;
    background-color: #f8f9fa;
}
* {
    box-sizing: border-box;
}

/* Button used to open the contact form - fixed at the bottom of the page */
.open-buttonh {
  background-color: #555;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  opacity: 0.8;
  position: fixed;
  bottom: 23px;
  right: 28px;
  width: 280px;
}

/* The popup form - hidden by default */
.form-popup {
  display: none;
  position: fixed;
  bottom: 0;
  right: 15px;
  border: 3px solid #f1f1f1;
  z-index: 9;
}

/* Add styles to the form container */
.form-container {
  max-width:600px;
  padding: 10px;
  background-color: rgba(0, 0, 0, 0.85);
}

/* Full-width input fields */
.form-container input[type=text], .form-container input[type=password] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  border: none;
  background: #f1f1f1;
}

/* When the inputs get focus, do something */
.form-container input[type=text]:focus, .form-container input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}

/* Set a style for the submit/login button */
.form-container .btn {
    margin: 5px 0 22px 0;
  background-color: #04AA6D;
  color: white;
  padding: 16px 10px;
  border: none;
  cursor: pointer;
  width: 100%;
  margin-bottom:10px;
  opacity: 0.8;
}
.form-container .tex {
  color: white;
  width: 100%;
  margin:10px;
}
.form-container .tex2 {
  color: white;
  width: 100%;
}
.form-container .select {
  background-color: #04AA6D;
  color: white;
  border: none;
  cursor: pointer;
  width: 100%;
  margin-bottom:10px;
  opacity: 0.8;
}

.clientpick{
 background-color: #04AA6D;
  color: white;
  border: none;
  cursor: pointer;
  margin-bottom:10px;
  opacity: 0.8;   
}

/* Add a red background color to the cancel button */
.form-container .cancel {
  background-color: red;
}

/* Add some hover effects to buttons */
.form-container .btn:hover, .open-button:hover {
  opacity: 1;
}

.buttonz {
  background-color: #04AA6D;
  border: none;
  color: white;
  padding: 20px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  border-radius: 25px;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  opacity: 0.8;
  position: fixed;
  bottom: 23px;
  right: 28px;
}
.button5 {border-radius: 12px;}

.container {
    width: 100%;
    max-width: 1200px;
    margin: auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
}


.main-content {
    /* margin-top: 80px; Adjust based on header height */
    display: flex;
    justify-content: center;
    align-items: center;
    top: 0;
    /* height: calc(100vh - 80px); Adjust based on header height */
}

/* Center the modal */
.modal-dialog {
    margin: auto;
    top: 50%;
    transform: translateY(-50%);
}
</style>
</head>
<body>


<div class="main-content">
<?php
    include "header.php";
?>
    <div class="">
        <div  class="form-inline">
            <button class="buttonz button5" onclick="openForm()"><i class="fa fa-database" aria-hidden="true"></i></button>

            <div class="form-popup" id="myForm">
                <div class="form-container" id="stationform">
                    <h3 class="tex">Design Query</h3>
                    <div id="reportpick" style="width: 250px;">
                        <select id="reports" name="reports" class="form-control select" required>
                            <option value="first">Choose Report</option>
                            <option value="pstation">Per Station</option>
                            <option value="wateronly">Water Only</option>
                            <option value="eleconly">Electricity Only</option>
                        </select>
                    </div>
                    <br><br>
                    <div id="steshi">
                        <form action="pri/stations.php" method="POST" class="form-container">
                            <label style="color: white;">Station </label>
                            <div id="stationspick">
                                <select id="stationsrepo" name="stationsrepo" class="form-control select" style="width: 100%" required>
                                    <option value="">Select Station</option>
                                    <option value="all">All Stations</option>
                                    <?php
                                    $result2 = mysqli_query($connect,"SELECT * FROM stations");
                                    while($row = mysqli_fetch_array($result2)) {
                                        echo "<option value=".$row['id'].">".$row['name']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <label style="color: white;">Where Client </label>
                            <div id="clientpick" style="background-color: #04AA6D; color: white; border: none; margin-bottom:10px;">
                                <select id="clientsrepo" name="clientsrepo" class="form-control select js-example-basic-single" style="width: 100%;">
                                    <option value="">Select Client</option>
                                    <option value="all">All Clients</option>
                                    <?php
                                    $result2 = mysqli_query($connect,"SELECT * FROM invoices GROUP BY tenant");
                                    while($row = mysqli_fetch_array($result2)) {
                                        echo "<option value=".$row['tenant'].">".$row['tenant']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <label style="color: white;">OR House (Pick One) </label>
                            <div id="housepick" style="background-color: #04AA6D; color: white; border: none; margin-bottom:10px;">
                                <select id="houserepo" name="houserepo" class="form-control select js-example-basic-single" style="width: 100%;">
                                    <option value="">Select House</option>
                                    <option value="all">All Houses</option>
                                    <?php
                                    $result2 = mysqli_query($connect,"SELECT * FROM invoices GROUP BY house_code");
                                    while($row = mysqli_fetch_array($result2)) {
                                        echo "<option value=".$row['house_code'].">".$row['house_code']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <label style="color: white;">And Month </label>
                            <div id="monthpick">
                                <select id="monthsrepo" name="monthsrepo[]" class="form-control select js-example-basic-multiple" style="width: 100%;" multiple="multiple" required>
                                    <option value="">Select Month</option>
                                    <?php
                                    $result2 = mysqli_query($connect, "SELECT DISTINCT month FROM invoices WHERE year = '2024'");
                                    while ($row = mysqli_fetch_array($result2)) {
                                        echo "<option value=" . $row['month'] . ">" . $row['month'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <label style="color: white;">And Year </label>
                            <div id="yearpick">
                                <select id="yearrepo" name="yearrepo" class="form-control select js-example-basic-multiple" style="width: 100%;" required>
                                    <option value="">Select Year</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                </select>
                            </div>
                            <input type="submit" formtarget="_blank" class="btn" id="genstation" id="prints" name="prints" value="Generate Station Report">
                        </form>
                    </div>

                    <div id="wateron">
                        <form action="pri/water.php" method="POST" class="form-container">
                            <label style="color: white;">Station </label>
                            <div id="stationspick">
                                <select id="stationsrepo" name="stationsrepo" class="form-control select" style="width: 100%" required>
                                    <option value="">Select Station</option>
                                    <option value="all">All Stations</option>
                                    <?php
                                    $result2 = mysqli_query($connect,"SELECT * FROM stations");
                                    while($row = mysqli_fetch_array($result2)) {
                                        echo "<option value=".$row['id'].">".$row['name']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <label style="color: white;">Where Client </label>
                            <div id="clientpick" style="background-color: #04AA6D; color: white; border: none; margin-bottom:10px;">
                                <select id="clientsrepo1" name="clientsrepo" class="form-control select js-example-basic-single" style="width: 100%;">
                                    <option value="gg">Select Client</option>
                                    <option value="all">All Clients</option>
                                    <?php
                                    $result2 = mysqli_query($connect,"SELECT * FROM invoices GROUP BY tenant");
                                    while($row = mysqli_fetch_array($result2)) {
                                        echo "<option value=".$row['tenant'].">".$row['tenant']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <label style="color: white;">OR House (Pick One) </label>
                            <div id="housepick1" style="background-color: #04AA6D; color: white; border: none; margin-bottom:10px;">
                                <select id="houserepo1" name="houserepo1[]" class="form-control select js-example-basic-multiple" style="width: 100%;" multiple="multiple">
                                    <option value="gg">Select House</option>
                                    <option value="all">All Houses</option>
                                    <?php
                                    $result2 = mysqli_query($connect,"SELECT * FROM invoices GROUP BY house_code");
                                    while($row = mysqli_fetch_array($result2)) {
                                        echo "<option value=".$row['house_code'].">".$row['house_code']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <label style="color: white;">And Month </label>
                            <div id="monthpick">
                                <select id="monthsrepo1" name="monthsrepo[]" class="form-control select js-example-basic-multiple" style="width: 100%;" multiple="multiple" required>
                                    <option value="">Select Month</option>
                                    <?php
                                    $result2 = mysqli_query($connect, "SELECT DISTINCT month FROM invoices WHERE year = '2024'");
                                    while ($row = mysqli_fetch_array($result2)) {
                                        echo "<option value=" . $row['month'] . ">" . $row['month'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <label style="color: white;">And Year </label>
                            <div id="yearpick">
                                <select id="yearrepo1" name="yearrepo1" class="form-control select js-example-basic-multiple" style="width: 100%;" required>
                                    <option value="">Select Year</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                </select>
                            </div>
                            <input type="submit" formtarget="_blank" class="btn" id="genstation" id="prints" name="prints" value="Generate Water Report" style="background-color: #008CDB;">
                        </form>
                    </div>

                    <div id="elecon">
                        <form action="pri/elec.php" method="POST" class="form-container">
                            <label style="color: white;">Station </label>
                            <div id="stationspick">
                                <select id="stationsrepo" name="stationsrepo" class="form-control select" style="width: 100%" required>
                                    <option value="">Select Station</option>
                                    <option value="all">All Stations</option>
                                    <?php
                                    $result2 = mysqli_query($connect,"SELECT * FROM stations");
                                    while($row = mysqli_fetch_array($result2)) {
                                        echo "<option value=".$row['id'].">".$row['name']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <label style="color: white;">Where Client </label>
                            <div id="clientpick" style="background-color: #04AA6D; color: white; border: none; margin-bottom:10px;">
                                <select id="clientsrepo2" name="clientsrepo" class="form-control select js-example-basic-single" style="width: 100%;">
                                    <option value="gg">Select Client</option>
                                    <option value="all">All Clients</option>
                                    <?php
                                    $result2 = mysqli_query($connect,"SELECT * FROM invoices GROUP BY tenant");
                                    while($row = mysqli_fetch_array($result2)) {
                                        echo "<option value=".$row['tenant'].">".$row['tenant']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <label style="color: white;">OR House (Pick One) </label>
                            <div id="housepick" style="background-color: #04AA6D; color: white; border: none; margin-bottom:10px;">
                                <select id="houserepo2" name="houserepo2[]" class="form-control select js-example-basic-multiple" style="width: 100%;" multiple="multiple">
                                    <option value="gg">Select House</option>
                                    <option value="all">All Houses</option>
                                    <?php
                                    $result2 = mysqli_query($connect,"SELECT * FROM invoices GROUP BY house_code");
                                    while($row = mysqli_fetch_array($result2)) {
                                        echo "<option value=".$row['house_code'].">".$row['house_code']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <label style="color: white;">And Month </label>
                            <div id="monthpick">
                                <select id="monthsrepo2" name="monthsrepo[]" class="form-control select js-example-basic-multiple" style="width: 100%;" multiple="multiple" required>
                                    <option value="">Select Month</option>
                                    <?php
                                    $result2 = mysqli_query($connect, "SELECT DISTINCT month FROM invoices WHERE year = '2024'");
                                    while ($row = mysqli_fetch_array($result2)) {
                                        echo "<option value=" . $row['month'] . ">" . $row['month'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <label style="color: white;">And Year </label>
                            <div id="yearpick">
                                <select id="yearrepo2" name="yearrepo2" class="form-control select js-example-basic-multiple" style="width: 100%;" required>
                                    <option value="">Select Year</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                </select>
                            </div>
                            <input type="submit" formtarget="_blank" class="btn" id="genstation" id="prints" name="prints" value="Generate Electricity Report" style="background-color: #1FDB00;">
                        </form>
                    </div>

                    <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openForm() {
    document.getElementById("myForm").style.display = "block";
}

function closeForm() {
    document.getElementById("myForm").style.display = "none";
}
</script>

<div class="container">
    <h3 class="text-center">Tenant Bills Reports</h3>
    <br />
    <div class="table-responsive">
        <table id="employee_data" class="table table-striped table-bordered text-center">
            <thead>
                <tr>
                    <td>Tenant</td>
                    <td># of Invoices</td>
                    <td>T. Water Charge (E)</td>
                    <td>T. Electr. Units (E)</td>
                    <td>Total (E)</td>
                    <td>Amount Paid (E)</td>
                    <td>Balance (E)</td>
                    <td>Action</td>
                </tr>
            </thead>
            <?php
            $currentYear = date('Y');
            $query = "SELECT tenant, COUNT(*) AS num_invoices, 
                      SUM(water_charge) AS total_water_charge, 
                      SUM(sewage_charge) AS total_sewage_charge, 
                      SUM(electricity_charge) AS total_electricity_charge, 
                      SUM(water_charge + electricity_charge + sewage_charge) AS total_charge 
                      FROM invoices 
                      WHERE year = '$currentYear' 
                      GROUP BY tenant 
                      ORDER BY tenant ASC";
            $result = mysqli_query($connect, $query);
            while ($row = mysqli_fetch_array($result)) {
                $tenant = $row["tenant"];
                $totalCharge = $row["total_charge"];

                // Fetch amount paid from the `paid` table
                $paidQuery = "SELECT SUM(amount) AS total_paid FROM paid WHERE tenant = '$tenant'";
                $paidResult = mysqli_query($connect, $paidQuery);
                $paidRow = mysqli_fetch_assoc($paidResult);
                $totalPaid = $paidRow['total_paid'] ?? 0;

                // Calculate balance
                $balance = $totalCharge - $totalPaid;

                echo '
                <tr>
                    <td>' . $tenant . '</td>
                    <td>' . $row["num_invoices"] . '</td>
                    <td>' . number_format($row["total_water_charge"] + $row["total_sewage_charge"], 2) . '</td>
                    <td>' . number_format($row["total_electricity_charge"], 2) . '</td>
                    <td>' . number_format($totalCharge, 2) . '</td>
                    <td>' . number_format($totalPaid, 2) . '</td>
                    <td>' . number_format($balance, 2) . '</td>
                    <td>
                        <a href="pri/print_water.php?tenant=' . $tenant . '" class="btn btn-info" target="_blank">Water</a>
                        <a href="pri/print_electricity.php?tenant=' . $tenant . '" class="btn btn-success" target="_blank">Electricity</a>
                        <button class="btn btn-primary pay-btn" data-tenant="' . $tenant . '" data-total="' . $totalCharge . '">Pay</button>
                    </td>
                </tr>
                ';
            }
            ?>
        </table>
    </div>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="paymentForm">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Make Payment</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="tenant" name="tenant"> <!-- Ensure 'name' attribute is present -->
                    <div class="form-group">
                        <label for="amount">Amount Paid (E):</label>
                        <input type="number" name="amount" class="form-control" step="0.01" placeholder="Enter amount" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Open payment modal
    $(".pay-btn").click(function() {
        const tenant = $(this).data("tenant");
        $("#tenant").val(tenant); // Set tenant value in the hidden input
        $("#paymentModal").modal("show");
    });

    // Ensure the form submission is bound only once
    $("#paymentForm").off("submit").on("submit", function(event) {
        event.preventDefault(); // Prevent default form submission

        // Disable the submit button to prevent multiple clicks
        const submitButton = $(this).find("button[type='submit']");
        if (submitButton.prop("disabled")) {
            return; // Prevent duplicate submissions
        }
        submitButton.prop("disabled", true);

        const formData = $(this).serialize(); // Serialize form data
        console.log("Form Data Sent:", formData); // Debugging: Log the form data

        $.ajax({
            url: "pri/save_payment.php", // Adjust the URL if needed
            type: "POST",
            data: formData,
            success: function(response) {
                console.log("Response Received:", response); // Debugging: Log the response
                try {
                    const jsonResponse = typeof response === "string" ? JSON.parse(response) : response; // Parse the response
                    alert(jsonResponse.message); // Display success message
                    if (jsonResponse.success) {
                        $("#paymentModal").modal("hide"); // Hide the modal
                        location.reload(); // Reload the page to update the table
                    }
                } catch (e) {
                    console.error("Invalid JSON response:", response); // Debugging: Log invalid JSON
                    alert("An error occurred while processing the payment.");
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error); // Debugging: Log AJAX errors
                alert("An error occurred while processing the payment."); // Display error message
            },
            complete: function() {
                // Re-enable the submit button after the request completes
                submitButton.prop("disabled", false);
            }
        });
    });

    // Reset the form and re-enable the submit button when the modal is closed
    $("#paymentModal").on("hidden.bs.modal", function() {
        $("#paymentForm")[0].reset(); // Reset the form
        $("#paymentForm").find("button[type='submit']").prop("disabled", false); // Re-enable the submit button
    });
});
</script>

<script>

var repo = document.getElementById("reports"); 
var steshis = document.getElementById("steshi"); 
repo.onchange = function() {
    
if (repo.value == 'wateronly'){
$("#steshi").hide();
$("#wateron").show();
$("#elecon").hide();

}else if(repo.value == 'pstation'){
$("#steshi").show();
$("#elecon").hide();
$("#wateron").hide();


}else if (repo.value == 'eleconly'){
    $("#steshi").hide();
    $("#elecon").show();
    $("#wateron").hide();

}else{
     $("#steshi").hide();
    $("#elecon").hide();
    $("#wateron").hide(); 
}
} 
</script>



<script> 

    $(document).on('click','#genstation',function(e) {
		var data = $("#stationform").serialize();
		$.ajax({
			data: data,
			type: "post",
			url: "backend/stationrepo.php",
			success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
				
						 $('#employee_data').hide(); 
					   alert("Please fill all the fields !");
					}
			}
		});
	});


 $(document).ready(function(){
     
     // In your Javascript (external .js resource or <script> tag)

// Reset the form when modal is closed
$("#paymentModal").on("hidden.bs.modal", function() {
        $("#paymentForm")[0].reset();
        $("#paymentForm").find("button[type='submit']").prop("disabled", false);
    });   
     
     
$("#steshi").hide();
$("#elecon").hide();
$("#wateron").hide();
      $('#employee_data').DataTable(); 
      // In your Javascript (external .js resource or <script> tag)

    $('.js-example-basic-single').select2();
    $('.js-example-basic-multiple').select2();

    // Open payment modal
    $(".pay-btn").click(function() {
        const tenant = $(this).data("tenant");
        $("#tenant").val(tenant);
        $("#paymentModal").modal("show");
    });


 });  
 </script> 
</body>
</html>
