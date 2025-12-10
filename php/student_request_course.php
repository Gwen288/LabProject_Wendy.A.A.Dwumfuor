<?php
session_start();
header('Content-Type: application/json');
require 'connection.php';

$data = json_decode(file_get_contents("php://input"), true);

$courseId = $data['course_id'] ?? null;
$role = $data['role'] ?? 'auditor';
$reason = $data['request_reason'] ?? '';
$status = 'pending';
$requestDate = date('Y-m-d');

// Get student ID directly from session
$studentId = $_SESSION['user_id'] ?? null;

if (!$courseId || !$studentId) {
    echo json_encode(['status'=>'error','msg'=>'Missing data']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO course_student_list 
    (course_id, student_id, `status`, `role`, request_reason, request_date)
    VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iissss", $courseId, $studentId, $status, $role, $reason, $requestDate);

if($stmt->execute()){
    echo json_encode(['status'=>'success']);
}else{
    echo json_encode(['status'=>'error','msg'=>$stmt->error]);
}

$stmt->close();
$conn->close();
?>
