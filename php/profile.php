<?php
require 'config.php';

// --- Authentication Step ---
$token = $_POST['token'] ?? '';
if (empty($token)) {
    echo json_encode(['status' => 'error', 'message' => 'Authentication failed: No token provided.']);
    exit();
}

// Check Redis for the token
$user_id = $redis->get("session:$token");

if (!$user_id) {
    echo json_encode(['status' => 'error', 'message' => 'Authentication failed: Invalid or expired session.']);
    exit();
}

// --- Action Handling (Get or Update) ---
$action = $_POST['action'] ?? '';

if ($action === 'get') {
    // Fetch profile from MongoDB
    $profile = $mongo_collection->findOne(['user_id' => (int)$user_id]);
    
    // Prepare data to send back, providing empty strings if not set
    $data = [
        'age' => $profile['age'] ?? '',
        'dob' => $profile['dob'] ?? '',
        'contact' => $profile['contact'] ?? '',
    ];

    echo json_encode(['status' => 'success', 'data' => $data]);

} elseif ($action === 'update') {
    // Update profile in MongoDB
    $age = $_POST['age'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $contact = $_POST['contact'] ?? '';

    // Data to be updated or inserted
    $updateData = [
        '$set' => [
            'user_id' => (int)$user_id,
            'age' => $age,
            'dob' => $dob,
            'contact' => $contact
        ]
    ];

    // Use 'upsert' option: update if exists, insert if not
    $options = ['upsert' => true];
    
    $result = $mongo_collection->updateOne(
        ['user_id' => (int)$user_id],
        $updateData,
        $options
    );

    if ($result->getModifiedCount() > 0 || $result->getUpsertedCount() > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update profile or no changes were made.']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid action specified.']);
}
?>