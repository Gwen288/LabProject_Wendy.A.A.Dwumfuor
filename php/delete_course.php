<?php
header('Content-Type: application/json');
require 'connection.php';

$data = json_decode(file_get_contents("php://input"), true);
$course_code = trim($data['course_code'] ?? '');

if (!$course_code) {
    echo json_encode([
        'status' => 'error',
        'msg' => 'Missing course_code'
    ]);
    exit;
}

// Prepare SQL delete
$stmt = $conn->prepare("DELETE FROM courses WHERE course_code = ?");
if (!$stmt) {
    echo json_encode([
        'status' => 'error',
        'msg' => 'Prepare failed',
        'mysql' => $conn->error
    ]);
    exit;
}

// Bind parameters and execute
$stmt->bind_param("s", $course_code);

if (!$stmt->execute()) {
    echo json_encode([
        'status' => 'error',
        'msg' => 'Execute failed',
        'mysql' => $stmt->error
    ]);
    exit;
}

// Success
echo json_encode(['status' => 'success']);

$stmt->close();
$conn->close();
?>
