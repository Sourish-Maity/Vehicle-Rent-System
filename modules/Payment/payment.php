<?php
include '../../config/config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['account_email'])) {
    header("Location: login.php");
    exit;
}

// Check if reservation ID is set
if (!isset($_SESSION['reservation_id'])) {
    header("Location: /car_rental/modules/User/rent_vehicle.php");
    exit;
}

// Get reservation ID
$reservation_id = $_SESSION['reservation_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment</title>
    <link rel="stylesheet" href="/car_rental/modules/Payment/payment.css">
</head>
<body>
    <div class="payment-container">
        <h2>Payment for Reservation</h2>
        <p>Your reservation ID is: <span class="reservation-id"><?php echo htmlspecialchars($reservation_id); ?></span></p>
        <form method="POST" action="/car_rental/modules/Payment/payscript.php" class="payment-form"> <!-- Check this action path -->
            <label for="bank_name">Bank Name:</label>
            <input type="text" name="bank_name" id="bank_name" required placeholder="Enter your bank name">
            <button type="submit" class="submit-button">Confirm Payment</button>
        </form>
    </div>
</body>
</html>
