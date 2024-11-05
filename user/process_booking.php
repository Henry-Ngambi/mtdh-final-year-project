<?php
session_start();
include('../includes/dbconnection.php');

if (!isset($_SESSION['user_id']) || !isset($_POST['submit_booking'])) {
    header('Location: ../public/login.php');
    exit();
}

// Fetch user inputs
$user_id = $_SESSION['user_id'];
$event_id = $_POST['event_id'];
$total_amount = $_POST['total_amount'];
$payment_method_id = $_POST['payment_method'];
$booking_date = date('Y-m-d');
$payment_details = "";

// Process payment details based on selected payment method
switch ($payment_method_id) {
    case '1': // Credit Card
        if (empty($_POST['card_number'])) {
            header('Location: events.php?error=MissingCardNumber');
            exit();
        }
        $card_number = htmlspecialchars($_POST['card_number']);
        $payment_details = "Card Number: " . $card_number;
        break;

    case '2': // PayPal
        if (empty($_POST['paypal_email'])) {
            header('Location: events.php?error=MissingPaypalEmail');
            exit();
        }
        $paypal_email = htmlspecialchars($_POST['paypal_email']);
        $payment_details = "PayPal Email: " . $paypal_email;
        break;

    case '3': // Airtel Money
        if (empty($_POST['airtel_phone'])) {
            header('Location: events.php?error=MissingAirtelPhone');
            exit();
        }
        $airtel_phone = htmlspecialchars($_POST['airtel_phone']);
        $payment_details = "Airtel Money Phone: " . $airtel_phone;
        break;

    default:
        header('Location: events.php?error=InvalidPaymentMethod');
        exit();
}

// Insert booking and payment information into the database
$stmt = $con->prepare("INSERT INTO bookings (user_id, event_id, booking_date, total_amount, payment_method_id, payment_details) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iisdis", $user_id, $event_id, $booking_date, $total_amount, $payment_method_id, $payment_details);

if ($stmt->execute()) {
    header('Location: events.php?success=BookingConfirmed');
} else {
    header('Location: events.php?error=BookingFailed');
}

$stmt->close();
$con->close();
?>
