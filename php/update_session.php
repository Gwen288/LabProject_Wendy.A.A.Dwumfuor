<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'connection.php';

// Read POST JSON body
$json = file_get_contents("php://input");
$data = json_decode($json, true);

if ($data === null) {
    echo json_encode(['status' => 'error', 'msg' => 'JSON decode failed', 'raw' => $json]);
    exit;
}

// Extract fields
$sessionId = trim($data['session_id'] ?? '');
$courseId  = trim($data['course_id'] ?? '');
$topic     = trim($data['topic'] ?? '');
$location  = trim($data['location'] ?? '');
$startTime = trim($data['start_time'] ?? '');
$endTime   = trim($data['end_time'] ?? '');
$sessionDate = trim($data['date'] ?? '');

if (!$sessionId || !$courseId || !$topic || !$location || !$startTime || !$endTime || !$sessionDate) {
    echo json_encode(['status' => 'error', 'msg' => 'Missing required data', 'received' => $data]);
    exit;
}

// Prepare SQL
$stmt = $conn->prepare("UPDATE sessions 
                        SET course_id = ?, topic = ?, location = ?, start_time = ?, end_time = ?, date = ?
                        WHERE session_id = ?");

if (!$stmt) {
    echo json_encode(['status' => 'error', 'msg' => 'Prepare failed', 'mysql' => $conn->error]);
    exit;
}

// Bind parameters
$stmt->bind_param("ssssssi", $courseId, $topic, $location, $startTime, $endTime, $sessionDate, $sessionId);

// Execute
if (!$stmt->execute()) {
    echo json_encode(['status' => 'error', 'msg' => 'Execute failed', 'mysql' => $stmt->error]);
    exit;
}

// Success
echo json_encode(['status' => 'success']);

$stmt->close();
$conn->close();
?>
