<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

$file = 'firebase_data.json';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (file_exists($file)) {
        echo file_get_contents($file);
    } else {
        echo '{}';
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if ($input) {
        $currentData = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
        
        // Simple deep merge or path update logic could go here
        // For this mock, we'll assume the client sends the full path update or we merge at root
        // But to keep it simple for the mock-firebase.js, let's just merge at the top level keys provided
        
        foreach ($input as $key => $value) {
            // If value is an array and exists, merge? No, Firebase set/update usually replaces at path.
            // Let's implement a simple path-based update if the key contains '/'
            // But our mock-firebase.js currently sends the whole object for a path?
            // Let's look at how we'll implement mock-firebase.js first.
            // For now, let's assume the client sends a patch object to merge.
            
            // Actually, let's make it robust:
            // The mock-firebase.js will send { "path/to/key": value }
            // We need to expand that into the array.
            
            $keys = explode('/', $key);
            $temp = &$currentData;
            foreach ($keys as $k) {
                if (!isset($temp[$k]) || !is_array($temp[$k])) {
                    $temp[$k] = [];
                }
                $temp = &$temp[$k];
            }
            $temp = $value; // Assign the value at the leaf
        }
        
        file_put_contents($file, json_encode($currentData, JSON_PRETTY_PRINT));
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
    }
}
?>
