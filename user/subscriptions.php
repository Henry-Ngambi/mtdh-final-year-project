<?php
session_start();
$title = "MTDH | Subscription Plans";
include 'header.php';
include 'sidebar.php';
include('../includes/dbconnection.php');

// Fetch subscription plans from the database
$subscriptionPlans = $con->query("SELECT * FROM subscription_plan");

// Check if there is a "plan_id" in the URL to open the modal after login
$selectedPlanId = isset($_GET['plan_id']) ? $_GET['plan_id'] : null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="style.css" rel="stylesheet" type="text/css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="container subscription-container">
    <h1>Choose Your Subscription Plan</h1>

    <!-- Display subscription plans -->
    <div class="subscription-cards">
        <?php while ($plan = $subscriptionPlans->fetch_assoc()) { ?>
            <div class="subscription-card">
                <h2><?php echo ucfirst($plan['plan_name']); ?></h2>
                <p><i><?php echo $plan['description']; ?></i></p>
                <p><b>Price:</b> <?php echo isset($plan['price']) ? number_format($plan['price'], 2) : 'N/A'; ?> per <?php echo ucfirst($plan['plan_name']); ?></p>
                <p><b>Duration:</b> 
                    <?php 
                        echo ($plan['plan_name'] == 'weekly') ? '7 days access' : (($plan['plan_name'] == 'monthly') ? '30 days access' : '365 days access');
                    ?>
                </p>
                <button class="btn btn-primary" onclick="handleSubscribe(<?php echo htmlspecialchars(json_encode($plan)); ?>)">Subscribe Now</button>
            </div>
        <?php } ?>
    </div>
</div>

<!-- Modal structure for completing subscription -->
<div class="modal" id="subscriptionModal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Complete Your Subscription</h2>
        <form id="payment-form">
            <input type="hidden" name="user_id" value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>">
            <input type="hidden" name="plan_id" id="plan_id" value="">

            <label>Plan Name:</label>
            <p id="plan_name"></p>

            <label>Price:</label>
            <p id="plan_price"></p>

            <!-- Payment Method Selection -->
            <label for="payment_method">Payment Method:</label>
            <select id="payment_method" name="payment_method" required onchange="togglePaymentFields()">
                <option value="">Select Payment Method</option>
                <option value="ctech_pay">Ctech Pay (Visa / Mastercard)</option>
                <option value="airtel_money">Airtel Money</option>
            </select>

            <div id="ctech_fields" style="display: none;">
                <label for="card_number">Card Number:</label>
                <input type="text" id="card_number" name="card_number" maxlength="16" required>
                <label for="card_expiry">Expiry Date (MM/YY):</label>
                <input type="text" id="card_expiry" name="card_expiry" placeholder="MM/YY" required>
                <label for="card_cvc">CVC:</label>
                <input type="text" id="card_cvc" name="card_cvc" maxlength="3" required>
            </div>

            <div id="airtel_fields" style="display: none;">
                <label for="airtel_number">Airtel Money Number:</label>
                <input type="text" id="airtel_number" name="airtel_number" placeholder="Your Airtel Money Number" required>
            </div>

            <button type="submit" id="submit-button" class="btn btn-primary">Confirm</button>
            <div id="payment-errors" role="alert"></div>
        </form>
    </div>
</div>

<script>
// Function to open modal and set plan details
function handleSubscribe(plan) {
    document.getElementById('plan_id').value = plan.plan_id; // Assuming 'plan_id' is in the plan array
    document.getElementById('plan_name').innerText = plan.plan_name;
    document.getElementById('plan_price').innerText = plan.price;
    document.getElementById('subscriptionModal').style.display = 'block';
}

// Close modal function
function closeModal() {
    document.getElementById('subscriptionModal').style.display = 'none';
}

// Validate card number (basic Luhn algorithm)
function isValidCardNumber(number) {
    let sum = 0;
    let shouldDouble = false;
    for (let i = number.length - 1; i >= 0; i--) {
        let digit = parseInt(number.charAt(i));
        if (shouldDouble) {
            digit *= 2;
            if (digit > 9) digit -= 9;
        }
        sum += digit;
        shouldDouble = !shouldDouble;
    }
    return sum % 10 === 0;
}

// Validate expiry date (MM/YY)
function isValidExpiryDate(expiry) {
    const [month, year] = expiry.split("/").map(Number);
    if (month < 1 || month > 12) return false;
    const currentYear = new Date().getFullYear() % 100; // Get last 2 digits of the year
    const currentMonth = new Date().getMonth() + 1;
    return year > currentYear || (year === currentYear && month >= currentMonth);
}

