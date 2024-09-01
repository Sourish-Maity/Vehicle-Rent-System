<?php
include('session_customer.php');
if (!isset($_SESSION['login_customer'])) {
    session_destroy();
    header("location: customerlogin.php");
}

$car_id = $_GET['car_id'];
$rent_start_date = $_GET['rent_start_date'];
$rent_end_date = $_GET['rent_end_date'];
$type = $_GET['type'];
$charge_type = $_GET['charge_type'];
$driver_id = $_GET['driver_id'];
$fare = $_GET['fare'];

// Retrieve car, driver, and client details from the database
$sql = "SELECT * FROM cars WHERE car_id = '$car_id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$car_name = $row["car_name"];
$car_nameplate = $row["car_nameplate"];

// Fetch driver details
$sql2 = "SELECT * FROM driver WHERE driver_id = '$driver_id'";
$result2 = $conn->query($sql2);
$driver_row = $result2->fetch_assoc();

$driver_name = $driver_row["driver_name"];
$driver_gender = $driver_row["driver_gender"];
$driver_phone = $driver_row["driver_phone"];

// Fetch client details if needed
// ...

?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="shortcut icon" type="image/png" href="assets/img/P.png.png">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/w3css/w3.css">
</head>
<body>
    <nav class="navbar navbar-custom navbar-fixed-top" role="navigation" style="color: black">
        <!-- Navigation content -->
    </nav>

    <div class="container">
        <div class="jumbotron">
            <h1 class="text-center" style="color: green;"><span class="glyphicon glyphicon-ok-circle"></span> Booking Confirmed.</h1>
        </div>
        <h2 class="text-center"> Thank you for using Car Rental System! We wish you have a safe ride. </h2>
        <h3 class="text-center"><strong>Your Order Number:</strong> <span style="color: blue;">#12345</span></h3>
        <div class="container">
            <h5 class="text-center">Please read the following information about your order.</h5>
            <div class="box">
                <div class="col-md-10" style="float: none; margin: 0 auto; text-align: center;">
                    <h3 style="color: orange;">Your booking has been received and placed into our order processing system.</h3>
                    <h4><strong>Vehicle Name:</strong> <?php echo $car_name; ?></h4>
                    <h4><strong>Vehicle Number:</strong> <?php echo $car_nameplate; ?></h4>
                    <h4><strong>Fare:</strong> Rs. <?php echo $fare; ?>/<?php echo ($charge_type == "days" ? "day" : "km"); ?></h4>
                    <h4><strong>Booking Date:</strong> <?php echo date("Y-m-d"); ?></h4>
                    <h4><strong>Start Date:</strong> <?php echo $rent_start_date; ?></h4>
                    <h4><strong>Return Date:</strong> <?php echo $rent_end_date; ?></h4>
                    <h4><strong>Driver Name:</strong> <?php echo $driver_name; ?></h4>
                    <h4><strong>Driver Gender:</strong> <?php echo $driver_gender; ?></h4>
                    <h4><strong>Driver Contact:</strong> <?php echo $driver_phone; ?></h4>
                </div>
            </div>
            <div class="col-md-12" style="float: none; margin: 0 auto; text-align: center;">
                <h6>Warning! <strong>Do not reload this page</strong> or the above display will be lost. If you want a hardcopy of this page, please print it now.</h6>
            </div>
        </div>
    </div>
</body>
</html>
