<?php
header('Content-Type: application/json');
require 'connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($data === null) {
    echo json_encode([
        'status' => 'error',
        'msg' => 'JSON decode failed',
        'raw' => file_get_contents("php://input")
    ]);
    exit;
}

// Extract fields
$original = trim($data['original_course_code'] ?? '');
$courseCode = trim($data['course_code'] ?? '');
$courseName = trim($data['course_name'] ?? '');
$description = trim($data['description'] ?? '');
$credit_hours = trim($data['credit_hours'] ?? '');
$faculty_id = trim($data['faculty_id'] ?? '');

if (!$original || !$courseCode || !$courseName || !$credit_hours || !$faculty_id) {
    echo json_encode([
        'status' => 'error',
        'msg' => 'Missing required data',
        'received' => $data
    ]);
    exit;
}

// Prepare SQL update
$stmt = $conn->prepare("UPDATE courses 
                        SET course_code = ?, course_name = ?, description = ?, credit_hours = ?, faculty_id = ?
                        WHERE course_code = ?");

if (!$stmt) {
    echo json_encode([
        'status' => 'error',
        'msg' => 'Prepare failed',
        'mysql' => $conn->error
    ]);
    exit;
}

// Bind parameters and execute
$stmt->bind_param("sssiss", $courseCode, $courseName, $description, $credit_hours, $faculty_id, $original);

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
