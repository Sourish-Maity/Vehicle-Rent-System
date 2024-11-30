<?php
include '../../config/config.php';
session_start();

// Ensure customer is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: /car_rental/modules/User/login.php");
    exit;
}

$customer_id = $_SESSION['customer_id'];

// Fetch reservations for the customer
$query = "SELECT Reservation.reservation_id, Reservation.total_price, Reservation.destination, Reservation.start_time, Reservation.end_time, Vehicle.vehicle_type 
          FROM Reservation 
          INNER JOIN Vehicle ON Reservation.vehicle_id = Vehicle.vehicle_id 
          WHERE Reservation.customer_id = :customer_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':customer_id', $customer_id);
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        .booking-card {
            background: #fff;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
        }
        .booking-card h3 {
            margin-bottom: 10px;
            color: #444;
        }
        .booking-card p {
            margin-bottom: 5px;
            color: #666;
        }
        .total-price {
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>My Bookings</h2>
        <?php if (count($reservations) > 0): ?>
            <?php foreach ($reservations as $reservation): ?>
                <div class="booking-card">
                    <h3>Booking ID: <?php echo htmlspecialchars($reservation['reservation_id']); ?></h3>
                    <p>Vehicle Type: <?php echo htmlspecialchars($reservation['vehicle_type']); ?></p>
                    <p>Start Time: <?php echo htmlspecialchars($reservation['start_time']); ?></p>
                    <p>End Time: <?php echo htmlspecialchars($reservation['end_time']); ?></p>
                    <p>Destination: <?php echo htmlspecialchars($reservation['destination']); ?></p>
                    <p class="total-price">Total Price: ₹<?php echo number_format($reservation['total_price'], 2); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No bookings found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
