<?php
// Load Composer's autoloader
require __DIR__ . '/../vendor/autoload.php';

// Set header to return JSON
header('Content-Type: application/json');

// --- MySQL Connection (using environment variables) ---
$mysql_host = getenv('MYSQL_HOST');
$mysql_user = getenv('MYSQL_USER');
$mysql_pass = getenv('MYSQL_PASSWORD');
$mysql_db = getenv('MYSQL_DATABASE');

$mysqli = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_db);

if ($mysqli->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'MySQL Connection Error: ' . $mysqli->connect_error]);
    exit();
}

// --- Redis Connection (using environment variable URL) ---
try {
    $redis = new Predis\Client(getenv('REDIS_URL'));
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Redis Connection Error: ' . $e->getMessage()]);
    exit();
}

// --- MongoDB Connection (using environment variable URL) ---
try {
    $mongo = new MongoDB\Client(getenv('MONGO_URL'));
    $mongo_db = $mongo->selectDatabase(getenv('MONGO_DATABASE_NAME'));
    $mongo_collection = $mongo_db->user_profiles;
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'MongoDB Connection Error: ' . $e->getMessage()]);
    exit();
}
?>