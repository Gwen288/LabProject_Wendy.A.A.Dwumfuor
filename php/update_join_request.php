<?php
header('Content-Type: application/json');
require 'connection.php';

// Read JSON POST body
$data = json_decode(file_get_contents("php://input"), true);

$student_id = $data['student_id'] ?? '';
$course_id = $data['course_id'] ?? '';
$status = $data['status'] ?? ''; // approved or rejected

if (!$student_id || !$course_id || !in_array($status, ['enrolled','rejected'])) {
    echo json_encode(['status' => 'error', 'msg' => 'Invalid data']);
    exit;
}

// Update status
$stmt = $conn->prepare("UPDATE course_student_list SET status = ? WHERE student_id = ? AND course_id = ?");
$stmt->bind_param("sii", $status, $student_id, $course_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'msg' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
