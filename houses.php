<?php
session_start();
include "database/conn.php";


?>
<html>
<head>
<title>Houses</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="file.css" rel="stylesheet">
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="css/crud.css">
<style>

    .custom-container {
        padding: 15px;
    }

    /* For screens with a width of 768px or larger */
    @media (min-width: 768px) {
        .custom-container {
            width: 100%;
            margin: 0 auto; /* Center the container horizontally */
        }
    }

    /* For screens with a width of less than 768px (mobile) */
    @media (max-width: 767px) {
        .custom-container {
            width: 100%;
        }
    }
    
    .custom-modal .modal-dialog {
    margin-top: 90px;  /* Adjust this value as needed */
}


#myInput {
  background-image: url('/css/searchicon.png');
  background-position: 10px 10px;
  background-repeat: no-repeat;
  width: 80%;
  font-size: 11px;
  padding: 8px 10px 12px 20px;
  border: 1px solid #ddd;
  margin: 8px;
}

</style>
</head>
<body>
<?php
if($_SESSION["id"]) {
    include "header.php";
?>



<div class="container"  >
	<p id="success"></p>
	

	
	
	
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6">
						<h2><b>Houses</b></h2>
					</div>
					<div class="col-sm-6">
						<a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New House</span></a>
						<a href="#csvupload" class="btn btn-primary" data-toggle="modal"><i class="material-icons">&#xe65c;</i> <span>Import CSV</span></a>						
					</div>
                </div>
            </div>
            
            
            <div class="table-responsive">
            <table class="table table-striped table-hover" id="myTable">
                <thead>
                    <tr>
						<th>
							<span class="custom-checkbox">
								<input type="checkbox" id="selectAll">
								<label for="selectAll"></label>
							</span>
						</th>
							<th>AutoID</th>
						<th>HOUSE NO</th>
                       
                        <th>TENANT</th>
						<th>STATION</th>
                        <th>ELECTRICITY MTR</th>
                        <th>WATER MTR</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
				<tbody>
				
				<?php
				$result = mysqli_query($conn,"SELECT * FROM house");
					$i=1;
					while($row = mysqli_fetch_array($result)) {
				?>
				<tr id="<?php echo $row["autoid"]; ?>">
				<td>
							<span class="custom-checkbox">
								<input type="checkbox" class="user_checkbox" data-user-id="<?php echo $row["housecode"]; ?>">
								<label for="checkbox2"></label>
							</span>
						</td>
						 <td><?php echo $row["autoid"]; ?></td>
                        <td><?php echo $row["housecode"]; ?></td>
					<td><?php echo $row["tcode"]; ?></td>
					<td><?php echo $row["station"]; ?></td>
					<td><?php echo $row["electricity_meter"]; ?></td>
					<td><?php echo $row["water_meter"]; ?></td>
					<td>
						<a href="#editEmployeeModal" class="edit" data-toggle="modal">
							<i class="material-icons update" data-toggle="tooltip" 
							data-housecode="<?php echo $row["autoid"]; ?>"
							data-houseid="<?php echo $row["housecode"]; ?>"
							data-tcode="<?php echo $row["tcode"]; ?>"
							data-station="<?php echo $row["station"]; ?>"
							data-emeter="<?php echo $row["electricity_meter"]; ?>"
							data-wmeter="<?php echo $row["water_meter"]; ?>"
							title="Edit">&#xE254;</i>
						</a>
						<a href="#deleteEmployeeModal" class="delete" data-id="<?php echo $row["autoid"]; ?>" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" 
						 title="Delete">&#xE872;</i></a>
                    </td>
				</tr>
				<?php
				$i++;
				}
				?>
				</tbody>
			</table>
			</div>
			
			


			
			
			
			
        </div>
    </div>
	
	
	
	
	
	<!-- Add Modal HTML -->
	<div id="addEmployeeModal" class="modal fade">
		<div class="modal-dialog" style="margin-top: 60px;">
			<div class="modal-content">
				<form id="user_form">
					<div class="modal-header">						
						<h4 class="modal-title">Add House</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">					
						<div class="form-group">
							<label>House Code</label>
							<input type="text" id="housecode" name="housecode" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Tenant</label>
							
                            <select id="tenant" name="tenant" class="form-control" >
                                <option value="">Select Tenant</option>
                                <?php
				           $result2 = mysqli_query($conn,"SELECT * FROM tenant ORDER BY name");
					
					while($row = mysqli_fetch_array($result2)) {
                       echo "<option value=".$row['name'].">".$row['name']." </option>";
                    }
				       ?>
                             </select>
						</div>
						<div class="form-group">
							<label>Stations</label>
						<select id="station" name="station" class="form-control" >
                                <option value="">Select Station</option>
                                <?php
				           $result5 = mysqli_query($conn,"SELECT * FROM stations");
					
					while($row = mysqli_fetch_array($result5)) {
                       echo "<option value=".$row['id'].">".$row['name']." </option>";
                    }
				       ?>
                             </select>
							 </div>
						<div class="form-group">
							<label>Electricity Meter</label>
							<input type="city" id="emeter" name="emeter" class="form-control" >
						</div>
                        <div class="form-group">
							<label>Water Meter</label>
							<input type="city" id="wmeter" name="wmeter" class="form-control" >
						</div>					
					</div>
					<div class="modal-footer">
					    <input type="hidden" value="1" name="type">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<button type="button" class="btn btn-success" id="btn-add">Add</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Edit Modal HTML -->
	<div id="editEmployeeModal" class="modal fade" >
		<div class="modal-dialog" style="margin-top: 60px;">
			<div class="modal-content">
				<form id="update_form">
					<div class="modal-header">						
						<h4 class="modal-title">Edit User</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
					    	<div class="form-group">
							<label>House AutoID</label>
							<input type="text" id="uautoid" name="uautoid" class="form-control" required READONLY>
						</div>
									
						<div class="form-group">
							<label>House Code</label>
							<input type="text" id="uhousecode" name="uhousecode" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Tenant</label>
							
                            <select id="utenant" name="utenant" class="form-control" required>
                                <option value="">Update Tenant</option>
                                <?php
				           $result2 = mysqli_query($conn,"SELECT * FROM tenant ORDER BY name");
					
					while($row = mysqli_fetch_array($result2)) {
                       echo "<option value=".$row['name'].">".$row['name']."</option>";
                    }
				       ?>
                             </select>
						</div>
						<div class="form-group">
							<label>Stations</label>
						<select id="ustation" name="ustation" class="form-control" required>
                                <option value="">Select Station</option>
                                <?php
				           $result5 = mysqli_query($conn,"SELECT * FROM stations");
					
					while($row = mysqli_fetch_array($result5)) {
					    //$outputstr="$row[name]";
                       echo "<option value='".$row['name']."'>".$row['name']." </option>";
                    }
				       ?>
                             </select>
							 </div>
						<div class="form-group">
							<label>Electricity Meter</label>
							<input type="city" id="uemeter" name="uemeter" class="form-control" required>
						</div>
                        <div class="form-group">
							<label>Water Meter</label>
							<input type="city" id="uwmeter" name="uwmeter" class="form-control" required>
						</div>							
					</div>
					<div class="modal-footer">
					<input type="hidden" value="2" name="type">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<button type="button" class="btn btn-info" id="update">Update</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Delete Modal HTML -->
	<div id="deleteEmployeeModal" class="modal fade" >
		<div class="modal-dialog" style="margin-top: 60px;">
			<div class="modal-content">
				<form>
						
					<div class="modal-header">						
						<h4 class="modal-title">Delete House Data</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
						<input type="hidden" id="id_d" name="id" class="form-control">					
						<p>Are you sure you want to delete these Records?</p>
						<p class="text-warning"><small>This action cannot be undone.</small></p>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<button type="button" class="btn btn-danger" id="delete">Delete</button>
					</div>




				</form>
			</div>
		</div>
	</div>

	<div id="csvupload" class="modal fade">
		<div class="modal-dialog" style="margin-top: 60px;">
			<div class="modal-content">
				<form method="POST" action="csv/uploadexcel.php" enctype="multipart/form-data">
						
					<div class="modal-header">						
						<h4 class="modal-title">Upload File</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
						<input type="file"  name="file" class="form-control">					
						<p>Please upload CSV file only</p>
						<p class="text-warning"><small>This action cannot be undone.</small></p>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						
                        <button type="submit" name="submit_file" class="btn btn-primary">Upload</button>
					</div>



				</form>
			</div>
		</div>
	</div>





<!--<div class="row">-->
<!--    <div class="col-md-6">-->
                    <!-- Ezoic - bottom_of_page 108 - bottom_of_page -->
<!--                    <div id="ezoic-pub-ad-placeholder-108">-->
<!--                    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>-->
                    <!-- google ad -->
<!--                    <ins class="adsbygoogle"-->
<!--                     style="display:inline-block;width:336px;height:280px"-->
<!--                     data-ad-client="ca-pub-1476310481307603"-->
<!--                     data-ad-slot="7754792175"></ins>-->
<!--                         <script>-->
<!--                            (adsbygoogle = window.adsbygoogle || []).push({});-->
<!--                         </script>-->
<!--                    </div>-->
                <!-- End Ezoic - bottom_of_page 108 - bottom_of_page -->
<!--    </div>-->
<!--    <div class="col-md-6">-->
                <!-- Ezoic - bottom_of_page 108 - bottom_of_page -->
<!--                <div id="ezoic-pub-ad-placeholder-108">-->
<!--                    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>-->
                    <!-- google ad -->
<!--                    <ins class="adsbygoogle"-->
<!--                     style="display:inline-block;width:336px;height:280px"-->
<!--                     data-ad-client="ca-pub-1476310481307603"-->
<!--                     data-ad-slot="7754792175"></ins>-->
<!--                         <script>-->
<!--                            (adsbygoogle = window.adsbygoogle || []).push({});-->
<!--                         </script>-->
<!--                    </div>-->
                <!-- End Ezoic - bottom_of_page 108 - bottom_of_page -->
<!--    </div>-->
<!--</div>-->

<script>
function myFunction() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
</script>



<script>
    $(document).on('click','#btn-add',function(e) {
		var data = $("#user_form").serialize();
		$.ajax({
			data: data,
			type: "post",
			url: "backend/save.php",
			success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
						$('#addEmployeeModal').modal('hide');
						alert('Data added successfully !'); 
                        location.reload();						
					}
					else if(dataResult.statusCode==201){
					   alert("Please fill all the fields !");
					}else if(dataResult.statusCode==206){
					   alert("There was an error, Please check for duplication !");
					}
			}
		});
	});
	$(document).on('click','.update',function(e) {
		var housecode=$(this).attr("data-houseid");
		var autoid=$(this).attr("data-housecode");
		var tenants=$(this).attr("data-tcode");
		var station=$(this).attr("data-station");
		var emeter=$(this).attr("data-emeter");
		var wmeter=$(this).attr("data-wmeter");
		$('#uautoid').val(autoid);
		$('#uhousecode').val(housecode);
		$('#utenant').val(tenants);
		$('#ustation').val(station);
		$('#uemeter').val(emeter);
		$('#uwmeter').val(wmeter);
	});
	
	$(document).on('click','#update',function(e) {
		var data = $("#update_form").serialize();
		$.ajax({
			data: data,
			type: "post",
			url: "backend/save.php",
			success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
						$('#editEmployeeModal').modal('hide');
						alert('Data updated successfully !'); 
                        location.reload();						
					}
					else if(dataResult.statusCode==201){
					   alert("Please fill all the fields !");
					}
			}
		});
	});
	$(document).on("click", ".delete", function() { 
		var id=$(this).attr("data-id");
		$('#id_d').val(id);
		
	});
	$(document).on("click", "#delete", function() { 
		$.ajax({
			url: "backend/save.php",
			type: "POST",
			cache: false,
			data:{
				type:3,
				id: $("#id_d").val()
			},
			success: function(dataResult){
					$('#deleteEmployeeModal').modal('hide');
					$("#"+dataResult).remove();
			
			}
		});
	});
	$(document).on("click", "#delete_multiple", function() {
		var user = [];
		$(".user_checkbox:checked").each(function() {
			user.push($(this).data('user-id'));
		});
		if(user.length <=0) {
			alert("Please select records."); 
		} 
		else { 
			WRN_PROFILE_DELETE = "Are you sure you want to delete "+(user.length>1?"these":"this")+" row?";
			var checked = confirm(WRN_PROFILE_DELETE);
			if(checked == true) {
				var selected_values = user.join(",");
				console.log(selected_values);
				$.ajax({
					type: "POST",
					url: "backend/save.php",
					cache:false,
					data:{
						type: 4,						
						id : selected_values
					},
					success: function(response) {
						var ids = response.split(",");
						for (var i=0; i < ids.length; i++ ) {	
							$("#"+ids[i]).remove(); 
						}	
					} 
				}); 
			}  
		} 
	});
	$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip();
		var checkbox = $('table tbody input[type="checkbox"]');
		$("#selectAll").click(function(){
			if(this.checked){
				checkbox.each(function(){
					this.checked = true;                        
				});
			} else{
				checkbox.each(function(){
					this.checked = false;                        
				});
			} 
		});
		checkbox.click(function(){
			if(!this.checked){
				$("#selectAll").prop("checked", false);
			}
		});
	});

</script>
</div>

	
	



<script type="text/javascript">

</script>
<?php
}else{
    header('Location: index.php');

} 
?>
</body>
</html>