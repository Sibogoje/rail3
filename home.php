<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "database/conn.php";

if (!isset($_SESSION['userid']) || !isset($_SESSION['role']))
{
    // Redirect to the login page
    header("Location: index.php"); // Adjust the path if needed
    exit();
}

// Access session variables
$username = $_SESSION['userid'];
$role = $_SESSION['role'];


?>
<html>
<head>
<title>Home</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="file.css" rel="stylesheet">
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>

  

<style>
@media ( min-width: 768px ) {
    .grid-divider {
        position: relative;
        padding: 0;
    }
    .grid-divider>[class*='col-'] {
        position: static;
    }
    .grid-divider>[class*='col-']:nth-child(n+2):before {
        content: "";
        
        position: absolute;
        top: 0;
        bottom: 0;
    }
    .col-padding {
        padding: 0 15px;
    }
    .col-sm-2{
        width:100%;
    }
}
</style>
</head>
<body>
<?php
//$ff = $_SESSION["id"];
include "header.php";
?>



<div class="container" >
 

    <div class="container" style="margin-top: 60px;">
   
    

<div class="table-responsive">
<table class="table table-striped table-hover">             
<tbody>
<tr>
<td>
<div class="col-sm-2 btn sdv" style="width:110px; background-color:#181C16; margin: 6px; padding: 10px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
      <div class="col-padding">
        <h6 style="font-weight: bold; color: white;">Sidvokodvo</h6>
      </div>
    </div>
    <div class="col-sm-2 btn" style="width:110px; background-color:rgba(12, 104, 161, 0.85); margin: 6px; padding: 10px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
      <div class="col-padding">
        <h6 style="font-weight: bold; color: white;">Mlawula</h6>
      </div>
    </div>
    <div class="col-sm-2 btn" style="width:110px; background-color:rgba(76, 12, 161, 0.85); margin: 6px; padding: 10px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
      <div class="col-padding">
        <h6 style="font-weight: bold;color: white;">Mpaka</h6>
      </div>
    </div>
    <div class="col-sm-2 btn" style="width:110px; background-color:rgba(31, 4, 66, 0.85); margin: 6px; padding: 10px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
      <div class="col-padding">
        <h6 style="font-weight: bold;color: white;">Nsoko</h6>
      </div>
    </div>
    <div class="col-sm-2 btn" style="width:110px; background-color:rgba(66, 4, 9, 0.85); margin: 6px; padding: 10px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
      <div class="col-padding">
        <h6 style="font-weight: bold;color: white;">Lavumisa</h6>
      </div>
    </div>
    <div class="col-sm-2 btn" style="width:110px; background-color:rgba(9, 66, 4, 0.85); margin: 6px; padding: 10px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
      <div class="col-padding">
        <h6 style="font-weight: bold;color: white;">Phuzamoya</h6>
      </div>
    </div>
    <div class="col-sm-2 btn" style="width:110px; background-color:rgba(218, 11, 32, 0.85); margin: 6px; padding: 10px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
      <div class="col-padding">
        <h6 style="font-weight: bold;color: white;">Matsapha</h6>
      </div>
    </div>
    <div class="col-sm-2 btn" style="width:110px; background-color:rgba(218, 207, 11, 0.85); margin: 6px; padding: 10px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
      <div class="col-padding">
        <h6 style="font-weight: bold;color: white;">Mbabane</h6>
      </div>
    </div>
    <div class="col-sm-2 btn" style="width:110px; background-color:rgba(218, 11, 131, 0.85); margin: 6px; padding: 10px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
      <div class="col-padding">
        <h6 style="font-weight: bold;color: white;">Mhlume</h6>
      </div>
    </div>
</td>
</tr>
</tbody>
</table>



</div>




<div style="width: 100%;">
<hr />
<div id="chart-container" >Houses Overview</div>
  
<hr />
</div>


 


</div> 
</div>









<script src="js/jquery-2.1.4.js"></script>
  <script src="js/fusioncharts.js"></script>
  <script src="js/fusioncharts.charts.js"></script>
  <script src="js/themes/fusioncharts.theme.zune.js"></script>
  
<script type="text/javascript">


var urls = 'chart_data.php';
var station = 'All Stations';
var counts = 'Number of Houses';
var caption = 'Houses by Station Overview';

$("sdv").click(function(){
var urls = 'sdv.php';
var station = 'Months';
var counts = 'Total Paid';
var caption = 'Payments per Month';
});

$(function() {
    $.ajax({

        url: urls,
        type: 'GET',
        success: function(data) {
            chartData = data;
            var chartProperties = {
                "caption": caption,
                "xAxisName": station,
                "yAxisName": counts,
                "rotatevalues": "1",
                "theme": "zune"
            };

            apiChart = new FusionCharts({
                type: 'column2d',
                renderAt: 'chart-container',
                width: '100%',
                height: '350',
                dataFormat: 'json',
                dataSource: {
                    "chart": chartProperties,
                    "data": chartData
                }
            });
            apiChart.render();
        }
    });
});
</script>

<?php
 
?>

 
</body>
</html>