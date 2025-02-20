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


include "database/conn.php";
if(isset($_POST["submit_file"]))
{
 $file = $_FILES["file"]["tmp_name"];
 $file_open = fopen($file,"r");
 while(($csv = fgetcsv($file_open, 1000, ",")) !== false)
 {
  $housecode = $csv[0];
  $tcode = $csv[1];
  $station = $csv[2];
  $emeter = $csv[3];
  $wmeter = $csv[4];
 
  $sql = "INSERT INTO house (`housecode`, `tcode`, `station`, `electricity_meter`, `water_meter`) VALUES ('$housecode','$tcode',' $station', '$emeter', '$wmeter')";
  
  if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
    header("Location: house.php"); 
exit();
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
  
  $conn->close();

 }
}



?>
<html>
<head>
<title>Tenants</title>
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
</head>
<body>
<?php

    include "header.php";
?>




<div class="container"  >
	<p id="success"></p>
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6">
						<h2><b>Tenants</b></h2>
					</div>
					<div class="col-sm-6">
						<a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Tenant</span></a>
						<a href="#csvupload" class="btn btn-primary" data-toggle="modal"><i class="material-icons">&#xe65c;</i> <span>Import CSV</span></a>						
					</div>
                </div>
            </div>
<div class="input-group mb-12 w-100" style="width: 100%;">
  <input type="text" class="form-control" id="searchInput" placeholder="Search..." aria-label="Search" aria-describedby="searchBtn">
