<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if ($data && isset($data['amount'], $data['phone'])) {
        $amount = $data['amount'];
        $phone = $data['phone'];
        $token = 'your_api_token';  
        $registration = 'your_registration_number';

        $url = 'https://api-sandbox.ctechpay.com/student/mobile/';

        $fields = [
            'token' => $token,
            'registration' => $registration,
            'amount' => $amount,
            'phone' => $phone,
            'airtel' => 1
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        
        $response = curl_exec($ch);

        if ($response === false) {
            echo json_encode(['message' => 'cURL Error: ' . curl_error($ch), 'error' => true]);
            curl_close($ch);
            exit();
        }

        curl_close($ch);

        $result = json_decode($response, true);

        if ($result && isset($result['status']['success']) && $result['status']['success']) {
            echo json_encode(['message' => 'Payment successful', 'transactionId' => $result['data']['transaction']['id']]);
        } else {
            echo json_encode(['message' => 'Payment failed', 'error' => $result['status']['message'] ?? 'Unknown error']);
        }
    } else {
        echo json_encode(['message' => 'Invalid data received', 'error' => true]);
    }
}
?>
