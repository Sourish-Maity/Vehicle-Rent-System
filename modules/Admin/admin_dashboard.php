<?php
include '../../config/config.php';

// Initialize $vehicles as an empty array
$vehicles = [];

try {
    // Fetch all vehicles
    $query = "SELECT * FROM Vehicle";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        h1, h2 {
            color: #333;
        }
        .add-vehicle-link {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 15px;
            background-color: #28a745;
            color: #fff;
            border-radius: 5px;
            transition: background-color 0.3s;
            text-decoration: none;
            font-weight: bold;
        }
        .add-vehicle-link:hover {
            background-color: #218838;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background-color: #fff;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: #fff;
        }
        td {
            color: #333;
        }
        .status-rented {
            color: red;
            font-weight: bold;
        }
        .status-available {
            color: green;
            font-weight: bold;
        }
        .action-link {
            color: #dc3545;
            font-weight: bold;
            text-decoration: none;
        }
        .action-link:hover {
            color: #c82333;
        }
    </style>
</head>
<body>

<h1>Admin Dashboard</h1>
<a href="add_vehicle.php" class="add-vehicle-link">Add New Vehicle</a>

<h2>Vehicle List</h2>
<?php if (!empty($vehicles)) { ?>
    <table>
        <tr>
            <th>Vehicle ID</th>
            <th>Type</th>
            <th>Seats</th>
            <th>Fuel Type</th>
            <th>Mileage</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php foreach ($vehicles as $vehicle) { ?>
        <tr>
            <td><?= htmlspecialchars($vehicle['vehicle_id']) ?></td>
            <td><?= htmlspecialchars($vehicle['vehicle_type']) ?></td>
            <td><?= htmlspecialchars($vehicle['seat_count']) ?></td>
            <td><?= htmlspecialchars($vehicle['fuel_type']) ?></td>
            <td><?= htmlspecialchars($vehicle['mileage']) ?></td>
            <td class="<?= $vehicle['is_rented'] ? 'status-rented' : 'status-available' ?>">
                <?= ($vehicle['is_rented'] ?? 0) ? 'Rented' : 'Available' ?>
            </td>
            <td>
                <?php if ($vehicle['is_rented']): ?>
                    <a href="return_vehicle.php?vehicle_id=<?= htmlspecialchars($vehicle['vehicle_id']) ?>" class="action-link">Return Vehicle</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php } ?>
    </table>
<?php } else { ?>
    <p>No vehicles available.</p>
<?php } ?>

</body>
</html>
