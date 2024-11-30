<?php
include '../../config/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch account details from the database
    $query = "SELECT * FROM Account WHERE account_email = '$email'";
    $result = $db->query($query); // Execute the query
    $account = $result->fetch(PDO::FETCH_ASSOC); // Fetch account data

    // Verify the password
    if ($account && password_verify($password, $account['account_password'])) {
        $_SESSION['account_email'] = $account['account_email'];
        $_SESSION['account_type'] = $account['account_type'];

        // Redirect based on account type
        if ($account['account_type'] === 'user') {
            // Fetch customer details
            $customerQuery = "SELECT * FROM Customer WHERE account_email = '$email'";
            $customerResult = $db->query($customerQuery); // Execute the query
            $customer = $customerResult->fetch(PDO::FETCH_ASSOC); // Fetch customer data

            $_SESSION['customer_id'] = $customer['customer_id']; // Store customer ID in session
            header("Location: /car_rental/modules/User/rent_vehicle.php");
            exit;
        } elseif ($account['account_type'] === 'provider') {
            // Fetch provider details
            $providerQuery = "SELECT * FROM Provider WHERE account_email = '$email'";
            $providerResult = $db->query($providerQuery); // Execute the query
            $provider = $providerResult->fetch(PDO::FETCH_ASSOC); // Fetch provider data

            $_SESSION['provider_id'] = $provider['provider_id']; // Store provider ID in session
            header("Location: /car_rental/modules/Admin/admin_dashboard.php"); // Updated path
            exit;
        }
    } else {
        echo "Invalid login credentials.";
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

        /* Background image setup with animations for transitioning images */
        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            animation: backgroundChange 8s infinite linear;
            z-index: -1;
        }

        @keyframes backgroundChange {
            0% { background-image: url('image1.jpg'); }
            33% { background-image: url('image2.jpg'); }
            66% { background-image: url('image3.jpg'); }
            100% { background-image: url('image4.jpg'); }
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

        .search-container {
            display: inline-flex;
            align-items: center;
            margin-left: 20px;
        }

        .search-container input {
            padding: 5px;
            background-color: #222;
            border: none;
            color: white;
            border-radius: 5px 0 0 5px;
        }

        .search-container button {
            background: #333;
            border: none;
            padding: 6px 10px;
            cursor: pointer;
            color: white;
            border-radius: 0 5px 5px 0;
        }

        .account-btn {
            background: none;
            border: none;
            font-size: 18px;
            color: white;
            cursor: pointer;
            margin-left: 20px;
        }

        /* Center auth-section */
        .auth-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    background: rgba(0, 0, 0, 0.7);
    padding: 20px;
    border-radius: 10px;
    max-width: 400px;
    width: 90%;
    margin: auto;
    position: relative;
    top: 50px;  /* Adjust this value to pull the box further down */
    transform: none;  /* Remove translateY to maintain the new position */
    color: white;
}


        .auth-section h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .auth-section p {
            font-size: 16px;
            margin-bottom: 20px;
            color: #ddd;
        }

        /* Modify input fields */
        .input-group {
            margin-bottom: 15px;
            width: 100%;
        }

        .input-group label {
            font-size: 16px;
            font-weight: bold;
            color: #fff;
            display: block;
            margin-bottom: 5px;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 2px solid #00FF84;
            border-radius: 5px;
            background-color: #333;
            color: white;
        }

        .input-group input:focus {
            outline: none;
            border-color: #00CC66; /* Different focus border color */
        }

        /* Modify button styles */
        .login-btn {
            padding: 12px 25px;
            font-size: 16px;
            color: white;
            background-color: #00FF84;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-weight: bold;
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
    <!-- Background Image Container -->
    <div class="background"></div>

    <header class="header">
        <div class="logo">RoadWave.</div>
        <nav class="nav-links">
            <a href="#">Home</a>
            <a href="#">Services</a>
            <a href="#">Collections</a>
            <a href="#">Contacts</a>
            <div class="search-container">
                <input type="text" placeholder="Search">
                <button>🔍</button>
            </div>
            <button class="account-btn">👤</button>
        </nav>
    </header>

    <main class="auth-section">
        <h2>Welcome to RoadWave</h2>
        <p>Please log in to continue</p>
        <form method="POST">
            <div class="input-group">
                <label for="email">Your Email Address</label> <!-- Changed label text -->
                <input type="email" id="email" name="email" required placeholder="Email">
            </div>
            <div class="input-group">
                <label for="password">Enter Your Password</label> <!-- Changed label text -->
                <input type="password" id="password" name="password" required placeholder="Password">
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>
        <div class="links">
            <p>Don't have an account? <a href="/register">Register here</a></p>
        </div>
    </main>

    <footer class="footer">
        <p>Connect with us on <a href="https://linkedin.com" target="_blank">LinkedIn</a></p>
    </footer>
</body>
</html>