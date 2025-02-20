<?php
session_start();
include "database/conn.php";
if (!isset($_SESSION['userid']) || !isset($_SESSION['role'])) {
    // Redirect to the login page
    header("Location: index.php"); // Adjust the path if needed
    exit();
}

// Access session variables
$username = $_SESSION['userid'];
$role = $_SESSION['role'];



if (isset($_POST['update2'])) {

    // Retrieve data from the form
    $house_number1 = $_POST['house_number1'];
    $tenant1 = $_POST['tenant1'];
    $newtenant = $_POST['newtenant'];
    $reason = $_POST['reason'];
    $vacate = $_POST['vacate'];

    // Save data to former_tenant table
    $insertFormerTenantQuery = "INSERT INTO former_tenant (`t_id`, `house`, `reason`, `to`) VALUES ('$tenant1', '$house_number1', '$reason', '$vacate')";

    if (mysqli_query($conn, $insertFormerTenantQuery)) {
       // echo "Record saved to former_tenant table successfully";
    $updateHouseQuery = "UPDATE house SET tcode = '$newtenant' WHERE housecode = '$house_number1'";

            if (mysqli_query($conn, $updateHouseQuery)) {
                echo "<script type='text/javascript'>alert('Tenant Updated Succeesfully!'); window.location.href = 'houses2.php';</script>";
               
            } else {
                echo "<script type='text/javascript'>alert('Error updating record: ' . mysqli_error($conn)!'); window.location.href = 'houses2.php';</script>";
            }
    } else {
        echo "Error: " . $insertFormerTenantQuery . "<br>" . mysqli_error($conn);
        // echo "<script type='text/javascript'>alert('Error Inserting record: ' . mysqli_error($conn)!'); window.location.href = 'houses2.php';</script>";
    }
    // Close the database connection
    mysqli_close($conn);
}



if (isset($_POST['update'])) {

    // Retrieve data from the form
    $id = $_POST['id'];
    $house_number = $_POST['house_number'];
    $tenant = $_POST['tenant'];
    $station = $_POST['station'];
    $electricity = $_POST['electricity'];
    $water = $_POST['water'];

    // Perform the update query
    $sql = "UPDATE house SET
            housecode = '$house_number',
            tcode = '$tenant',
            station = '$station',
            electricity_meter = '$electricity',
            water_meter = '$water'
            WHERE autoid = '$id'";

    if (mysqli_query($conn, $sql)) {
       // echo "Record updated successfully";
        echo "<script type='text/javascript'>alert('Record updated successfully!'); window.location.href = 'houses2.php';</script>";
    } else {
        //echo "Error updating record: " . mysqli_error($conn);
         echo "<script type='text/javascript'>alert('Error updating record: ' . mysqli_error($conn)!'); window.location.href = 'houses2.php';</script>";
    }

    // Close the database connection
    mysqli_close($conn);
}


