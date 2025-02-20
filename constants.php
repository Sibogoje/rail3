<?php
session_start();
include "database/conn.php";
if (!isset($_SESSION['userid']) || !isset($_SESSION['role'])) {
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
<title>Constants</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="file.css" rel="stylesheet">
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="css/crud.css">

<style>
 /* USER PROFILE PAGE */
 .card {
    margin-top: 20px;
    padding: 30px;
    background-color: rgba(214, 224, 226, 0.2);
    -webkit-border-top-left-radius:5px;
    -moz-border-top-left-radius:5px;
    border-top-left-radius:5px;
    -webkit-border-top-right-radius:5px;
    -moz-border-top-right-radius:5px;
    border-top-right-radius:5px;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
.card.hovercard {
    position: relative;
    padding-top: 0;
    overflow: hidden;
    text-align: center;
    background-color: #fff;
    background-color: rgba(255, 255, 255, 1);
}
.card.hovercard .card-background {
    height: 130px;
}
.card-background img {
    -webkit-filter: blur(25px);
    -moz-filter: blur(25px);
    -o-filter: blur(25px);
    -ms-filter: blur(25px);
    filter: blur(25px);
    margin-left: -100px;
    margin-top: -200px;
    min-width: 130%;
}
.card.hovercard .useravatar {
    position: absolute;
    top: 15px;
    left: 0;
    right: 0;
}
.card.hovercard .useravatar img {
    width: 100px;
    height: 100px;
    max-width: 100px;
    max-height: 100px;
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    border-radius: 50%;
    border: 5px solid rgba(255, 255, 255, 0.5);
}
.card.hovercard .card-info {
    position: absolute;
    bottom: 14px;
    left: 0;
    right: 0;
}
.card.hovercard .card-info .card-title {
    padding:0 5px;
    font-size: 20px;
    line-height: 1;
    color: #262626;
    background-color: rgba(255, 255, 255, 0.1);
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
}
.card.hovercard .card-info {
    overflow: hidden;
    font-size: 12px;
    line-height: 20px;
    color: #737373;
    text-overflow: ellipsis;
}
.card.hovercard .bottom {
    padding: 0 20px;
    margin-bottom: 17px;
}
.btn-pref .btn {
    -webkit-border-radius:0 !important;
}

</style>
</head>
<body>
<?php

    include "header.php";
?>




<div class="container">
<div class="container" style="margin-top: 50px;">


<div class="col-lg-6 col-sm-6" style="width: 100%;">
   
    <div class="btn-pref btn-group btn-group-justified btn-group-lg" role="group" aria-label="...">
        <div class="btn-group" role="group">
            <button type="button" id="stars" class="btn btn-primary" href="#tab1" data-toggle="tab"><span class="glyphicon glyphicon-check" aria-hidden="true"></span>
                <div class="hidden-xs">Rates</div>
            </button>
        </div>

        <div class="btn-group" role="group">
            <button type="button" id="following" class="btn btn-default" href="#tab3" data-toggle="tab"><span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span>
                <div class="hidden-xs">Other Constants</div>
            </button>
        </div>
    </div>

        <div class="well">
      <div class="tab-content">
          
          
        <div class="tab-pane fade in active" id="tab1">
        <div class="table-wrapper" style="margin-top: 0px;">
            <div class="table-title">
                <div class="row">
                <div class="col-sm-6">
						<h4><b>Rates</b></h4>
					</div>
					<div class="col-sm-6">
						<a href="#addRateModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Rate</span></a>
										
					</div>
                </div>
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
						<th>CODE</th>
                        <th>CATEGORY</th>
                        <th>NAME</th>
						<th>TARRIF</th>
                        <th>ACTION</th>
                        
                    </tr>
                </thead>
				<tbody>
				
				<?php
				$result = mysqli_query($conn,"SELECT * FROM tarrifs");
					$i=1;
					while($row = mysqli_fetch_array($result)) {
				?>
				<tr id="<?php echo $row["id"]; ?>">
				<td>
							<span class="custom-checkbox">
								<input type="checkbox" class="user_checkbox" data-user-id="<?php echo $row["id"]; ?>">
								<label for="checkbox2"></label>
							</span>
						</td>
						<td><?php echo $row["id"]; ?></td>
                        <td><?php echo $row["category"]; ?></td>
					<td><?php echo $row["name"]; ?></td>
					<td><?php echo $row["tarrif"]; ?></td>
					
					<td>
						<a href="#editRateModal" class="edit" data-toggle="modal">
							<i class="material-icons update" data-toggle="tooltip" 
							data-code="<?php echo $row["id"]; ?>"
								data-code="<?php echo $row["category"]; ?>"
							data-item="<?php echo $row["name"]; ?>"
							data-amount="<?php echo $row["tarrif"]; ?>"
					
							title="Edit">&#xE254;</i>
						</a>
						<a href="#deleteRateModal" class="delete" data-id="<?php echo $row["id"]; ?>" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" 
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
	<div id="addRateModal" class="modal fade">
		<div class="modal-dialog" style="margin-top: 60px;">
			<div class="modal-content">
				<form id="user_form">
					<div class="modal-header">						
						<h4 class="modal-title">Add Rate</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">					
                    <div class="form-group">
							<label>Category</label>
							<select class="form-control" name="category">
							    <option value="water">Water</option>
							    <option value="electricity">Elecrticity</option>
							    <option value="electricity">Elecrticity</option>
							</select>
						</div>
						<div class="form-group">
							<label>Basic Charge Name</label>
							<input type="text" id="basic" name="name" class="form-control" required>
						</div>
                        <div class="form-group">
							<label>Charge</label>
							<input type="number" id="amount" name="tarrif" class="form-control" required>
						</div>					
					</div>
					<div class="modal-footer">
					    <input type="hidden" value="1" name="type">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<button type="submit" class="btn btn-success" id="btn-addrate">Add</button>
					</div>
				</form>
			</div>
		</div>
	</div>

    <!-- Edit Modal HTML -->
	<div id="editRateModal" class="modal fade" >
		<div class="modal-dialog" style="margin-top: 60px;">
			<div class="modal-content">
				<form id="update_form">
					<div class="modal-header">						
						<h4 class="modal-title">Edit Rate</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
									
						<div class="form-group">
							<label>Category</label>
							<input type="text" id="ucode" name="ucode" class="form-control" required READONLY>
						</div>
						
						<div class="form-group">
							<label>Name</label>
							<input type="text" id="uitem" name="uitem" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Tarrif</label>
							<input type="city" id="uamount" name="uamount" class="form-control" required>
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
	<div id="deleteRateModal" class="modal fade" >
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
	


        <div class="tab-pane fade in" id="tab3">
          <h3>No Data</h3>
        </div>
      </div>
    </div>
    
    </div>
    </div>
</div>

<script type="text/javascript">
    $(document).on('click','#btn-addtarrif',function(e) {
		var data = $("#tarrif_form").serialize();
		$.ajax({
			data: data,
			type: "post",
			url: "backend/tarrifadd.php",
			success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
						$('#addTarrifModal').modal('hide');
						alert('Rate added successfully !'); 
                        location.reload();						
					}
					else if(dataResult.statusCode==201){
					   alert("Please fill all the fields !");
					}
			}
		});
	});
	$(document).on("click", ".tdelete", function() { 
		var id=$(this).attr("data-id");
		$('#id_d').val(id);
		
	});
	$(document).on("click", "#tdelete", function() { 
		$.ajax({
			url: "backend/rateadd.php",
			type: "POST",
			cache: false,
			data:{
				type:3,
				id: $("#id_d").val()
			},
			success: function(dataResult){
					$('#deleteTarrifModal').modal('hide');
					$("#"+dataResult).remove();
			
			}
		});
	});
	


    $(document).on('click','.trupdate',function(e) {
		var tminv=$(this).attr("data-tminv");
		var tmaxv=$(this).attr("data-tmaxv");
		var trange=$(this).attr("data-trange");
		var tamount=$(this).attr("data-tamount");
		var trcode=$(this).attr("data-trcode");
		
		$('#utrcode').val(trcode);
		$('#uminv').val(tminv);
		$('#umaxv').val(tmaxv);
		$('#urange').val(trange);
		$('#ucharge').val(tamount);
		
	});
	
	$(document).on('click','#trupdate',function(e) {
		var data = $("#trupdate_form").serialize();
		$.ajax({
			data: data,
			type: "post",
			url: "backend/tarrifadd.php",
			success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
						$('#editTarrifModal').modal('hide');
						alert('Data updated successfully !'); 
                        location.reload();						
					}
					else if(dataResult.statusCode==201){
					   alert("Please fill all the fields !");
					}
			}
		});
	});

