<?php
session_start();
header('Content-Type: application/json');
require 'connection.php';

// Get JSON data from request
$data = json_decode(file_get_contents("php://input"), true);

$courseId = $data['course_id'] ?? null;
$role = trim($data['role'] ?? 'auditor');
$reason = trim($data['request_reason'] ?? '');
$status = 'pending';
$requestDate = date('Y-m-d');

// Use student_id from session if not provided
$studentId = $data['student_id'] ?? ($_SESSION['user_id'] ?? null);

if (!$courseId || !$studentId) {
    echo json_encode(['status' => 'error', 'msg' => 'Missing data: courseId or studentId']);
    exit;
}

// Check if student already requested or enrolled
$check = $conn->prepare("SELECT * FROM course_student_list WHERE course_id=? AND student_id=?");
$check->bind_param("ii", $courseId, $studentId);
$check->execute();
$res = $check->get_result();
if ($res->num_rows > 0) {
    echo json_encode(['status' => 'error', 'msg' => 'You have already requested or enrolled in this course']);
    exit;
}

// Prepare insert statement
$stmt = $conn->prepare("INSERT INTO course_student_list 
    (course_id, student_id, `status`, `role`, request_reason, request_date)
    VALUES (?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode(['status' => 'error', 'msg' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("iissss", $courseId, $studentId, $status, $role, $reason, $requestDate);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'course' => [
            'course_id' => $courseId,
            'student_id' => $studentId,
            'status' => $status,
            'role' => $role,
            'request_reason' => $reason,
            'request_date' => $requestDate
        ]
    ]);
} else {
    echo json_encode(['status' => 'error', 'msg' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
