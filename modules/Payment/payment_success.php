<?php
session_start();
include '../../config/config.php';

// Check if reservation ID and total price are set in session
if (isset($_GET['payment_id']) && isset($_SESSION['reservation_id'])) {
    $reservationId = $_SESSION['reservation_id'];
    $totalAmount = $_SESSION['total_price']; // Total amount from session
    $paymentDate = date("Y-m-d H:i:s"); // Current date and time

    // Insert into Payment table with payment date
    $query = "INSERT INTO Payment (reservation_id, amount, payment_date) VALUES (:reservation_id, :amount, :payment_date)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':reservation_id', $reservationId);
    $stmt->bindParam(':amount', $totalAmount);
    $stmt->bindParam(':payment_date', $paymentDate);
    $stmt->execute();

    // Get the auto-generated transaction ID
    $transactionId = $db->lastInsertId();

    // Display success message with styling
    echo "
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                background-color: #f4f4f9;
            }
            .success-container {
                background-color: #fff;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                text-align: center;
                max-width: 400px;
                width: 100%;
            }
            .success-container h2 {
                color: #28a745;
                margin-bottom: 20px;
            }
            .success-container p {
                color: #333;
                font-size: 16px;
                margin: 10px 0;
            }
            .success-container a {
                display: inline-block;
                margin-top: 20px;
                padding: 10px 20px;
                color: #fff;
                background-color: #007bff;
                border-radius: 4px;
                text-decoration: none;
                transition: background-color 0.3s;
            }
            .success-container a:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class='success-container'>
            <h2>Payment Successful!</h2>
            <p>Transaction ID: " . htmlspecialchars($transactionId) . "</p>
            <p>Amount: $" . htmlspecialchars($totalAmount) . "</p>
            <p>Payment Date: " . htmlspecialchars($paymentDate) . "</p>
            <a href='/car_rental/modules/User/rent_vehicle.php'>Go to Dashboard</a>
        </div>
    </body>
    </html>
    ";
} else {
    echo "<h2>Payment Failed or Missing Information</h2>";
}
?>
