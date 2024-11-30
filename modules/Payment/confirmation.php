<?php
session_start();

// Check if transaction ID is set in session
if (!isset($_SESSION['transaction_id'])) {
    header("Location: /car_rental/modules/User/rent_vehicle.php");
    exit;
}

// Retrieve the transaction ID from the session
$transaction_id = $_SESSION['transaction_id'];

// Optionally, clear the session variable if you don't need it further
unset($_SESSION['transaction_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Confirmation</title>
    <link rel="stylesheet" href="/car_rental/modules/Payment/confirmation.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="confirmation-container">
        <h2>Payment Successful!</h2>
        <p>Your transaction ID is: <span class="transaction-id"><?php echo htmlspecialchars($transaction_id); ?></span></p>
        <p>Thank you for your payment.</p>
    </div>

    <!-- Include Confetti Script -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.min.js"></script>

    <!-- Confetti Animation Script -->
    <script>
        // Function to launch confetti on page load
        function launchConfetti() {
            const duration = 5000; // Duration of confetti effect in milliseconds
            const end = Date.now() + duration;

            (function frame() {
                confetti({
                    particleCount: 5,
                    angle: 60,
                    spread: 55,
                    origin: { x: 0 }
                });
                confetti({
                    particleCount: 5,
                    angle: 120,
                    spread: 55,
                    origin: { x: 1 }
                });

                if (Date.now() < end) {
                    requestAnimationFrame(frame);
                }
            }());
        }

        // Launch confetti when the page loads
        window.onload = launchConfetti;
    </script>
</body>
</html>
