<?php
include '../database/conn.php';
if(count($_POST)>0){
	if($_POST['type']==1){
	    
			$code=$_POST['trcode'];
			$min=$_POST['minv'];
			$mix=$_POST['maxv'];
            $range=$_POST['range'];
            $charge=$_POST['charge'];
			
		if($code !="" && $min !="" &&  $charge !="" ){

			$sql = "INSERT INTO `water_range`(`code`, `minv`, `maxv`, `range`, `amount`) 
			VALUES ('$code','$min','$mix', '$range', '$charge')";
			if (mysqli_query($conn, $sql)) {
				echo json_encode(array("statusCode"=>200));
			} 
			else {
				echo "Error: " . $sql . "<br>" . mysqli_error($conn);
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
		$utrcode=$_POST['utrcode'];
			$uminv=$_POST['uminv'];
			$umaxv=$_POST['umaxv'];
            $urange=$_POST['urange'];
			$ucharge=$_POST['ucharge'];
			
		$sql = "UPDATE `water_range` SET `minv`='$uminv',`maxv`='$umaxv' ,`range`='$urange' ,`amount`='$ucharge'  WHERE `code`='$utrcode' ";
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