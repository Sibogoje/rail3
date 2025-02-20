<?php
include '../database/conn.php';
if(count($_POST)>0){
	if($_POST['type']==1){
        $monthss = "";
        $days = "";
        $inyanga = "";
        $inyanga2 = 0;
            $tenant=$_POST['tenante'];
			$month=$_POST['month'];
			$dd=$_POST['wha'];
            $clean_tenant = mysqli_real_escape_string($conn, $tenant);
            $clean_month = mysqli_real_escape_string($conn, $month);

            $tinyanga = mysqli_query($conn,"SELECT id FROM months WHERE month='$clean_month'  ");
			while($rowm = mysqli_fetch_array($tinyanga)) {
               $inyanga = $rowm['id'];
                if ($inyanga==1){
                   $monthss = "December";
                   $days = 31;
               }else{
                $inyanga2 = $inyanga-1;
                $finals = mysqli_query($conn,"SELECT * FROM months WHERE id='$inyanga2'  ");
				while($rowf = mysqli_fetch_array($finals)) {
                    $monthss = $rowf['month'];
                    $days = $rowf['days'];
                }}
            }
            
            
            
            
/////////////////////////////////////            
            
$sql = "SELECT * FROM `invoices` WHERE `house_code`='$clean_tenant' AND `month`='$monthss' AND `id` LIKE '%$dd%' ORDER BY GREATEST(`currentelec`, `currentwat`) DESC LIMIT 1";

if (mysqli_query($conn, $sql)) {
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $prev_elec = $row['currentelec'];  
        $prev_water = $row['currentwat'];  
        $pre_sewage = $row['prev_sewage'];

        $response = array(
            'statusCode'=>200,
            'prev_elec'=>$prev_elec,
            'prev_water'=>$prev_water,
            'pre_sewage'=>$pre_sewage
        );
        echo json_encode($response);
    } else {
        $kesponse = array(
            'statusCode'=>201
        );
        echo json_encode($kesponse);
    }   
} else {
    $response = array(
        'statusCode'=>207
    );
    echo json_encode($response);
}

			
			
//////////////////////			

		mysqli_close($conn);
	}else{
        $response = array(
            'statusCode'=>207
            );
        echo json_encode($response);
    }
}else{
    $response = array(
        'statusCode'=>208
        );
    echo json_encode($response);
}

?>