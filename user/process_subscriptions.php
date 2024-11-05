<?php
session_start();
include('../includes/dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and fetch the form data
    $userId = $_SESSION['user_id'];
    $planId = intval($_POST['plan_id']);
    $paymentMethodId = intval($_POST['payment_method_id']);

    // Fetch the plan details from the database
    $planQuery = "SELECT plan_name, price FROM subscription_plan WHERE plan_id = ?";
    $stmtPlan = $con->prepare($planQuery);
    $stmtPlan->bind_param('i', $planId);
    $stmtPlan->execute();
    $stmtPlan->store_result();

    if ($stmtPlan->num_rows > 0) {
        $stmtPlan->bind_result($planName, $price);
        $stmtPlan->fetch();

        // Calculate start and end dates for the subscription
        $startDate = date('Y-m-d H:i:s');
        $duration = ($planName === 'weekly') ? '+7 days' : (($planName === 'monthly') ? '+30 days' : (($planName === 'annually') ? '+365 days' : ''));
        $endDate = date('Y-m-d H:i:s', strtotime($startDate . $duration));

        // Insert the subscription details into the database
        $insertQuery = "INSERT INTO subscriptions (user_id, plan_id, start_date, end_date, payment_status, plan_name, price) VALUES (?, ?, ?, ?, 'pending', ?, ?)";
        $stmtInsert = $con->prepare($insertQuery);
        $stmtInsert->bind_param('iisssd', $userId, $planId, $startDate, $endDate, $planName, $price);

        if ($stmtInsert->execute()) {
            $_SESSION['success_message'] = "Subscription successfully created!";
        } else {
            $_SESSION['error_message'] = "Error creating subscription. Please try again.";
        }

        $stmtInsert->close();
    } else {
        $_SESSION['error_message'] = "Invalid subscription plan. Please try again.";
    }

    $stmtPlan->close();
} else {
    $_SESSION['error_message'] = "Invalid request method.";
}

$con->close();
header('Location: subscriptions.php');
exit();
