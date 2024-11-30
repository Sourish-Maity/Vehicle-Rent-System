<?php
include '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicle_id = $_POST['vehicle_id'];
    $vehicle_type = $_POST['vehicle_type'];
    $wheels_count = $_POST['wheels_count'];
    $seat_count = $_POST['seat_count'];
    $fuel_type = $_POST['fuel_type'];
    $mileage = $_POST['mileage'];
    $vehicle_price = $_POST['vehicle_price']; // Added vehicle price
    $image = file_get_contents($_FILES['image']['tmp_name']);

    // Execute the query directly without prepared statements
    $query = "INSERT INTO Vehicle (vehicle_id, vehicle_type, wheels_count, seat_count, fuel_type, mileage, vehicle_price, image)
              VALUES ('$vehicle_id', '$vehicle_type', $wheels_count, $seat_count, '$fuel_type', '$mileage', '$vehicle_price', ?)";
    
    // Prepare for inserting the binary image data
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $image, PDO::PARAM_LOB);
    
    // Use the execute method to run the query
    if ($stmt->execute()) {
        echo "Vehicle added successfully.";
    } else {
        echo "Error adding vehicle.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Vehicle</title>
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            padding: 20px;
        }

        .form-container {
            background-color: #fff;
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #28a745; /* Green color for the heading */
        }

        .vehicle-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-size: 14px;
            margin-bottom: 5px;
            color: #333;
        }

        input, select {
            padding: 10px;
            border: 2px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            margin-bottom: 10px;
        }

        input[type="file"] {
            padding: 5px;
        }

        button.submit-button {
            padding: 10px;
            background-color: #28a745; /* Green button */
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button.submit-button:hover {
            background-color: #218838; /* Darker green when hovered */
        }

        input:focus, select:focus {
            border-color: #28a745;
            outline: none;
        }

        /* Style for input fields */
        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
        }

        button.submit-button:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add a New Vehicle</h2>
        <form method="POST" enctype="multipart/form-data" class="vehicle-form">
            <label for="vehicle_id">Vehicle ID:</label>
            <input type="text" name="vehicle_id" required>

            <label for="vehicle_type">Vehicle Type:</label>
            <select name="vehicle_type">
                <option value="car">Car</option>
                <option value="bike">Bike</option>
                <option value="scooter">Scooter</option>
            </select>

            <label for="wheels_count">Number of Wheels:</label>
            <input type="number" name="wheels_count" required>

            <label for="seat_count">Number of Seats:</label>
            <input type="number" name="seat_count" required>

            <label for="fuel_type">Fuel Type:</label>
            <select name="fuel_type">
                <option value="petrol">Petrol</option>
                <option value="diesel">Diesel</option>
            </select>

            <label for="mileage">Mileage:</label>
            <input type="text" name="mileage" required>

            <label for="vehicle_price">Vehicle Price:</label>
            <input type="text" name="vehicle_price" required>

            <label for="image">Upload Image:</label>
            <input type="file" name="image" required>

            <button type="submit" class="submit-button">Add Vehicle</button>
        </form>
    </div>
</body>
</html>