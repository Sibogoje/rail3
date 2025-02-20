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
    
    $house = $_POST['house'];
    $fault = $_POST['fault'];
    $report_date = $_POST['repodate'];
    $reporter = $_POST['reporter'];
    $status = "Reported";

    // Prepare and bind statement
    $stmt = $conn->prepare("INSERT INTO faults (house, fault, report_date, status, reporter) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $house, $fault, $report_date, $status, $reporter);

    // Execute the prepared statement
    if ($stmt->execute()) {
        echo "<script type='text/javascript'>alert('Fault Reported!'); window.location.href = 'faults.php';</script>";
    } else {
        echo "<script type='text/javascript'>alert('There was a problem reporting the fault!'); window.location.href = 'faults.php';</script>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $fix_date = $_POST['fix_date'];
    $fault = $_POST['fault'];

    $query = "UPDATE faults SET status='$status', fix_date='$fix_date', fault='$fault' WHERE id='$id'";
    if ($conn->query($query) === TRUE) {
        header("Location: faults.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

?>

<html>
<head>
<title>House Faults</title>
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
            width: 100%;
            margin: 0 auto; /* Center the container horizontally */
        }
    }

    /* For screens with a width of less than 768px (mobile) */
    @media (max-width: 767px) {
        .custom-container {
            width: 100%;
        }
    }
    
    .custom-modal .modal-dialog {
    margin-top: 90px;  /* Adjust this value as needed */
}

</style>

</head>
<body>
<?php


include "header.php";

?>


<!-- Update Modal -->
<div class="modal fade custom-modal" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content my-auto">
      <div class="modal-header">
        <h5 class="modal-title" id="updateModalLabel">Update Fault</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="" method="post">
        <div class="modal-body">
          <input type="hidden" id="edit_id" name="id">
          <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" class="form-control">
              <option value="Reported">Reported</option>
              <!-- Add other statuses as needed -->
              <option value="Fixed">Fixed</option>
              <option value="Pending">Pending</option>
              <option value="False Report">False Report</option>
            </select>
          </div>
          <div class="form-group">
            <label for="fix_date">Fix Date</label>
            <input type="date" id="fix_date" name="fix_date" class="form-control">
          </div>
          <div class="form-group">
            <label for="fault_description">Fault</label>
            <textarea id="fault_description" name="fault" class="form-control"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" name="update" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="container custom-container" style="margin-bottom: 80px;">
<div class="container custom-container text-center" style="margin-top: 50px;">
    <div class="row grid-divider">

    <h3>Report Fault</h3>
        <?php $actio = "pri/index.php"; ?>
    <form method="POST" action="" id="paymentform">
    <div class="col-md-3 form-group">
        <label for="house">House</label>
        <select id="house" name="house" class="form-control js-example-basic-single" required>
            <option value="">Select House</option>
            <?php
            $result2 = mysqli_query($conn,"SELECT housecode FROM `house` order by housecode desc");
            while($row = mysqli_fetch_array($result2)) {
                echo "<option value=".$row['housecode'].">".$row['housecode']."</option>";
            }?>
        </select>
    </div>
      <div class="col-md-3 form-group">
          <label for="types">Fault Type</label>
        <select id="types" name="types" class="form-control js-example-basic-single" required>
            <option value="">Fault Type</option>
            <option value="Electricity">Electricity</option>
            <option value="Water">Water</option>
        </select>
    </div>

    <div class="row">
        <div class="col-md-3 form-group"> <!-- For medium devices and larger, use half of the width -->
            <label for="repodate">Report date</label>
            <input type="date" id="repodate" name="repodate" placeholder="Pay Here" class="form-control">
        </div>

        <div class="col-md-3 form-group"> <!-- For medium devices and larger, use half of the width -->
            <label for="reporter">Person Who Reported</label>
            <input type="text" id="reporter" name="reporter" placeholder="Reporter Name" class="form-control">
        </div>
         <div class="col-md-8 form-group"> <!-- For medium devices and larger, use half of the width -->
            
            <textarea type="text" id="fault" name="fault" placeholder="Fault in Detail" class="form-control"></textarea>
        </div>
        
    
    <div class="col-md-4 form-group">
        <input type="submit" class="btn btn-warning" id="process" name="process" style="width: 100%;" value="Report Fault">
    </div>
    </div>
</form>
<?php
// Retrieve all faults from the database
$query = "SELECT * FROM faults";
$result = $conn->query($query);
?>

<!-- Your existing HTML form goes here -->

<?php
// Pagination
$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;
$query = "SELECT * FROM faults LIMIT $start, $limit";
$result = $conn->query($query);

// For pager
$result1 = $conn->query("SELECT COUNT(id) AS id FROM faults");
$faultCount = $result1->fetch_all(MYSQLI_ASSOC);
$total = $faultCount[0]['id'];
$pages = ceil( $total / $limit );

$previous = $page - 1;
$next = $page + 1;

?>


<!-- Table to display the faults -->
<h3>All House faults</h3>
<div class="container mt-5">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>House</th>
                <th>Fault</th>
                <th>Report Date</th>
                <th>Fix Date</th>
                <th>Status</th>
                <th>Reporter</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['house']; ?></td>
                    <td><?php echo $row['fault']; ?></td>
                    <td><?php echo $row['report_date']; ?></td>
                    <td><?php echo $row['fix_date']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td><?php echo $row['reporter']; ?></td>
                    <td><a href="edit_fault.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm edit-btn">Edit</a></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <ul class="pagination">
        <li class="page-item"><a href="faults.php?page=<?php echo $previous; ?>" class="page-link">Previous</a></li>
        <?php for($i = 1; $i<= $pages; $i++) : ?>
            <li class="page-item"><a href="faults.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a></li>
        <?php endfor; ?>
        <li class="page-item"><a href="faults.php?page=<?php echo $next; ?>" class="page-link">Next</a></li>
    </ul>
</div>

<?php
// Close connection
$conn->close();
?>



</div>
</div>
</div>



<script type="text/javascript">
$(document).ready(function() {
    $('.js-example-basic-single').select2();
});

</script>


<script>
    $(document).on('click', '.edit-btn', function() {
        event.preventDefault();  // Prevents default behavior of the button
  let row = $(this).closest('tr');
  let id = row.find('td:eq(0)').text();
  let fault = row.find('td:eq(2)').text();
  let fixDate = row.find('td:eq(4)').text();
  let status = row.find('td:eq(5)').text();

  $('#edit_id').val(id);
  $('#fault_description').val(fault);
  $('#fix_date').val(fixDate);
  $('#status').val(status);

  $('#updateModal').modal('show');
});

</script>



</body>
</html>