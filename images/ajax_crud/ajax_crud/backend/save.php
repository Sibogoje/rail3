<?php
include '../database/conn.php';
if(count($_POST)>0){
	if($_POST['type']==1){
	    
			$name=$_POST['name'];
			$email=$_POST['email'];
			$phone=$_POST['phone'];
			$city=$_POST['city'];
		if($name !="" && $email !="" &&  $phone !="" && $city!=""){
			$sql = "INSERT INTO `user`( `name`, `email`,`phone`,`city`) 
			VALUES ('$name','$email','$phone','$city')";
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
		$id=$_POST['id'];
		$name=$_POST['name'];
		$email=$_POST['email'];
		$phone=$_POST['phone'];
		$city=$_POST['city'];
		$sql = "UPDATE `user` SET `name`='$name',`email`='$email',`phone`='$phone',`city`='$city',`cancel`=0 WHERE id=$id";
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
		$sql = "UPDATE `user` SET cancel=1 WHERE id=$id";
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