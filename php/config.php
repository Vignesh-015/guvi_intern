<?php
// Load Composer's autoloader
require __DIR__ . '/../vendor/autoload.php';

// Set header to return JSON
header('Content-Type: application/json');

// --- MySQL Connection (using MySQLi) ---
$mysql_host = 'localhost';
$mysql_user = 'root';
$mysql_pass = ''; // Enter your password if you have one
$mysql_db = 'internship_db';

$mysqli = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_db);

if ($mysqli->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'MySQL Connection Error: ' . $mysqli->connect_error]);
    exit();
}

// --- Redis Connection (using Predis) ---
try {
    $redis = new Predis\Client([
        'scheme' => 'tcp',
        'host'   => '127.0.0.1',
        'port'   => 6379,
    ]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Redis Connection Error: ' . $e->getMessage()]);
    exit();
}

// --- MongoDB Connection ---
try {
    $mongo = new MongoDB\Client("mongodb://127.0.0.1:27017");
    $mongo_db = $mongo->internship_db; // Database name
    $mongo_collection = $mongo_db->user_profiles; // Collection name
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'MongoDB Connection Error: ' . $e->getMessage()]);
    exit();
}
?>