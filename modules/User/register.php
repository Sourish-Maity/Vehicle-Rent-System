<?php
include '../../config/config.php';
echo '<link rel="stylesheet" href="register.css">';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $account_type = $_POST['account_type']; // 'user' for customer, 'provider' for company

    // Insert into Account table
    $accountQuery = "INSERT INTO Account (account_email, account_password, account_type) VALUES ('$email', '$password', '$account_type')";
    $db->query($accountQuery); // Execute the query directly

    if ($account_type === 'user') {
        // Register as a Customer
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $age = $_POST['age'];
        $contact_number = $_POST['contact_number'];
        $license_number = $_POST['license_number'];
        
        // Insert into Customer table
        $customerQuery = "INSERT INTO Customer (customer_id, first_name, last_name, age, account_email, contact_number, license_number)
                          VALUES (UUID(), '$first_name', '$last_name', '$age', '$email', '$contact_number', '$license_number')";
        $db->query($customerQuery); // Execute the query directly
        
        echo "Customer registration successful.";

    } elseif ($account_type === 'provider') {
        // Register as a Provider (Company)
        $provider_name = $_POST['provider_name'];
        $provider_location = $_POST['provider_location'];
        $provider_contact = $_POST['provider_contact'];

        // Insert into Provider table
        $providerQuery = "INSERT INTO Provider (provider_id, provider_name, account_email, provider_location, provider_contact)
                          VALUES (UUID(), '$provider_name', '$email', '$provider_location', '$provider_contact')";
        $db->query($providerQuery); // Execute the query directly
        
        echo "Provider registration successful.";
    }
}
?>

<form method="POST" class="registration-form">
    <h2>Register</h2>
    <label for="account_type">Register as:</label>
    <select name="account_type" id="account_type" required onchange="toggleFields()">
        <option value="user">Customer</option>
        <option value="provider">Company</option>
    </select>

    <input type="email" name="email" required placeholder="Email">
    <input type="password" name="password" required placeholder="Password">
    
    <!-- Customer fields, displayed by default -->
    <div id="customer_fields" class="additional-fields customer">
        <h3>Customer Details</h3>
        <input type="text" name="first_name" placeholder="First Name">
        <input type="text" name="last_name" placeholder="Last Name">
        <input type="number" name="age" placeholder="Age">
        <input type="text" name="contact_number" placeholder="Contact Number">
        <input type="text" name="license_number" placeholder="License Number">
    </div>

    <!-- Provider fields, hidden by default -->
    <div id="provider_fields" class="additional-fields provider" style="display: none;">
        <h3>Company Details</h3>
        <input type="text" name="provider_name" placeholder="Company Name">
        <input type="text" name="provider_location" placeholder="Company Location">
        <input type="text" name="provider_contact" placeholder="Company Contact Number">
    </div>

    <button type="submit" class="submit-button">Register</button>
</form>

<script>
function toggleFields() {
    var accountType = document.getElementById("account_type").value;
    document.getElementById("customer_fields").style.display = accountType === 'user' ? 'block' : 'none';
    document.getElementById("provider_fields").style.display = accountType === 'provider' ? 'block' : 'none';
}
</script>