if (isset($_POST['Add'])) {
    // Retrieve values from the form
    $houseNumber = $_POST['houseNumber'];
    $tenant = $_POST['tenant2'];
    $station = $_POST['station'];
    $electricityMeter = $_POST['electricityMeter'];
    $waterMeter = $_POST['waterMeter'];

    // Perform SQL INSERT query
    $query = "INSERT INTO house (housecode, tcode, station, electricity_meter, water_meter) VALUES ('$houseNumber', '$tenant', '$station', '$electricityMeter', '$waterMeter')";
    
    if ($conn->query($query) === TRUE) {
        // Insert successful
        echo "<script type='text/javascript'>alert('House Added Succesfully!'); window.location.href = 'houses2.php';</script>";
    } else {
        // Insert failed
       echo "<script type='text/javascript'>alert('There was an errror adding the house!'); window.location.href = 'houses2.php';</script>";
    }
// Close the database connection
$conn->close();    
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

    /* Style for the fixed button */
    .fixed-button {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 999;
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
        <h5 class="modal-title" id="updateModalLabel">Update House Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="" method="post">
        <div class="modal-body">
          <input type="hidden" id="edit_id" name="id">
          <div class="form-group">
            <label for="house_number">House Code</label>
            <input type="text" id="house_number" name="house_number" class="form-control">
          </div>
          <div class="form-group">
            <label for="tenant">Tenant</label>
            <input type="text" id="tenant" name="tenant" class="form-control">
          </div>
          <div class="form-group">
            <label for="station">Station</label>
            <input type="text" id="station" name="station" class="form-control">
          </div>
          <div class="form-group">
            <label for="electricity">Electricity Meter</label>
            <input type="text" id="electricity" name="electricity" class="form-control">
          </div>
          <div class="form-group">
            <label for="water">Water Meter</label>
            <input type="text" id="water" name="water" class="form-control">
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


<!-- Edit AC Modal -->
<div class="modal fade custom-modal" id="editACModal" tabindex="-1" role="dialog" aria-labelledby="editACModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editACModalLabel">Edit AC Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form to edit AC information -->
                <form action="edit_ac_process.php" method="post">
                    <input type="hidden" id="acedit_id" name="acedit_id">
                    <input type="hidden" id="house_id" name="house_id">
                    <div class="form-group">
                        <label for="brand">AC Brand</label>
                        <input type="text" class="form-control" id="brand" name="brand">
                    </div>
                    <div class="form-group">
                        <label for="model">Model</label>
                        <input type="text" class="form-control" id="model" name="model">
                    </div>
                    <div class="form-group">
                        <label for="size">Size</label>
                        <input type="text" class="form-control" id="size" name="size">
                    </div>
                    <div class="form-group">
                        <label for="ser_in">Serial In</label>
                        <input type="text" class="form-control" id="ser_in" name="ser_in">
                    </div>
                    <div class="form-group">
                        <label for="ser_out">Serial Out</label>
                        <input type="text" class="form-control" id="ser_out" name="ser_out">
                    </div>
                    <!-- Add other AC fields as needed -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" name="Updateac">Update AC</button>
            </div>
            </form>
        </div>
    </div>
</div>



<!-- Update Modal -->
<div class="modal fade custom-modal" id="updateModal1" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content my-auto">
      <div class="modal-header">
        <h5 class="modal-title" id="updateModalLabel1"><label id=""></label></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="" method="post">
        <div class="modal-body">
          <input type="text" class="form-control" id="house_number1" name="house_number1" readonly>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="house_number1">Old tenant</label>
            <input type="text" id="tenant1" name="tenant1" class="form-control" readonly>
        </div>
    </div>
<div class="col-md-6">
    <div class="form-group">
        <label for="newtenant">New Tenant</label>
        <select id="newtenant" name="newtenant" class="form-control">
            <?php
            // Assuming you have a connection to the database named $conn
            $result = mysqli_query($conn, "SELECT * FROM tenant_info");
            echo "<option value='empty'>No tenant</option>";
            while ($row = mysqli_fetch_array($result)) {
                echo "<option value='" . $row['t_code'] . "'>" . $row['fullname'] . "</option>";
            }
            ?>
        </select>
    </div>
</div>

</div>

          <div class="form-group">
            <label for="station">Reason</label>
            <input type="text" id="reason" name="reason" class="form-control">
          </div>
          
          <div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="vacate">Vacate Date</label>
            <input type="date" id="vacate" name="vacate" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="entry">New Entry Date</label>
            <input type="date" id="entry" name="entry" class="form-control">
        </div>
    </div>
</div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" name="update2" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Modal for adding a new house -->
<!-- Modal for adding a new house -->
<div class="modal fade custom-modal" id="addHouseModal" tabindex="-1" role="dialog" aria-labelledby="addHouseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addHouseModalLabel">Add New House</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Your four text fields go here -->
                <form action="" method="post">
                <div class="form-group">
                    <label for="houseNumber">House Number</label>
                    <input type="text" class="form-control" id="houseNumber" name="houseNumber">
                </div>
                <div class="form-row">
                                        <div class="form-group col-md-6">
                    <label for="station">Tenant</label>
                    <select class="form-control " id="tenant" name="tenant2">
                        <?php
                        // Assuming you have a connection to the database named $conn
                        $result = mysqli_query($conn, "SELECT t_code, fullname FROM tenant_info");
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<option value='" . $row['t_code'] . "'>" . $row['fullname'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                    <div class="form-group col-md-6">
                    <label for="station">Station</label>
                    <select class="form-control " id="station" name="station">
                        <?php
                        // Assuming you have a connection to the database named $conn
                        $result = mysqli_query($conn, "SELECT id, name FROM stations");
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                </div>
                <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="electricityMeter">Electricity Meter</label>
                    <input type="text" class="form-control" id="electricityMeter" name="electricityMeter">
                </div>
                 <div class="form-group col-md-6">
                    <label for="electricityMeter">Water Meter</label>
                    <input type="text" class="form-control" id="electricityMeter" name="waterMeter">
                </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" name="Add">Add House</button>
            </div>
            </form>
        </div>
    </div>
</div>



<!-- Add new house button -->
<a href="#" class="btn btn-primary fixed-button" data-toggle="modal" data-target="#addHouseModal">
    <i class="fa fa-plus" aria-hidden="true"></i> Add New House
</a>



<div class="container">





<div class="custom-container" style="margin-bottom: 80px; margin-top: 60px">

<?php
// Retrieve all faults from the database
$query = "SELECT * FROM house";
$result = $conn->query($query);
?>

<!-- Your existing HTML form goes here -->

<div class="container mt-3">
    <form method="GET" action="houses2.php">
        <div class="form-group">
            <label for="search">Search:</label>
            <input type="text" class="form-control" id="search" name="search" placeholder="Enter keyword" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>

<?php
// Pagination
$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Retrieve search query if provided
$search = isset($_GET['search']) ? $_GET['search'] : '';


// Modify the query to include the search condition and use LEFT JOIN to fetch brand from airconditioners table
$query = "SELECT house.*, airconditioners.brand
          FROM house
          LEFT JOIN airconditioners ON house.ac_code = airconditioners.ac_id
          WHERE house.housecode LIKE '%$search%'
             OR house.tcode LIKE '%$search%'
             OR house.station LIKE '%$search%'
             OR house.electricity_meter LIKE '%$search%'
             OR house.water_meter LIKE '%$search%'
          LIMIT $start, $limit";

$result = $conn->query($query);

// For pager
$result1 = $conn->query("SELECT COUNT(autoid) AS autoid FROM house");
$faultCount = $result1->fetch_all(MYSQLI_ASSOC);
$total = $faultCount[0]['autoid'];
$pages = ceil($total / $limit);

$previous = $page - 1;
$next = $page + 1;
?>

<!-- Table to display the faults -->
<h3>House Data</h3>
<div class="container mt-5">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>HOUSE NO</th>
                <th>TENANT</th>
                <th>STATION</th>
                <th>ELECTRICITY MTR</th>
                <th>WATER MTR</th>
                <th>AC Brand</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row["autoid"]; ?></td>
                    <td><?php echo $row["housecode"]; ?></td>
                    <td><?php echo $row["tcode"]; ?></td>
                    <td><?php echo $row["station"]; ?></td>
                    <td><?php echo $row["electricity_meter"]; ?></td>
                    <td><?php echo $row["water_meter"]; ?></td>
                    <td><?php echo $row["brand"]; ?></td>
                    <td>
                        <a href="edit_fault.php?id=<?php echo $row['autoid']; ?>" title="House Edit" class="btn btn-primary btn-sm edit-btn"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                        <a href="edit_fault.php?id=<?php echo $row['housecode']; ?>" title="Tenant" class="btn btn-primary btn-sm tenant-btn"><i class="fa fa-users" aria-hidden="true"></i></a>
                        <a href="#" data-id="<?php echo $row['ac_code']; ?>" data-house="<?php echo $row['autoid']; ?>" title="AC" class="btn btn-primary btn-sm ac-btn"><i class="fa fa-cog" aria-hidden="true"></i></a>

                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>



    <!-- Pagination -->
    <ul class="pagination">
        <li class="page-item"><a href="houses2.php?page=<?php echo $previous; ?>&search=<?php echo $search; ?>" class="page-link">Previous</a></li>
        <?php for ($i = 1; $i <= $pages; $i++) : ?>
            <li class="page-item"><a href="houses2.php?page=<?php echo $i; ?>&search=<?php echo $search; ?>" class="page-link"><?php echo $i; ?></a></li>
        <?php endfor; ?>
        <li class="page-item"><a href="houses2.php?page=<?php echo $next; ?>&search=<?php echo $search; ?>" class="page-link">Next</a></li>
    </ul>
</div>

<?php
// Close connection
$conn->close();
?>
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
  let edit_id = row.find('td:eq(0)').text();
  let house_number = row.find('td:eq(1)').text();
  let tenant = row.find('td:eq(2)').text();
  let station = row.find('td:eq(3)').text();
  let electricity = row.find('td:eq(4)').text();
  let water = row.find('td:eq(5)').text();

 $('#edit_id').val(edit_id);
  $('#house_number').val(house_number);
  $('#tenant').val(tenant);
  $('#station').val(station);
  $('#electricity').val(electricity);
   $('#water').val(water);

  $('#updateModal').modal('show');
});

