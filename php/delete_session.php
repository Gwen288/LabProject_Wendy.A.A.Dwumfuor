<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'connection.php';

// Read POST JSON body
$json = file_get_contents("php://input");
$data = json_decode($json, true);

$sessionId = trim($data['session_id'] ?? '');

if (!$sessionId) {
    echo json_encode(['status' => 'error', 'msg' => 'Missing session_id']);
    exit;
}

$stmt = $conn->prepare("DELETE FROM sessions WHERE session_id = ?");
if (!$stmt) {
    echo json_encode(['status' => 'error', 'msg' => 'Prepare failed', 'mysql' => $conn->error]);
    exit;
}

$stmt->bind_param("i", $sessionId);

if (!$stmt->execute()) {
    echo json_encode(['status' => 'error', 'msg' => 'Execute failed', 'mysql' => $stmt->error]);
    exit;
}

echo json_encode(['status' => 'success']);
$stmt->close();
$conn->close();
?>
