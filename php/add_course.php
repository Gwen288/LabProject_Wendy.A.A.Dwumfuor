<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'connection.php';

// Read POST body (JSON)
$json = file_get_contents("php://input");
$data = json_decode($json, true);

if ($data === null) {
    echo json_encode([
        'status' => 'error',
        'msg' => 'JSON decode failed',
        'raw' => $json
    ]);
    exit;
}

// Extract fields
$courseCode  = trim($data['course_code'] ?? '');
$courseName  = trim($data['course_name'] ?? '');
$Des         = trim($data['description'] ?? '');
$Hours = (float) trim($data['credit_hours'] ?? 0);
$facultyId   = trim($data['faculty_id'] ?? '');

if (!$courseCode || !$courseName || !$Hours || !$facultyId) {
    echo json_encode([
        'status' => 'error',
        'msg' => 'Missing required data',
        'received' => $data
    ]);
    exit;
}

// Prepare SQL
$stmt = $conn->prepare("INSERT INTO courses (course_code, course_name, `description`, credit_hours, faculty_id)
                        VALUES (?, ?, ?, ?, ?)");

if (!$stmt) {
    echo json_encode([
        'status' => 'error',
        'msg' => 'Prepare failed',
        'mysql' => $conn->error
    ]);
    exit;
}

// Bind parameters
$stmt->bind_param("sssdi", $courseCode, $courseName, $Des, $Hours, $facultyId);

// Execute
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
