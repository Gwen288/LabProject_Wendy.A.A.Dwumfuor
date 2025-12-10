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

// Accept student_id from JS or session
$studentId = $data['student_id'] ?? ($_SESSION['user_id'] ?? null);

if (!$courseId || !$studentId) {
    echo json_encode(['status'=>'error','msg'=>'Missing data: courseId or studentId']);
    exit;
}

// Prepare insert statement
$stmt = $conn->prepare("INSERT INTO course_student_list 
    (course_id, student_id, `status`, `role`, request_reason, request_date)
    VALUES (?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode(['status'=>'error','msg'=>'Prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("iissss", $courseId, $studentId, $status, $role, $reason, $requestDate);

if($stmt->execute()){
    echo json_encode(['status'=>'success']);
} else {
    echo json_encode(['status'=>'error','msg'=>$stmt->error]);
}

$stmt->close();
$conn->close();
?>
