<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php'; // Ensure the path to autoload.php is correct

// Check if total amount is set in session
if (!isset($_SESSION['total_price'])) {
    header("Location: /car_rental/modules/User/rent_vehicle.php");
    exit;
}

$totalAmount = $_SESSION['total_price'] * 100; // Convert to paise (required by Razorpay API)

// Razorpay API credentials (Test Mode)
$keyId = "rzp_test_dI5T9EvBngTJMx";  // Your Razorpay Key ID
$keySecret = "33c07bI6ekCZAASWRdk6IiLO";  // Your Razorpay Secret Key

try {
    $api = new Razorpay\Api\Api($keyId, $keySecret);

    // Create Razorpay order
    $orderData = [
        'receipt' => strval(rand(1000, 9999)), // Random receipt ID
        'amount' => $totalAmount, // Amount in paise
        'currency' => 'INR',
        'payment_capture' => 1
    ];

    $order = $api->order->create($orderData);
    $orderId = $order['id'];

    // Display Razorpay payment form with UPI option
    echo "
        <html>
            <head>
                <script src='https://checkout.razorpay.com/v1/checkout.js'></script>
                <style>
                    /* Add some basic styling */
                    body {
                        font-family: Arial, sans-serif;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                        margin: 0;
                        background-color: #f3f4f6;
                    }
                    .payment-container {
                        text-align: center;
                        background: #fff;
                        padding: 30px;
                        border-radius: 8px;
                        box-shadow: 0 0 20px rgba(0,0,0,0.1);
                    }
                    h2 {
                        color: #333;
                    }
                    .order-id {
                        font-weight: bold;
                        color: #555;
                    }
                    #payButton {
                        background-color: #F37254;
                        color: #fff;
                        border: none;
                        padding: 12px 24px;
                        border-radius: 4px;
                        cursor: pointer;
                        font-size: 16px;
                    }
                </style>
            </head>
            <body>
                <div class='payment-container'>
                    <h2>Complete Your Payment</h2>
                    <p>Your order ID is: <span class='order-id'>$orderId</span></p>
                    <button id='payButton'>Pay Now</button>
                </div>

                <script>
                    document.getElementById('payButton').onclick = function(e) {
                        var options = {
                            'key': '$keyId',
                            'amount': $totalAmount,
                            'currency': 'INR',
                            'order_id': '$orderId',
                            'name': 'Car Rental Payment',
                            'description': 'Payment for Vehicle Rental',
                            'handler': function(response) {
                                // Redirect to success page with payment ID
                                window.location.href = 'payment_success.php?payment_id=' + response.razorpay_payment_id;
                            },
                            'prefill': {
                                'name': 'John Doe',
                                'email': 'john.doe@example.com',
                                'contact': '9999999999'
                            },
                            'theme': {
                                'color': '#F37254'
                            },
                            'method': {
                                'upi': true, // Enable UPI
                                'card': true,
                                'netbanking': true,
                                'wallet': true
                            }
                        };

                        var rzp1 = new Razorpay(options);
                        rzp1.open();
                        e.preventDefault();
                    };
                </script>
            </body>
        </html>
    ";

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
