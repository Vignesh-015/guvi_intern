<?php
require 'config.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'Email and password are required.']);
    exit();
}

// Use a prepared statement to fetch the user
$stmt = $mysqli->prepare("SELECT id, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    // Verify the password
    if (password_verify($password, $user['password'])) {
        // Generate a unique session token
        $token = bin2hex(random_bytes(32));
        $user_id = $user['id'];
        
        // Store the token in Redis with an expiration of 1 hour (3600 seconds)
        $redis->setex("session:$token", 3600, $user_id);
        
        echo json_encode([
            'status' => 'success', 
            'message' => 'Login successful!',
            'token' => $token
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email or password.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email or password.']);
}

$stmt->close();
$mysqli->close();
?>