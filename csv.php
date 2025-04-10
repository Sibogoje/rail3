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
    // Fetch data from the invoices table and separate by type
            }else{
                    die("Error in SQL query: " . mysqli_error($conn));   ber AS `Inv Number`, 
            }  month AS `Month`, 'Water' AS `Type`, water_charge AS `Amount`,
               ROUND(water_charge * 0.15, 2) AS `VAT`,
        }else{ ROUND(water_charge * 1.15, 2) AS `Amount Incl`
            die("Error in SQL query: " . mysqli_error($conn)); 
        }HERE water_charge > 0
        // Success: You can redirect or display a success message
        SELECT house_code AS `House Nr`, tenant AS `Occupant`, invoicenumber AS `Inv Number`, 
    } else {   month AS `Month`, 'Electricity' AS `Type`, electricity_charge AS `Amount`,
        // Handle errorsctricity_charge * 0.15, 2) AS `VAT`,
        echo "<script type='text/javascript'>alert('Error in processing Invoice!');</script>";
    }   FROM invoices
}       WHERE electricity_charge > 0
        UNION ALL
if (isset($_POST['export_csv'])) {e Nr`, tenant AS `Occupant`, invoicenumber AS `Inv Number`, 
    $selectedYear = $_POST['year'];arge AS `Amount`,
    $selectedMonth = $_POST['month']; ROUND(sewage_charge * 0.15, 2) AS `VAT`,

    // Fetch data from the invoices table and separate by type within the selected month and year
    $query = "
        SELECT house_code AS `House Nr`, tenant AS `Occupant`, invoicenumber AS `Inv Number`, 
               month AS `Month`, 'Water' AS `Type`, water_charge AS `Amount`,query($query);
               ROUND(water_charge * 0.15, 2) AS `VAT`,
               ROUND(water_charge * 1.15, 2) AS `Amount Incl`num_rows > 0) {
        FROM invoices
        WHERE water_charge > 0 AND year = ? AND month = ?
        UNION ALLnvoices.csv"');
        SELECT house_code AS `House Nr`, tenant AS `Occupant`, invoicenumber AS `Inv Number`, 
               month AS `Month`, 'Electricity' AS `Type`, electricity_charge AS `Amount`,t stream
               ROUND(electricity_charge * 0.15, 2) AS `VAT`,t', 'w');
               ROUND(electricity_charge * 1.15, 2) AS `Amount Incl`
        FROM invoices
        WHERE electricity_charge > 0 AND year = ? AND month = ?, 'Amount', 'VAT', 'Amount Incl']);
        UNION ALL
        SELECT house_code AS `House Nr`, tenant AS `Occupant`, invoicenumber AS `Inv Number`, 
               month AS `Month`, 'Sewage' AS `Type`, sewage_charge AS `Amount`, $result->fetch_assoc()) {
               ROUND(sewage_charge * 0.15, 2) AS `VAT`,ow);
               ROUND(sewage_charge * 1.15, 2) AS `Amount Incl`  }
        FROM invoices
        WHERE sewage_charge > 0 AND year = ? AND month = ?        fclose($output);
    ";

    $stmt = $conn->prepare($query);lable to export.');</script>";
    $stmt->bind_param("isisis", $selectedYear, $selectedMonth, $selectedYear, $selectedMonth, $selectedYear, $selectedMonth);
    $stmt->execute();}
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="invoices.csv"');<title>CSV</title>
 content="width=device-width, initial-scale=1">
        // Open output streambootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
        $output = fopen('php://output', 'w');esheet">
pt src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
        // Write column headers    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.debug.js"></script>
        fputcsv($output, ['House Nr', 'Occupant', 'Inv Number', 'Month', 'Type', 'Amount', 'VAT', 'Amount Incl']);maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
ttps://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
        // Write rowshttps://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, $row);
        }
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        fclose($output);cript src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        exit;
    } else {>
        echo "<script>alert('No data available to export for the selected month and year.');</script>";ustom-container {
    }5px;
}

?> larger */

<html>
<head>
<title>CSV</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">    }
    <link href="file.css" rel="stylesheet">
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>    /* For screens with a width of less than 768px (mobile) */
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.debug.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">            width: 100%;
<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'> }



<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />    
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    .custom-container {art;
        padding: 15px;
    }

    /* For screens with a width of 768px or larger */
    @media (min-width: 768px) {
        .custom-container {
            width: 70%;
            margin: 0 auto; /* Center the container horizontally */mn;
        }n-right: 20px; /* Adjust the space between From and To sections */
    }

    /* For screens with a width of less than 768px (mobile) */e-label {
    @media (max-width: 767px) {margin-bottom: 5px; /* Adjust as per your design */
        .custom-container {
            width: 100%;
        }
    }input fields here */
    
    
    /style>
    .date-range-row {
        margin-top: 15px;
    display: flex;
    justify-content: start;
    align-items: center;
   
}nclude "header.php";

.date-range-item {
     width: 100%;
    display: flex;
    flex-direction: column;
    margin-right: 20px; /* Adjust the space between From and To sections */
}
div class="container custom-container" style="margin-top: 0;">
.date-label {<div class="container custom-container text-center" style="margin-top: 20px;">
    margin-bottom: 5px; /* Adjust as per your design */ class="row grid-divider">
}    <div class="">
>Invoice Payments</h3>
.form-control { 
    /* Add your styles for input fields here */   <form method="POST" action="bulkprocess.php" id="paymentform"  >
}                        
                        
</style>   <div class="date-range-row">
                            <div class="date-range-item">
</head>                              <label for="year" class="date-label">Year</label>
<body>                                <select id="year" name="year" class="form-control">
<?php                                    <option value="">Select Year</option>
                                    <?php for ($y = 2021; $y <= 2025; $y++): ?>
                                        <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
include "header.php";                                    <?php endfor; ?>

?>
lass="date-range-item">
              <label for="month" class="date-label">Month</label>
   <select id="month" name="month" class="form-control">
                             <option value="">Select Month</option>

<div class="container custom-container" style="margin-top: 0;">            $months = [
<div class="container custom-container text-center" style="margin-top: 20px;">                "January", "February", "March", "April", "May", 
    <div class="row grid-divider">y", "August", "September", "October", 
    <div class="">mber"
    <h3>Invoice Payments</h3>
       
        <form method="POST" action="bulkprocess.php" id="paymentform"  >+ 1; ?>"><?php echo $month; ?></option>
                        
                        
                        <div class="date-range-row">
                            <div class="date-range-item">
                                <label for="year" class="date-label">Year</label>
                                <select id="year" name="year" class="form-control">
                                    <option value="">Select Year</option>
                                    <?php for ($y = 2021; $y <= 2025; $y++): ?>process" style="width: 100%;" value="Export CSV">
                                        <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="date-range-item">
                                <label for="month" class="date-label">Month</label>
                                <select id="month" name="month" class="form-control">="">
                                    <option value="">Select Month</option>s" style="width: 100%;">Export CSV</button>
                                    <?php 
                                    $months = [
                                        "January", "February", "March", "April", "May", 
                                        "June", "July", "August", "September", "October", 
                                        "November", "December"
                                    ];</div>
                                    foreach ($months as $index => $month): ?>
                                        <option value="<?php echo $index + 1; ?>"><?php echo $month; ?></option>
                                    <?php endforeach; ?>
                                </select>/javascript">
                            </div>ready(function() {
                        </div>ngle').select2();

         
            <div class="form-group">
                    <input type="submit" class="btn btn-warning"  id="process" name="process" style="width: 100%;" value="Export CSV">
            </div>
            ent listener to the select element
                           enumber').on('change', function () {
                              // Get the selected invoicenumber
        </form>  var selectedInvoiceNumber = $(this).val();
        <div class="form-group">  
            <form method="POST" action="">  // Make an AJAX request to fetch data from the database
                <button type="submit" name="export_csv" class="btn btn-success" style="width: 100%;">Export CSV</button>        $.ajax({
            </form>            url: 'fetch_data.php', // Replace with the PHP script that fetches data
        </div>            type: 'POST',
</div>er: selectedInvoiceNumber },
</div>// Assuming the data returned is in JSON format
</div>
</div>             // Populate the balance and surplus textboxes with the retrieved data
                $('#balance').val(data.balance);
       $('#surplus').val(data.surplus);
                $('#totals').val(data.total_charge);
<script type="text/javascript">         $('#pay').val(data.paid);
$(document).ready(function() {a.total_charge);
    $('.js-example-basic-single').select2();
});

</script>

<script>
    // Attach an event listener to the select element
    $('#invoicenumber').on('change', function () { invoice data
        // Get the selected invoicenumber
        var selectedInvoiceNumber = $(this).val();
        
        // Make an AJAX request to fetch data from the database
        $.ajax({
            url: 'fetch_data.php', // Replace with the PHP script that fetches data
            type: 'POST',
            data: { invoicenumber: selectedInvoiceNumber },
            dataType: 'json', // Assuming the data returned is in JSON format
            success: function (data) {function(response) {
                // Populate the balance and surplus textboxes with the retrieved data/ Assuming response is an object with count and total
                $('#balance').val(data.balance); $('#invoice_count').val(response.count);
                $('#surplus').val(data.surplus);     $('#t_owed').val(response.total);
                $('#totals').val(data.total_charge);
                 $('#pay').val(data.paid);    });
                  $('#t_owed').val(data.balance + data.total_charge);
                 
            }
        });dateInvoiceData);
    });
</script>() {
    var payAmount = parseFloat($(this).val()) || 0;
<script>lOwed = parseFloat($('#t_owed').val().replace(/ /g, '')) || 0; // Remove commas before calculation
    // Function to update invoice data totalOwed;
function updateInvoiceData() {
    var company = $('#company').val();
    var year = $('#year').val();
    var month = $('#month').val();

    $.ajax({
        url: 'fetchbulk.php',
        type: 'POST',ction formatMoney(number) {
        data: {company: company, year: year, month: month},   return new Intl.NumberFormat('en-US', {
        success: function(response) {        style: 'decimal',
            // Assuming response is an object with count and totalctionDigits: 2,
            $('#invoice_count').val(response.count);
            $('#t_owed').val(response.total);    }).format(number);
        }
    });
}

// Event listeners
$('#company, #year, #month').change(updateInvoiceData);
ody>
$('#payment').on('input', function() {</html>    var payAmount = parseFloat($(this).val()) || 0;    var totalOwed = parseFloat($('#t_owed').val().replace(/ /g, '')) || 0; // Remove commas before calculation    var balance = payAmount - totalOwed;    $('#balance').val(formatMoney(balance));});</script><script>    function formatMoney(number) {    return new Intl.NumberFormat('en-US', {        style: 'decimal',        minimumFractionDigits: 2,        maximumFractionDigits: 2    }).format(number);}


</script>


</body>
</html>