</script>


<script>
$(document).on('click', '.ac-btn', function() {
    event.preventDefault();

    // Retrieve the AC ID from the data attribute
    let ac_id = $(this).data('id');
    let house_id = $(this).data('house');

    // Use AJAX to fetch data from the server
    $.ajax({
        url: 'get_ac_data.php', // Replace with the actual path to your server-side script
        method: 'POST',
        data: { ac_id: ac_id},
        dataType: 'json',
        success: function(response) {
            // Check if the response is empty (no AC data)
            if ($.isEmptyObject(response)) {
                // Set the modal in "add new" state
                $('#acedit_id').val('');
                $('#brand').val('');
                 $('#model').val('');
                  $('#size').val('');
                   $('#ser_in').val('');
                    $('#ser_out').val('');
                     $('#house_id').val(house_id);
                // Add other fields as needed

                // Show the edit AC modal
                $('#editACModal').modal('show');
            } else {
                // Update the modal with the fetched data
                $('#acedit_id').val(response.edit_id);
                $('#brand').val(response.brand);
                $('#model').val(response.model);
                $('#size').val(response.size);
                $('#ser_in').val(response.ser_in);
                $('#ser_out').val(response.ser_out);
                 $('#house_id').val(house_id);
                // Add other fields as needed

                // Show the edit AC modal
                $('#editACModal').modal('show');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error: ' + status + ' - ' + error);
        }
    });
});


</script>


<script>
    $(document).on('click', '.tenant-btn', function() {
        event.preventDefault();  // Prevents default behavior of the button
  let row = $(this).closest('tr');
  let tenant_id = row.find('td:eq(0)').text();
  let house_number1 = row.find('td:eq(1)').text();
  let tenant1 = row.find('td:eq(2)').text();


 $('#tenant_id').val(tenant_id);
  $('#house_number1').val(house_number1);
  $('#tenant1').val(tenant1);

  $('#updateModal1').modal('show');
});

</script>

</body>
</html>