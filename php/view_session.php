<?php
header('Content-Type: application/json');
require 'connection.php';

$session_id = trim($_GET['session_id'] ?? '');
if (!$session_id) {
    echo json_encode(['status' => false, 'msg' => 'Session ID required']);
    exit;
}

// Fetch session with course code
$stmt = $conn->prepare("
    SELECT s.session_id, c.course_code, s.topic, s.location, s.start_time, s.end_time, s.date
    FROM sessions s
    JOIN courses c ON s.course_id = c.course_id
    WHERE s.session_id = ?
");
$stmt->bind_param("i", $session_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        'status' => true,
        'data' => $row
    ]);
} else {
    echo json_encode(['status' => false, 'msg' => 'Session not found']);
}

$stmt->close();
$conn->close();
