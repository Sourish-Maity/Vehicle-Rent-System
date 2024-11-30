<?php
include '../../config/config.php';
require '../../vendor/autoload.php';

use Razorpay\Api\Api;

session_start();

// Razorpay API credentials
$keyId = 'YOUR_RAZORPAY_KEY_ID';
$keySecret = 'YOUR_RAZORPAY_SECRET';

// Initialize Razorpay API
$api = new Api($keyId, $keySecret);

// Verify payment signature
$success = false;
$error = "Payment failed";

if (!empty($_GET['payment_id']) && !empty($_GET['order_id']) && !empty($_GET['signature'])) {
    try {
        $attributes = [
            'razorpay_order_id' => $_GET['order_id'],
            'razorpay_payment_id' => $_GET['payment_id'],
            'razorpay_signature' => $_GET['signature']
        ];

        $api->utility->verifyPaymentSignature($attributes);
        $success = true;
    } catch (\Exception $e) {
        $error = 'Razorpay Error: ' . $e->getMessage();
    }
}

if ($success) {
    $reservation_id = $_SESSION['reservation_id'];
    $transaction_id = $_GET['payment_id'];
    $bank_name = 'Razorpay'; // Payment via Razorpay
    $payment_type = 'Initial';

    // Insert payment into the database
    $query = "INSERT INTO Payment (transaction_id, bank_name, payment_type, payment_date, reservation_id) 
              VALUES (:transaction_id, :bank_name, :payment_type, NOW(), :reservation_id)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':transaction_id', $transaction_id);
    $stmt->bindParam(':bank_name', $bank_name);
    $stmt->bindParam(':payment_type', $payment_type);
    $stmt->bindParam(':reservation_id', $reservation_id);
    $stmt->execute();

    // Clear sensitive session data and redirect to confirmation
    unset($_SESSION['reservation_id']);
    header("Location: confirmation.php");
    exit;
} else {
    echo "<p>$error</p>";
}
?>
