<?php
// Set the path to your JSON data file
$file = 'links.json';

// Only allow POST requests (required for sending data)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    exit('Method not allowed.');
}

// Ensure the content type is JSON
if (strpos($_SERVER['CONTENT_TYPE'], 'application/json') === false) {
    http_response_code(400); // Bad Request
    exit('Content-Type must be application/json');
}

// 1. Get the raw POST data (the new sections array from the browser)
$json_data = file_get_contents('php://input');

// 2. Decode the JSON data
$data = json_decode($json_data);

// 3. Validation and Safety Checks
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400); // Bad Request
    exit('Invalid JSON received.');
}

// Optionally, add more checks here (e.g., ensure it's an array)
if (!is_array($data)) {
    http_response_code(400);
    exit('Data must be a JSON array.');
}

// 4. Encode the data back to JSON with nice formatting (JSON_PRETTY_PRINT)
$new_json_content = json_encode($data, JSON_PRETTY_PRINT);

// 5. Write the data to the links.json file
if (file_put_contents($file, $new_json_content) !== false) {
    // Success response
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'message' => 'Links updated and saved to ' . $file]);
} else {
    // Failure response
    http_response_code(500); // Internal Server Error
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Failed to write to file. Check file permissions.']);
}
?>