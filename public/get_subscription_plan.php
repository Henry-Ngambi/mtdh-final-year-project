<?php
session_start();
include('../includes/dbconnection.php');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$selectedPlanId = isset($_POST['plan_id']) ? intval($_POST['plan_id']) : null;
if ($selectedPlanId) {
    $selectedPlan = $con->query("SELECT * FROM subscription_plan WHERE plan_id = $selectedPlanId")->fetch_assoc();
    if ($selectedPlan) {
        echo json_encode([
            'success' => true,
            'plan' => [
                'plan_id' => $selectedPlan['plan_id'],
                'plan_name' => ucfirst($selectedPlan['plan_name']),
                'price' => number_format($selectedPlan['price'], 2)
            ]
        ]);
    } else {
        echo json_encode(['error' => 'Plan not found']);
    }
} else {
    echo json_encode(['error' => 'Invalid plan ID']);
}
?>