// Validate CVC (3 digits)
function isValidCVC(cvc) {
    return /^\d{3}$/.test(cvc);
}

// Validate Airtel Money number (Malawi Airtel number format as an example: 09XXXXXXXX)
function isValidAirtelNumber(number) {
    return /^09\d{8}$/.test(number);
}

// Show/hide fields based on payment method and validate fields
function togglePaymentFields() {
    const paymentMethod = document.getElementById('payment_method').value;
    document.getElementById('ctech_fields').style.display = paymentMethod === 'ctech_pay' ? 'block' : 'none';
    document.getElementById('airtel_fields').style.display = paymentMethod === 'airtel_money' ? 'block' : 'none';
    document.getElementById('card_number').disabled = paymentMethod !== 'ctech_pay';
    document.getElementById('card_expiry').disabled = paymentMethod !== 'ctech_pay';
    document.getElementById('card_cvc').disabled = paymentMethod !== 'ctech_pay';
    document.getElementById('airtel_number').disabled = paymentMethod !== 'airtel_money';
}

// Handle form submission with validation
document.getElementById('payment-form').addEventListener('submit', async (event) => {
    event.preventDefault();

    const paymentMethod = document.getElementById('payment_method').value;
    const errors = [];

    if (paymentMethod === 'ctech_pay') {
        const cardNumber = document.getElementById('card_number').value;
        const cardExpiry = document.getElementById('card_expiry').value;
        const cardCVC = document.getElementById('card_cvc').value;

        if (!isValidCardNumber(cardNumber)) errors.push("Invalid card number.");
        if (!isValidExpiryDate(cardExpiry)) errors.push("Invalid expiry date.");
        if (!isValidCVC(cardCVC)) errors.push("Invalid CVC.");
    } else if (paymentMethod === 'airtel_money') {
        const airtelNumber = document.getElementById('airtel_number').value;
        if (!isValidAirtelNumber(airtelNumber)) errors.push("Invalid Airtel Money number.");
    } else {
        errors.push("Please select a payment method.");
    }

    if (errors.length > 0) {
        document.getElementById('payment-errors').innerText = errors.join("\n");
        return;
    }

    // Prepare data for CURL call for card payments
    const planPrice = document.getElementById('plan_price').innerText;

    if (paymentMethod === 'ctech_pay') {
        const cardNumber = document.getElementById('card_number').value;
        const cardExpiry = document.getElementById('card_expiry').value;
        const cardCVC = document.getElementById('card_cvc').value;

        // CURL call to Ctech payment API using fetch
        try {
            const response = await fetch('process_ctech_payment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    user_id: document.querySelector('input[name="user_id"]').value,
                    plan_id: document.getElementById('plan_id').value,
                    plan_price: planPrice,
                    card_number: cardNumber,
                    card_expiry: cardExpiry,
                    card_cvc: cardCVC
                })
            });

            const result = await response.json();
            handlePaymentResponse(result);
        } catch (error) {
            console.error("Error during payment process:", error);
        }

    } else if (paymentMethod === 'airtel_money') {
        const airtelNumber = document.getElementById('airtel_number').value;

        // CURL call to Airtel payment API using fetch
        try {
            const response = await fetch('process_airtel_payment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    user_id: document.querySelector('input[name="user_id"]').value,
                    plan_id: document.getElementById('plan_id').value,
                    plan_price: planPrice,
                    airtel_number: airtelNumber
                })
            });

            const result = await response.json();
            handlePaymentResponse(result);
        } catch (error) {
            console.error("Error during payment process:", error);
        }
    }
});

// Handle payment response and close modal
function handlePaymentResponse(response) {
    if (response.success) {
        alert("Subscription successful!");
        closeModal();
        // Optionally, refresh the page or update the UI
        location.reload();
    } else {
        document.getElementById('payment-errors').innerText = response.message || "An error occurred. Please try again.";
    }
}

// Show modal if there is a selected plan
document.addEventListener('DOMContentLoaded', function () {
    <?php if ($selectedPlanId): ?>
        const planId = <?php echo json_encode($selectedPlanId); ?>;
        const planElement = document.querySelector(`.subscription-card button[onclick*='${planId}']`);
        if (planElement) planElement.click();
    <?php endif; ?>
});
</script>
</body>
</html>
