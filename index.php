<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Login Page</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <style>
    body {
      background-color: #f8f9fa;
    }

    .login-container {
      max-width: 400px;
      margin: auto;
      margin-top: 100px;
      padding: 20px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .login-container h2 {
      text-align: center;
      margin-bottom: 30px;
    }

    .login-form {
      margin-bottom: 20px;
    }

    .login-form input {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ced4da;
      border-radius: 4px;
      box-sizing: border-box;
    }

    .login-form button {
      width: 100%;
      padding: 10px;
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .login-form button:hover {
      background-color: #0056b3;
    }

    .progress-container {
      display: none;
      margin-bottom: 20px;
    }

    .progress-bar {
      height: 20px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="login-container">
      <h2>ZenMark Invoicing System</h2>
      <div class="progress-container">
        <div class="progress">
          <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%"></div>
        </div>
      </div>
      <form class="login-form" id="loginForm">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" class="form-control" id="username" placeholder="Enter your username">
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" class="form-control" id="password" placeholder="Enter your password">
        </div>
        <button type="submit" class="btn btn-primary" id="loginBtn">Login</button>
      </form>
    </div>
  </div>

  <!-- Bootstrap JS and dependencies -->
  <!-- Replace this line with the full version of jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- ... (previous HTML code) ... -->
<script>
$(document).ready(function() {
  $("#loginForm").submit(function(event) {
    event.preventDefault();

    // Show progress bar
    $(".progress-container").show();

    let username = $("#username").val();
    let password = $("#password").val();

    // Make an AJAX request for authentication
    $.ajax({
      url: 'authenticate.php', // Replace with the actual path to your PHP script
      type: 'POST',
      data: { username: username, password: password },
      dataType: 'json', // Ensure that the response is parsed as JSON
      success: function(response) {
        // Hide progress bar
        $(".progress-container").hide();

        if (response.success) {
          // Redirect to payments.php upon successful login
          window.location.href = "home.php";
        } else {
          // Show error message (you can customize this based on your needs)
          alert(response.message || 'An unknown error occurred.');
        }
      },
      error: function() {
        // Hide progress bar
        $(".progress-container").hide();

        // Show a generic error message
        alert('An error occurred while processing your request.');
      }
    });
  });
});
</script>

<!-- ... (remaining HTML and Bootstrap/JS dependencies) ... -->

</body>
</html>
