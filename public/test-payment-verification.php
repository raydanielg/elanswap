<?php
// Test script to verify payment using webhook

// Configuration
$webhookUrl = 'https://swap.elanbrands.net/payment/webhook';
$orderId = date('Y-m-d-H-i-s'); // Generate a unique order ID

// Test payment data
$paymentData = [
    'order_id' => $orderId,
    'status' => 'paid',
    'transid' => 'TXN' . time(),
    'reference' => 'REF' . time(),
    'amount' => '2500',
    'currency' => 'TZS',
    'timestamp' => date('Y-m-d H:i:s')
];

// Initialize cURL
$ch = curl_init($webhookUrl);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($paymentData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-ElanSwap-Webhook: test-verification'
]);

// Execute the request
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Check for errors
if (curl_errno($ch)) {
    echo 'cURL Error: ' . curl_error($ch);
} else {
    // Output the response
    echo "<h2>Payment Verification Test</h2>";
    echo "<p><strong>Status Code:</strong> {$httpCode}</p>";
    echo "<p><strong>Response:</strong></p>";
    echo "<pre>" . htmlspecialchars(print_r(json_decode($response, true), true)) . "</pre>";
    
    // Display the request data for reference
    echo "<p><strong>Sent Data:</strong></p>";
    echo "<pre>" . htmlspecialchars(print_r($paymentData, true)) . "</pre>";
    
    // Add a link to verify the payment
    $verifyUrl = "https://swap.elanbrands.net/payment/verify/{$orderId}";
    echo "<p><a href=\"$verifyUrl\" target=\"_blank\">Verify Payment Status</a></p>";
}

// Close cURL resource
curl_close($ch);
?>
