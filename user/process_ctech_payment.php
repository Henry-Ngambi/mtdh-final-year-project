<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('../includes/dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if ($data && isset($data['amount'], $data['phone'], $data['user_id'], $data['subscription_plan_id'])) {
        $amount = $data['amount'];
        $phone = $data['phone'];
        $userId = $data['user_id'];
        $subscriptionPlanId = $data['subscription_plan_id'];
        $paymentMethod = 'ctech_pay';
        $subscriptionStatus = 'active';
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime('+1 month'));

        // Mocking payment response for demonstration purposes
        $paymentResponse = [
            'status' => 'success',
            'message' => 'Payment successful',
        ];

        if ($paymentResponse['status'] === 'success') {
            $con->begin_transaction();

            try {
                // Insert into payments table
                $stmt1 = $con->prepare("INSERT INTO payments (user_id, amount, method, status, timestamp) VALUES (?, ?, ?, ?, NOW())");
                $stmt1->bind_param('idss', $userId, $amount, $paymentMethod, $subscriptionStatus);
                $stmt1->execute();

                // Insert into subscriptions table
                $stmt2 = $con->prepare("INSERT INTO subscriptions (user_id, subscription_plan_id, start_date, end_date, status) VALUES (?, ?, ?, ?, ?)");
                $stmt2->bind_param('iisss', $userId, $subscriptionPlanId, $startDate, $endDate, $subscriptionStatus);
                $stmt2->execute();

                // Update user subscription status
                $stmt3 = $con->prepare("UPDATE users SET is_subscribed = 1, subscription_plan_id = ? WHERE user_id = ?");
                $stmt3->bind_param('ii', $subscriptionPlanId, $userId);
                $stmt3->execute();

                $con->commit();
                echo json_encode(['message' => 'Subscription activated successfully']);
            } catch (Exception $e) {
                $con->rollback();
                echo json_encode(['message' => 'Transaction failed: ' . $e->getMessage(), 'error' => true]);
            }
        } else {
            echo json_encode(['message' => 'Payment failed', 'error' => $paymentResponse['message']]);
        }
    } else {
        echo json_encode(['message' => 'Invalid input data', 'error' => true]);
    }
} else {
    echo json_encode(['message' => 'Invalid request method', 'error' => true]);
}
?>
