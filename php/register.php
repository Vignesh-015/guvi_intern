<?php
require 'config.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'Email and password are required.']);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
    exit();
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Use a prepared statement to prevent SQL injection
$stmt = $mysqli->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
$stmt->bind_param("ss", $email, $hashed_password);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Registration successful! Redirecting to login...']);
} else {
    // Check for duplicate email error
    if ($mysqli->errno === 1062) {
        echo json_encode(['status' => 'error', 'message' => 'This email is already registered.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Registration failed. Please try again.']);
    }
}

$stmt->close();
$mysqli->close();
?>