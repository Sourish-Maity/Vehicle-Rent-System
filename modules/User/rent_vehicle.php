<?php
include '../../config/config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['account_email'])) {
    header("Location: login.php");
    exit;
}

$message = ""; // To store availability message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicle_type = $_POST['vehicle_type'];
    $destination = $_POST['destination'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Query to find available vehicles of the specified type and time
    // Query to find available vehicles of the specified type and time
    $query = "SELECT vehicle_id, vehicle_type, mileage, vehicle_price FROM Vehicle WHERE vehicle_type = :vehicle_type AND is_rented = 0";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':vehicle_type', $vehicle_type);
    $stmt->execute();
    $available_vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if any vehicles are available
    if (!empty($available_vehicles)) {
        $_SESSION['available_vehicles'] = $available_vehicles;
        $_SESSION['reservation_details'] = [
            'vehicle_type' => $vehicle_type,
            'destination' => $destination,
            'start_time' => $start_time,
            'end_time' => $end_time
        ];

        header("Location: /car_rental/modules/Allocation/allocate_vehicle.php");
        exit;
    } else {
        $message = "No vehicles of the selected type are currently available.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FleetFly - Premium Car Rental Services</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('image4.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -1;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 50px;
            background: rgba(0, 0, 0, 0.5);
            width: 100%;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #00FF84;
        }

        .nav-links a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-size: 16px;
        }

        .search-container input {
            padding: 10px;
            background-color: #222;
            border: none;
            color: white;
            border-radius: 5px 0 0 5px;
            width: 200px;
            font-size: 14px;
        }

        .search-container button {
            background: #333;
            border: none;
            padding: 10px 14px;
            cursor: pointer;
            color: white;
            border-radius: 0 5px 5px 0;
            font-size: 14px;
        }

        .account-btn {
            background: none;
            border: none;
            font-size: 18px;
            color: white;
            cursor: pointer;
            margin-left: 20px;
        }

        .auth-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            background: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 10px;
            max-width: 650px;
            width: 90%;
            margin: auto;
            margin-top: 80px;
            color: white;
        }

        .auth-section h2 {
            font-size: 28px;
            margin-bottom: 15px;
        }

        .availability-form {
            width: 100%;
        }

        .availability-form label {
            display: block;
            text-align: left;
            font-size: 16px;
            margin-bottom: 5px;
            color: #ddd;
        }

        .availability-form input,
        .availability-form select,
        .availability-form button {
            width: 100%;
            padding: 15px;
            margin-bottom: 15px;
            font-size: 16px;
            border: 1px solid #444;
            border-radius: 5px;
            background: #222;
            color: white;
        }

        /* Button styles */
        .availability-form button {
            background-color: #00FF84;
            color: #222;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .availability-form button:hover {
            background-color: #00cc6a;
        }

        /* Custom calendar icon color for datetime-local inputs */
        .availability-form input[type="datetime-local"]::-webkit-calendar-picker-indicator {
            filter: invert(1); /* Makes the calendar icon white */
        }

        .footer {
            text-align: center;
            padding: 15px;
            background: #000;
            color: #ccc;
            font-size: 14px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .footer a {
            color: #00FF84;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="background"></div>

    <header class="header">
        <div class="logo">RoadWave.</div>
        <nav class="nav-links">
            <a href="#">Home</a>
            <a href="/car_rental/modules/User/my_booking.php">My Booking</a>
            <a href="#">Collections</a>
            <a href="#">Contacts</a>
        </nav>
    </header>

    <main class="auth-section">
        <h2>Check Vehicle Availability</h2>
        <form method="POST" class="availability-form">
            <label for="current_location">Current Location:</label>
            <input type="text" name="current_location" placeholder="Enter current location" required>

            <label for="destination">Destination:</label>
            <input type="text" name="destination" placeholder="Enter destination" required>

            <label for="vehicle_type">Vehicle Type:</label>
            <select name="vehicle_type" required>
                <option value="car">Car</option>
                <option value="bike">Bike</option>
                <option value="scooter">Scooter</option>
            </select>

            <label for="start_time">Start Time:</label>
            <input type="datetime-local" name="start_time" required>

            <label for="end_time">End Time:</label>
            <input type="datetime-local" name="end_time" required>

            <button type="submit" class="submit-button">Search Vehicles</button>
        </form>
    </main>

    <footer class="footer">
        <p>Connect with us on <a href="https://linkedin.com" target="_blank">LinkedIn</a></p>
    </footer>
</body>
</html>
