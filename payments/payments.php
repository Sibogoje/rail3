<?php
session_start(); // Start the session (should be at the beginning of every page using sessions)
include "../database/conn.php";
// Check if the user is not logged in
if (!isset($_SESSION['userid']) || !isset($_SESSION['role'])) {
    // Redirect to the login page
    header("Location: index.php"); // Adjust the path if needed
    exit();
}

// Access session variables
$username = $_SESSION['userid'];
$role = $_SESSION['role'];


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
    $getTenantQuery = "SELECT tenant FROM invoices WHERE invoicenumber = ?";
    $stmt2 = $conn->prepare($getTenantQuery);
    $stmt2->bind_param("i", $selectedInvoiceNumber);
    $stmt2->execute();

    // Get the result as an associative array
    $result2 = $stmt2->get_result();
    $row2 = $result2->fetch_assoc();
    $tenant = $row2['tenant'];

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
    <link href="../file.css" rel="stylesheet">
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





// Fetch the user's station based on the username (Assuming you have a 'users' table with a 'station' column)
$result_user_station = mysqli_query($conn, "SELECT station FROM house WHERE station = '$role'");
$user_station_row = mysqli_fetch_assoc($result_user_station);
$user_station = $user_station_row['station'];
// Fetch the invoices based on the user's station
$result_invoices = mysqli_query($conn, "SELECT i.invoicenumber, i.house_code, i.billdate 
                                         FROM invoices i
                                         JOIN house h ON i.house_code = h.housecode
                                         WHERE h.station = '$user_station'
                                         ORDER BY i.invoicenumber DESC");


?>





<div class="container custom-container" style="margin-top: 0;">
<div class="container custom-container text-center" style="margin-top: 60px;">
    <div class="row grid-divider">
    <div class="">
    <h3>Invoice Payments</h3>
        <form method="POST" action="" id="paymentform"  >
               
                        
                        <select id="invoicenumber" name="invoicenumber" class="form-control js-example-basic-single" required>
        <option value="">Select Invoice to Process</option>
        <?php
        while ($row = mysqli_fetch_array($result_invoices)) {
            echo "<option value=" . $row['invoicenumber'] . ">" . $row['invoicenumber'] . " - " . $row['house_code'] . " - " . $row['billdate'] . "</option>";
        }
        ?>
    </select>

                        <label for="total" class="" style="margin-top: 20px;">Invoice Total</label>

                        <input type="number" id="totals" name="totals" placeholder="Invoice Total" class="form-control" readonly>

                        <label for="pay" class="" style="margin-top: 20px;">Amount Paid</label>

                        <input type="number" id="pay" name="pay" placeholder="Pay Here" class="form-control">

                        <label for="balance" class="" style="margin-top: 20px;">Balance Unpaid</label>

                        <input type="number" id="balance" name="balance" placeholder="Balance" class="form-control" readonly>

                        <label for="surplus" class="" style="margin-top: 20px;">Credit - Extra Paid</label>

                        <input type="number" id="surplus" name="surplus" placeholder="Credit" class="form-control" readonly>          
            <div class="form-group">
                    <input type="submit" class="btn btn-warning"  id="process" name="process" style="width: 100%;" value="Process Invoice">
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
            }
        });
    });
</script>


<script>
    // Function to update balance and surplus fields based on payment
    function updateBalanceAndSurplus() {
    var invoiceTotal = parseFloat($('#totals').val());
    var amountPaid = parseFloat($('#pay').val());

    if (!isNaN(invoiceTotal) && !isNaN(amountPaid)) {
        var balance = invoiceTotal - amountPaid;
        var surplus = balance < 0 ? Math.abs(balance) : 0;

        // Ensure balance is not negative
        balance = Math.max(balance, 0);

        $('#balance').val(balance.toFixed(2)); // Set balance
        $('#surplus').val(surplus.toFixed(2)); // Set surplus
    } else {
        // Handle the case where the input is not a valid number
        $('#balance').val(''); // Clear balance
        $('#surplus').val(''); // Clear surplus
    }
}
    // Attach event listener to the 'pay' input for real-time calculation
    $('#pay').on('input', updateBalanceAndSurplus);

    // Initial calculation
    updateBalanceAndSurplus();
</script>


</body>
</html>
