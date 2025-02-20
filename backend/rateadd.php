<?php
include '../database/conn.php';
if(count($_POST)>0){
	if($_POST['type']==1){
	    
			$code=$_POST['category'];
			$basic=$_POST['name'];
			$amount=$_POST['tarrif'];
			
		if($code !="" && $basic !="" &&  $amount !="" ){

			$sql = "INSERT INTO `tarrifs`(`category`, `name`, `tarrif`) 
			VALUES ('$code','$basic','$amount')";
			if (mysqli_query($conn, $sql)) {
				echo json_encode(array("statusCode"=>200));
			} 
			else {
				//echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			}
			mysqli_close($conn);
		}
		else{
			echo json_encode(array("statusCode"=>201));
		}
	}
}
if(count($_POST)>0){
	if($_POST['type']==2){
		$ucode=$_POST['ucode'];
			$ubasic=$_POST['uitem'];
			$uamount=$_POST['uamount'];
			
		$sql = "UPDATE `flat_charge` SET `item`='$ubasic',`amount`='$uamount' WHERE `code`='$ucode' ";
		if (mysqli_query($conn, $sql)) {
			echo json_encode(array("statusCode"=>200));
		} 
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		mysqli_close($conn);
	}
}
if(count($_POST)>0){
	if($_POST['type']==3){
		$id=$_POST['id'];
		$sql = "DELETE FROM flat_charge WHERE `code` = '$id'";
		if (mysqli_query($conn, $sql)) {
			echo $id;
		} 
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		mysqli_close($conn);
	}
}
if(count($_POST)>0){
	if($_POST['type']==4){
		$id=$_POST['id'];
		$sql = "DELETE FROM user WHERE id in ($id)";
		if (mysqli_query($conn, $sql)) {
			echo $id;
		} 
		else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		mysqli_close($conn);
	}
}

?>