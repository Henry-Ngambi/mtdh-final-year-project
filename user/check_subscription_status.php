<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['trans_id'])) {
    $transactionId = $_GET['trans_id'];
    $token = 'your_api_token';

    $url = 'https://api-sandbox.ctechpay.com/student/mobile/status/?trans_id=' . urlencode($transactionId);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token));
    
    $response = curl_exec($ch);

    if ($response === false) {
        echo json_encode(['message' => 'cURL Error: ' . curl_error($ch), 'error' => true]);
        curl_close($ch);
        exit();
    }

    curl_close($ch);
    $apiResponse = json_decode($response, true);

    if (isset($apiResponse['transaction_status'])) {
        $transactionStatus = $apiResponse['transaction_status'];
        
        if ($transactionStatus === 'TS') {
            echo json_encode(['status' => 'completed', 'message' => 'Transaction successfully completed']);
        } elseif ($transactionStatus === 'TIP') {
            echo json_encode(['status' => 'in_progress', 'message' => 'Transaction in progress']);
        } else {
            echo json_encode(['status' => 'failed', 'message' => 'Transaction failed']);
        }
    } else {
        echo json_encode(['message' => 'Invalid response from payment gateway', 'error' => true]);
    }
}
?>
