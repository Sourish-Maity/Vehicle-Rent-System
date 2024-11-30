<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../../config/config.php';
session_start();

// Check if availability data is set
if (!isset($_SESSION['available_vehicles']) || !isset($_SESSION['reservation_details'])) {
    header("Location: /car_rental/modules/User/rent_vehicle.php");
    exit;
}

// Get available vehicles and reservation details from the session
$available_vehicles = $_SESSION['available_vehicles'];
$reservation_details = $_SESSION['reservation_details'];

// Calculate duration in hours from session data
$start_time = new DateTime($reservation_details['start_time']);
$end_time = new DateTime($reservation_details['end_time']);
$duration = $end_time->diff($start_time);
$duration_hours = $duration->h + ($duration->days * 24);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_vehicle = $_POST['selected_vehicle'];
    $customer_id = $_SESSION['customer_id']; // Retrieve customer ID from session
    $destination = $_SESSION['reservation_details']['destination']; // Retrieve destination from session

    // Find the selected vehicle's price and calculate total price
    foreach ($available_vehicles as $vehicle) {
        if ($vehicle['vehicle_id'] == $selected_vehicle) {
            $vehicle_price = $vehicle['vehicle_price'];
            $rental_price = $duration_hours * 50;  // Example rental price calculation
            $total_price = $vehicle_price + $rental_price;
            break;
        }
    }

    // Insert reservation with total price
    $query = "INSERT INTO Reservation (vehicle_id, customer_id, start_time, end_time, total_price, destination) 
              VALUES (:vehicle_id, :customer_id, :start_time, :end_time, :total_price, :destination)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':vehicle_id', $selected_vehicle);
    $stmt->bindParam(':customer_id', $customer_id);
    $stmt->bindParam(':start_time', $reservation_details['start_time']);
    $stmt->bindParam(':end_time', $reservation_details['end_time']);
    $stmt->bindParam(':total_price', $total_price);
    $stmt->bindParam(':destination', $destination);
    $stmt->execute();

    // Get the auto-incremented reservation_id
    $reservation_id = $db->lastInsertId();

    // Update the 'is_rented' status in the Vehicle table for the reserved vehicle
    $updateQuery = "UPDATE Vehicle SET is_rented = 1 WHERE vehicle_id = :vehicle_id";
    $updateStmt = $db->prepare($updateQuery);
    $updateStmt->bindParam(':vehicle_id', $selected_vehicle);
    $updateStmt->execute();

    // Store reservation ID and selected vehicle in session for later use
    $_SESSION['reservation_id'] = $reservation_id;
    $_SESSION['selected_vehicle'] = $selected_vehicle;
    $_SESSION['total_price'] = $total_price;

    // Redirect to the payment page
    header("Location: /car_rental/modules/Payment/payment.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Vehicle</title>
    <style>
        /* General reset and base styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            text-align: center;
            padding: 20px;
        }

        h2 {
            font-size: 32px;
            color: #333;
            margin-bottom: 30px;
        }

        .vehicle-selection-form {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .vehicle-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 12px;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            overflow: hidden;
            text-align: left;
        }

        .vehicle-card:hover {
            transform: translateY(-5px);
        }

        .vehicle-card input[type="radio"] {
            appearance: none;
            width: 24px;
            height: 24px;
            border: 2px solid #4CAF50;
            border-radius: 50%;
            display: inline-block;
            margin-right: 10px;
            vertical-align: middle;
            cursor: pointer;
        }

        .vehicle-card input[type="radio"]:checked {
            background-color: #4CAF50;
            border: 2px solid #4CAF50;
        }

        .vehicle-card label {
            padding: 30px;
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 15px;
            cursor: pointer;
        }

        .vehicle-info {
            flex-grow: 1;
        }

        .vehicle-info h3 {
            font-size: 24px;
            color: #444;
            margin-bottom: 10px;
        }

        .vehicle-info p {
            font-size: 16px;
            color: #666;
        }

        .price-breakdown p {
            font-size: 16px;
            color: #444;
        }

        .price-breakdown strong {
            font-weight: bold;
            color: #111;
        }

        .reserve-button {
            background-color: #4CAF50;
            color: white;
            font-size: 18px;
            padding: 12px;
            width: 100%;
            border: none;
            cursor: pointer;
            border-radius: 0 0 12px 12px;
            transition: background-color 0.3s ease;
        }

        .reserve-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Select a Vehicle</h2>
        <form method="POST" class="vehicle-selection-form">
            <div class="vehicle-list">
                <?php foreach ($available_vehicles as $vehicle): ?>
                    <div class="vehicle-card">
                        <input type="radio" name="selected_vehicle" value="<?php echo htmlspecialchars($vehicle['vehicle_id']); ?>" id="vehicle_<?php echo htmlspecialchars($vehicle['vehicle_id']); ?>" required>
                        <label for="vehicle_<?php echo htmlspecialchars($vehicle['vehicle_id']); ?>">
                            <div class="vehicle-info">
                                <h3>Vehicle ID: <?php echo htmlspecialchars($vehicle['vehicle_id']); ?></h3>
                                <p>Type: <?php echo htmlspecialchars($vehicle['vehicle_type']); ?></p>
                                <p>Mileage: <?php echo htmlspecialchars($vehicle['mileage']); ?> km/l</p>
                            </div>
                            <div class="price-breakdown">
                                <p>Vehicle Price: ₹<?php echo number_format($vehicle['vehicle_price'], 2); ?></p>
                                <p>Rental Price (for <?php echo $duration_hours; ?> hours): ₹<?php echo number_format($duration_hours * 50, 2); ?></p>
                                <p><strong>Total: ₹<?php echo number_format($vehicle['vehicle_price'] + ($duration_hours * 50), 2); ?></strong></p>
                            </div>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="submit" class="reserve-button">Reserve Now</button>
        </form>
    </div>
</body>
</html>
