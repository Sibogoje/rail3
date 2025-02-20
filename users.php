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



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    // Retrieve form data
    $id = $_POST["id"];
    $userid = $_POST["userid"];
    $email = $_POST["email"];
    $role = $_POST["role"];
    $newPassword = $_POST["password"]; // Assuming the new password is sent from the form

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    // Update statement
    $sql = "UPDATE `u747325399_Railnew`.`users`
            SET `userid` = '$userid',
                `email` = '$email',
                `password` = '$hashedPassword',  -- Use the hashed password
                `role` = '$role'
            WHERE `id` = '$id'";

    // Execute the update query
    if (mysqli_query($conn, $sql)) {
        echo "<script type='text/javascript'>alert('User Updated Successfully!'); window.location.href = 'users.php';</script>";
    } else {
        echo "<script type='text/javascript'>alert('Update was not Successful!'); window.location.href = 'users.php';</script>";
    }
}

// Close the database connection
//mysqli_close($conn);


if (isset($_POST['Add'])) {
    // Retrieve values from the form
    $userid = $_POST['userid'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Perform SQL INSERT query
    $query = "INSERT INTO users (userid, email, role, password) VALUES ('$userid', '$email', '$role', '$hashedPassword')";

    if ($conn->query($query) === TRUE) {
        // Insert successful
        echo "<script type='text/javascript'>alert('User Added Successfully!'); window.location.href = 'users.php';</script>";
    } else {
        // Insert failed
        echo "<script type='text/javascript'>alert('There was an error adding the User!'); window.location.href = 'users.php';</script>";
    }

    // Close the database connection
    $conn->close();
}


?>


<html>
<head>
<title>Users</title>
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
        <h5 class="modal-title" id="updateModalLabel">Update User Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="" method="post">
        <div class="modal-body">
          <input type="hidden" id="edit_id" name="id">
          <div class="form-group">
            <label for="house_number">UserID</label>
            <input type="text" id="userid" name="userid" class="form-control">
          </div>
          <div class="form-group">
            <label for="tenant">Email</label>
            <input type="text" id="email" name="email" class="form-control">
          </div>
          <div class="form-group">
            <label for="station">Role/Station</label>
            <input type="text" id="role" name="role" class="form-control">
          </div>
          <div class="form-group">
            <label for="electricity">Password</label>
            <input type="text" id="password" name="password" class="form-control">
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
                <h5 class="modal-title" id="addHouseModalLabel">Add New User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Your four text fields go here -->
                <form action="" method="post">
                    <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="userid">UserID</label>
                    <input type="text" class="form-control" id="userid" name="userid">
                </div>
                  <div class="form-group col-md-6">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email">
                </div>
                </div>
                <div class="form-row">

                    <div class="form-group col-md-6">
                    <label for="station">Station</label>
                    <select class="form-control " id="role" name="role">
                        <?php
                        // Assuming you have a connection to the database named $conn
                        $result = mysqli_query($conn, "SELECT id, name FROM stations");
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="electricityMeter">Password</label>
                    <input type="text" class="form-control" id="password" name="password">
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
    <i class="fa fa-plus" aria-hidden="true"></i> Add New User
</a>



<div class="container">





<div class="custom-container" style="margin-bottom: 80px; margin-top: 60px">

<?php
// Retrieve all faults from the database
$query = "SELECT * FROM users";
$result = $conn->query($query);
?>

<!-- Your existing HTML form goes here -->

<?php
// Pagination
$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;
$query = "SELECT * FROM users LIMIT $start, $limit";
$result = $conn->query($query);

// For pager
$result1 = $conn->query("SELECT COUNT(id) AS userid FROM users");
$faultCount = $result1->fetch_all(MYSQLI_ASSOC);
$total = $faultCount[0]['userid'];
$pages = ceil( $total / $limit );

$previous = $page - 1;
$next = $page + 1;

?>
<!-- Table to display the faults -->
<h3>Users</h3>
<div class="container mt-5">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                        <th>ID</th>
						<th>UserID</th>
						<th>Email</th>
                        <th>Role</th>
						<th>Password</th>
                        <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row["id"]; ?></td>
					<td><?php echo $row["userid"]; ?></td>
                    <td><?php echo $row["email"]; ?></td>
					<td><?php echo $row["role"]; ?></td>
					<td><?php echo $row["password"]; ?></td>
					
                    <td><a href="edit_fault.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm edit-btn"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                    <a href="edit_fault.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm tenant-btn"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
                </tr>
            <?php endwhile; ?>
        </tbody>

    </table>

    <!-- Pagination -->
    <ul class="pagination">
        <li class="page-item"><a href="houses2.php?page=<?php echo $previous; ?>" class="page-link">Previous</a></li>
        <?php for($i = 1; $i<= $pages; $i++) : ?>
            <li class="page-item"><a href="houses2.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a></li>
        <?php endfor; ?>
        <li class="page-item"><a href="houses2.php?page=<?php echo $next; ?>" class="page-link">Next</a></li>
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
   let userid = row.find('td:eq(1)').text();
  let email = row.find('td:eq(2)').text();
  let role = row.find('td:eq(3)').text();
  let password = row.find('td:eq(4)').text();


 $('#edit_id').val(edit_id);
  $('#userid').val(userid);
  $('#email').val(email);
  $('#role').val(role);
 // $('#password').val(password);


  $('#updateModal').modal('show');
});

</script>

<script>
$(document).on('click', '.tenant-btn', function(event) {
    event.preventDefault();  // Prevents default behavior of the button

    let row = $(this).closest('tr');
    let id = row.find('td:eq(0)').text();

    // Make an AJAX request to delete.php
    $.ajax({
        url: 'deleteuser.php',
        type: 'POST',
        data: { id: id },
        success: function(response) {
            // Handle the response from the server
            if (response.success) {
                // Optionally, you can remove the row from the table
                row.remove();
                alert('User deleted successfully!');
            } else {
                alert('Failed to delete User. Please try again.');
            }
        },
        error: function() {
            alert('An error occurred while processing your request.');
        }
    });
});

</script>

</body>
</html>