</script>



<script type="text/javascript">
    $(document).on('click','#btn-addrate',function(e) {
		var data = $("#user_form").serialize();
		$.ajax({
			data: data,
			type: "post",
			url: "backend/rateadd.php",
			success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
						$('#addRateModal').modal('hide');
						alert('Rate added successfully !'); 
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
			url: "backend/rateadd.php",
			type: "POST",
			cache: false,
			data:{
				type:3,
				id: $("#id_d").val()
			},
			success: function(dataResult){
					$('#deleteRateModal').modal('hide');
					$("#"+dataResult).remove();
			
			}
		});
	});

    $(document).on('click','.update',function(e) {
		var code=$(this).attr("data-code");
		var item=$(this).attr("data-item");
		var amount=$(this).attr("data-amount");
		
		$('#ucode').val(code);
		$('#uitem').val(item);
		$('#uamount').val(amount);
		
	});
	
	$(document).on('click','#update',function(e) {
		var data = $("#update_form").serialize();
		$.ajax({
			data: data,
			type: "post",
			url: "backend/rateadd.php",
			success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
						$('#editRateModal').modal('hide');
						alert('Data updated successfully !'); 
                        location.reload();						
					}
					else if(dataResult.statusCode==201){
					   alert("Please fill all the fields !");
					}
			}
		});
	});

</script>

<script type="text/javascript">

$(document).ready(function() {
$(".btn-pref .btn").click(function () {
    $(".btn-pref .btn").removeClass("btn-primary").addClass("btn-default");
    // $(".tab").addClass("active"); // instead of this do the below 
    $(this).removeClass("btn-default").addClass("btn-primary");   
});
});
</script>

</body>
</html>