<?php
session_start();
include "database/conn.php";
?>
<html>
<head>
<title>Payments</title>
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
    width: 70%;
}
</style>
</head>
<body>
<?php
if($_SESSION["id"]) {

include "header.php";

?>





<div class="container custom-container" style="margin-top: 0;">
<div class="container custom-container text-center" style="margin-top: 100px;">
    <div class="row grid-divider">
    <div class="col-padding">
    <h3>Invoice Payments</h3>
        <?php $actio = "pri/index.php"; ?>
        <form method="POST" action="" id="paymentform" target="_blank"  >
                        <select id="invoicenumber" name="invoicenumber" class="form-control js-example-basic-single" required>
                            <option value="">Select Invoice to Process</option>
                            <?php
                            $result2 = mysqli_query($conn,"SELECT * FROM  `invoices` order by invoicenumber desc");
                            while($row = mysqli_fetch_array($result2)) {
                            echo "<option value=".$row['invoicenumber'].">".$row['invoicenumber']."</option>";
                            }?>
                        </select>

                        <label for="balance" class="" style="margin-top: 20px;">Balance</label>

                        <input type="number" id="balance" name="balance" placeholder="Balance" class="form-control" >

                        <label for="surplus" class="" style="margin-top: 20px;">Credit</label>

                        <input type="number" id="surplus" name="surplus" placeholder="Credit" class="form-control" >          
            <div class="form-group">
                    <input type="submit" formtarget="_blank" class="btn btn-warning"  id="process" name="process" style="width: 100%;" value="Process Invoice">
            </div>
            
                           
                            
        </form>
</div>
</div>
</div>
</div>



<script type="text/javascript">
$(document).ready(function() {
    $('.js-example-basic-single').select2();
});

</script>

<script>
    // Attach an event listener to the select element
    $('#invoicenumber').on('change', function () {
        // Get the selected invoicenumber
        var selectedInvoiceNumber = $(this).val();
        
        // Make an AJAX request to fetch data from the database
        $.ajax({
            url: 'fetch_data.php', // Replace with the PHP script that fetches data
            type: 'POST',
            data: { invoicenumber: selectedInvoiceNumber },
            dataType: 'json', // Assuming the data returned is in JSON format
            success: function (data) {
                // Populate the balance and surplus textboxes with the retrieved data
                $('#balance').val(data.balance);
                $('#surplus').val(data.surplus);
            }
        });
    });
</script>


<?php
}else{
    header('Location: index.php');

} 
?>
</body>
</html>