</div>
            <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
						<th>
							<span class="custom-checkbox">
								<input type="checkbox" id="selectAll">
								<label for="selectAll"></label>
							</span>
						</th>
						<th>T-ID</th>
                       
                        <th>NAME</th>
						 <th>STATION</th>
						  <th>HOUSE</th>
                        <th>PHONE</th>
                        <th>EMAIL</th>
                        
                        <th>ADDRESS</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
				<tbody>
				
				<?php
				$result = mysqli_query($conn,"SELECT * FROM tenant");
					$i=1;


					while($row = mysqli_fetch_array($result)) {
				?>
				<tr id="<?php echo $row["autoid"]; ?>">
				<td>
							<span class="custom-checkbox">
								<input type="checkbox" class="user_checkbox" data-user-id="<?php echo $row["autoid"]; ?>">
								<label for="checkbox2"></label>
							</span>
						</td>
                        <td><?php echo $row["autoid"]; ?></td>
					<td><?php echo $row["name"]; ?></td>
					
				
					<td><?php echo $row["station"]; ?></td>
					<td><?php echo $row["housecode"]; ?></td>
						<td><?php echo $row["phone"]; ?></td>
					<td><?php echo $row["email"]; ?></td>
                    <td><?php echo $row["address"]; ?></td>
					<td>
						<a href="#editEmployeeModal" class="edit" data-toggle="modal">
							<i class="material-icons update" data-toggle="tooltip" 
							data-autoid="<?php echo $row["autoid"]; ?>"
							data-name="<?php echo $row["name"]; ?>"
								data-housecode="<?php echo $row["housecode"]; ?>"
						
						
							data-phone="<?php echo $row["phone"]; ?>"
							data-email="<?php echo $row["email"]; ?>"
								data-station="<?php echo $row["station"]; ?>"
                            data-address="<?php echo $row["address"]; ?>"
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
						<h4 class="modal-title">Add Tenant</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">					
                    <div class="form-group">
							<label>Tenant Code</label>
							<input type="text" id="tcode" name="tcode" class="form-control" required >
						</div>
						<div class="form-group">
							<label>House Assigned</label>
							
                            <select id="house" name="house" class="form-control" required>
                                <option value="">Update Tenant</option>
                                <?php
				           $result2 = mysqli_query($conn,"SELECT * FROM house  ");
					
					while($row = mysqli_fetch_array($result2)) {
                       echo "<option value=".$row['housecode'].">".$row['housecode']." ".$row['station']."</option>";
                    }
				       ?>
                             </select>
						</div>
						<div class="form-group">
							<label>Name</label>
							<input type="text" id="name" name="name" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Surname</label>
							<input type="city" id="station" name="station" class="form-control" required>
						</div>
                        <div class="form-group">
							<label>Phone</label>
							<input type="city" id="phone" name="phone" class="form-control" required>
						</div>		
                        <div class="form-group">
							<label>Email</label>
							<input type="city" id="email" name="email" class="form-control" required>
						</div>	
                        <div class="form-group">
							<label>Address</label>
							<input type="city" id="address" name="address" class="form-control" required>
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
						<h4 class="modal-title">Edit Tenant</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
									
						<div class="form-group">
							<label>Tenant Code</label>
							<input type="text" id="autoid" name="autoid" class="form-control" required READONLY>
						</div>
					
						<div class="form-group">
							<label>Name</label>
							<input type="text" id="uname" name="uname" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Station</label>
							<input type="city" id="ustation" name="ustation" class="form-control" required>
						</div>
							<div class="form-group">
							<label>House Assigned</label>
							
                            <select id="uhouse" name="uhouse" class="form-control" required>
                                <option value="">Choose House</option>
                                <?php
				           $result2 = mysqli_query($conn,"SELECT * FROM house  ");
					
					while($row = mysqli_fetch_array($result2)) {
                       echo "<option value=".$row['housecode'].">".$row['housecode']." ".$row['station']."</option>";
                    }
				       ?>
                             </select>
						</div>
                        <div class="form-group">
							<label>Phone</label>
							<input type="city" id="uphone" name="uphone" class="form-control" required>
						</div>		
                        <div class="form-group">
							<label>Email</label>
							<input type="city" id="uemail" name="uemail" class="form-control" required>
						</div>	
                        <div class="form-group">
							<label>Address</label>
							<input type="city" id="uaddress" name="uaddress" class="form-control" required>
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
				<form method="POST" action="csv/uploadexcel1.php" enctype="multipart/form-data">
						
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





<div class="row">
    <div class="col-md-6">
                    <!-- Ezoic - bottom_of_page 108 - bottom_of_page -->
                    <div id="ezoic-pub-ad-placeholder-108">
                    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- google ad -->
                    <ins class="adsbygoogle"
                     style="display:inline-block;width:336px;height:280px"
                     data-ad-client="ca-pub-1476310481307603"
                     data-ad-slot="7754792175"></ins>
                         <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                         </script>
                    </div>
                <!-- End Ezoic - bottom_of_page 108 - bottom_of_page -->
    </div>
    <div class="col-md-6">
                <!-- Ezoic - bottom_of_page 108 - bottom_of_page -->
                <div id="ezoic-pub-ad-placeholder-108">
                    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- google ad -->
                    <ins class="adsbygoogle"
                     style="display:inline-block;width:336px;height:280px"
                     data-ad-client="ca-pub-1476310481307603"
                     data-ad-slot="7754792175"></ins>
                         <script>
                            (adsbygoogle = window.adsbygoogle || []).push({});
                         </script>
                    </div>
                <!-- End Ezoic - bottom_of_page 108 - bottom_of_page -->
    </div>
</div>
<script>
    document.getElementById("searchInput").addEventListener("keyup", function() {
  searchTable();
});

document.getElementById("searchBtn").addEventListener("click", function() {
  searchTable();
});
</script>

<script>
    function searchTable() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("searchInput");
  filter = input.value.toUpperCase();
  table = document.getElementsByTagName("table")[0];
  tr = table.getElementsByTagName("tr");

  for (i = 1; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td");
    var found = false;
    for (j = 0; j < td.length; j++) {
      txtValue = td[j].textContent || td[j].innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        found = true;
        break;
      }
    }
    if (found) {
      tr[i].style.display = "";
    } else {
      tr[i].style.display = "none";
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
			url: "backend/tenant.php",
			success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
						$('#addEmployeeModal').modal('hide');
						alert('Data added successfully !'); 
                        location.reload();						
					}
					else if(dataResult.statusCode==201){
					   alert("Please fill all the fields !");
					}
			}
		});
	});
	$(document).on('click','.update',function(e) {
		var autoid=$(this).attr("data-autoid");
		var name=$(this).attr("data-name");
		var station=$(this).attr("data-station");
		var phone=$(this).attr("data-phone");
		var email=$(this).attr("data-email");
        var address=$(this).attr("data-address");
        var housecode=$(this).attr("data-housecode");
		$('#autoid').val(autoid);
		$('#uname').val(name);
		$('#ustation').val(station);
		$('#uphone').val(phone);
		$('#uemail').val(email);
        $('#uaddress').val(address);
        $('#uhouse').val(housecode);
	});
	
	$(document).on('click','#update',function(e) {
		var data = $("#update_form").serialize();
		$.ajax({
			data: data,
			type: "post",
			url: "backend/tenant.php",
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
			url: "backend/tenant.php",
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

</body>
</html>