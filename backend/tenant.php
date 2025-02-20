<?php
include '../database/conn.php';
if(count($_POST)>0){
	if($_POST['type']==1){
	    
			$tcode=$_POST['tcode'];
			$name=$_POST['name'];
			$surname=$_POST['station'];
			$phone=$_POST['phone'];
			$email=$_POST['email'];
            $address=$_POST['address'];
            $housecode=$_POST['house'];
		if($name !="" ){

			$sql = "INSERT INTO `tenant`(`tcode`, `name`, `station`,`phone`,`email`, `address`, `housecode`) 
			VALUES ('$tcode','$name','$surname', '$phone', '$email','$address', '$housecode')";
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
		$tcode=$_POST['autoid'];
			$name=$_POST['uname'];
			$station=$_POST['ustation'];
			$phone=$_POST['uphone'];
			$email=$_POST['uemail'];
				$house=$_POST['uhouse'];
            $address=$_POST['uaddress'];
		$sql = "UPDATE `tenant` SET `name`='$name',`station`='$station',`housecode` ='$house', `phone`='$phone', `email`='$email' , `address`='$address' WHERE `autoid`='$tcode' ";
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
		$sql = "DELETE FROM tenant WHERE `autoid` = '$id'";
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