<?php
include('session_customer.php');
if (!isset($_SESSION['login_customer'])) {
    session_destroy();
    header("location: customerlogin.php");
}

// Retrieve form data
$car_id = $_POST['hidden_carid'];
$rent_start_date = $_POST['rent_start_date'];
$rent_end_date = $_POST['rent_end_date'];
$type = $_POST['type'];
$charge_type = $_POST['charge_type'];
$driver_id = $_POST['driver_id'];
$fare = $_POST['fare'];

// Dummy payment processing logic
// Replace with actual payment integration (e.g., PayPal, Stripe)
$payment_success = true; // Simulate successful payment

if ($payment_success) {
    // Insert into database, or any other post-payment logic here
    // Redirect to confirmation page
    header("Location: bookingconfirm.php?car_id=$car_id&rent_start_date=$rent_start_date&rent_end_date=$rent_end_date&type=$type&charge_type=$charge_type&driver_id=$driver_id&fare=$fare");
    exit();
} else {
    // Handle payment failure
    echo "Payment failed. Please try again.";
}
?>

