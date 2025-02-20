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

if (isset($_POST['process'])) {

}

?>

<html>
<head>
<title>House Profile</title>
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

<style>
    .custom-container {
        padding: 15px;
    }

    /* For screens with a width of 768px or larger */
    @media (min-width: 768px) {
        .custom-container {
            width: 70%;
            margin: 0 auto; /* Center the container horizontally */
        }
    }

    /* For screens with a width of less than 768px (mobile) */
    @media (max-width: 767px) {
        .custom-container {
            width: 100%;
        }
    }
</style>

</head>
<body>
<?php


include "header.php";

?>





<div class="container custom-container" style="margin-top: 0;">
<div class="container custom-container text-center" style="margin-top: 30px;">
    <div class="row grid-divider">

    <h3>House Profile</h3>
        <form method="POST" action="printhouse.php" target="_blank" id="paymentform"  >
                        <select id="invoicenumber" name="invoicenumber" class="form-control js-example-basic-single" required>
                            <option value="">Select House</option>
                            <?php
                            $result2 = mysqli_query($conn,"SELECT housecode FROM  `house` order by housecode desc");
                            while($row = mysqli_fetch_array($result2)) {
                            echo "<option value=".$row['housecode'].">".$row['housecode']."</option>";
                            }?>
                        </select>
            <div class="form-group">
                    <input type="submit" class="btn btn-warning"  id="process" name="process" style="width: 100%;" value="Print House Profile">
            </div>
            
                           
                            
        </form>
</div>

</div>
</div>



<script type="text/javascript">
$(document).ready(function() {
    $('.js-example-basic-single').select2();
});

</script>


</body>
</html>