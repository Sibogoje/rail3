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
    $selectedInvoiceNumber = $_POST['invoicenumber'];
    $paymentAmount = $_POST['pay'];
    $balance = $_POST['balance'];
    $credit = $_POST['surplus'];

    // Step 1: Update 'invoices' table's 'paid' column
    $updateInvoicesQuery = "UPDATE invoices SET paid = ? WHERE invoicenumber = ?";
    $stmt1 = $conn->prepare($updateInvoicesQuery);
    $stmt1->bind_param("di", $paymentAmount, $selectedInvoiceNumber);
    $stmt1->execute();


// Step 2: Get 'tenant' from 'invoices' table
    $getTenantQuery = "SELECT house_code FROM invoices WHERE invoicenumber = ?";
    $stmt2 = $conn->prepare($getTenantQuery);
    $stmt2->bind_param("i", $selectedInvoiceNumber);
    $stmt2->execute();

    // Get the result as an associative array
    $result2 = $stmt2->get_result();
    $row2 = $result2->fetch_assoc();
    $tenant = $row2['house_code'];

    // Step 3: Insert or update 'payments' table's 'balance' and 'credit' columns using INSERT ... ON DUPLICATE KEY UPDATE
    $insertOrUpdatePaymentsQuery = "INSERT INTO payments (house, balance, credit) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE balance = VALUES(balance), credit = VALUES(credit)";
    $stmt3 = $conn->prepare($insertOrUpdatePaymentsQuery);
    $stmt3->bind_param("sdd", $tenant, $balance, $credit);
    $stmt3->execute();

    // Close statements
    $stmt1->close();
    $stmt2->close();
    $stmt3->close();

    // Handle success or redirect to another page
    if ($stmt1) {
        if ($stmt2){
            if($stmt3){
                echo "<script type='text/javascript'>alert('Invoice processed successfully!'); window.location.href = 'payment.php';</script>";

            }else{
                    die("Error in SQL query: " . mysqli_error($conn));   
            }

        }else{
            die("Error in SQL query: " . mysqli_error($conn)); 
        }
        // Success: You can redirect or display a success message

    } else {
        // Handle errors
        echo "<script type='text/javascript'>alert('Error in processing Invoice!');</script>";
    }
}

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
    
    
    
    .date-range-row {
        margin-top: 15px;
    display: flex;
    justify-content: start;
    align-items: center;
   
}

.date-range-item {
     width: 100%;
    display: flex;
    flex-direction: column;
    margin-right: 20px; /* Adjust the space between From and To sections */
}

.date-label {
    margin-bottom: 5px; /* Adjust as per your design */
}

.form-control {
    /* Add your styles for input fields here */
}

</style>

</head>
<body>
<?php


include "header.php";

?>





<div class="container custom-container" style="margin-top: 0;">
<div class="container custom-container text-center" style="margin-top: 20px;">
    <div class="row grid-divider">
    <div class="">
    <h3>Invoice Payments</h3>
       
        <form method="POST" action="bulkprocess.php" id="paymentform"  >
                        <select id="company" name="company" class="form-control js-example-basic-single" required>
                            <option value="">Select Company/Tenant</option>
                            <?php
                            $result2 = mysqli_query($conn,"SELECT distinct(tenant) as tenant  FROM  `invoices` order by tenant desc");
                            while($row = mysqli_fetch_array($result2)) {
                            echo "<option value=".$row['tenant'].">".$row['tenant']." </option>";
                            }?>
                        </select>
                        
                        <div class="date-range-row">
                            <div class="date-range-item">
                                <label for="from" class="date-label">FROM</label>
                                <input type="date" id="from" name="from" class="form-control">
                            </div>
                            <div class="date-range-item">
                                <label for="to" class="date-label">TO</label>
                                <input type="date" id="to" name="to" class="form-control">
                            </div>
                        </div>

                        <div class="date-range-row">
                        <div class="date-range-item">
                        <label for="total" class="" style="margin-top: 20px;">Number of Invoices</label>
                        <input type="number" id="invoice_count" name="invoice_count" placeholder="Invoice Total" class="form-control" readonly>
                        </div>
                        <div class="date-range-item">
                        <label for="balance" class="" style="margin-top: 20px;">Total Owed</label>
                        <input type="number" id="t_owed" name="t_owed" placeholder="Total Owed" class="form-control" readonly>
                        </div>
                        </div>  
                        
                        <div class="date-range-row">
                        <div class="date-range-item">
                        <label for="pay" class="" style="margin-top: 20px;">Pay Amount</label>
                        <input type="text" id="payment" name="payment" placeholder="Pay Here" class="form-control" pattern="[0-9]+(\.[0-9]{1,2})?" title="Enter a valid number with up to two decimal places">
                        </div>
                        
                        <div class="date-range-item">
                        <label for="surplus" class="" style="margin-top: 20px;">Balance After Paying</label>
                         <input type="text" id="balance" name="balance" placeholder="Balance After Paying" class="form-control" readonly> 
                        </div>
                        </div>


         
            <div class="form-group">
                    <input type="submit" class="btn btn-warning"  id="process" name="process" style="width: 100%;" value="Process Payment">
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
                $('#totals').val(data.total_charge);
                 $('#pay').val(data.paid);
                  $('#t_owed').val(data.balance + data.total_charge);
                 
            }
        });
    });
</script>

<script>
    // Function to update invoice data
function updateInvoiceData() {
    var company = $('#company').val();
    var from = $('#from').val();
    var to = $('#to').val();

    $.ajax({
        url: 'fetchbulk.php',
        type: 'POST',
        data: {company: company, from: from, to: to},
        success: function(response) {
            // Assuming response is an object with count and total
            $('#invoice_count').val(response.count);
            $('#t_owed').val(response.total);
        }
    });
}

// Event listeners
$('#company, #from, #to').change(updateInvoiceData);

$('#payment').on('input', function() {
    var payAmount = parseFloat($(this).val()) || 0;
    var totalOwed = parseFloat($('#t_owed').val().replace(/ /g, '')) || 0; // Remove commas before calculation
    var balance = payAmount - totalOwed;

    $('#balance').val(formatMoney(balance));
});

</script>

<script>
    function formatMoney(number) {
    return new Intl.NumberFormat('en-US', {
        style: 'decimal',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(number);
}


</script>


</body>
</html>