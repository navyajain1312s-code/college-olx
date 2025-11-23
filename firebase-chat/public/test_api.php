<?php
$url = 'http://localhost:8000/api.php';
$data = [
    'chats/seller-6/messages/msg_test_1' => [
        'text' => 'Manual Test Message',
        'role' => 'client',
        'buyerName' => 'Manual Tester',
        'ts' => time() * 1000
    ]
];

$options = [
    'http' => [
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data)
    ]
];
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

echo "Response: " . $result . "\n";
?>
