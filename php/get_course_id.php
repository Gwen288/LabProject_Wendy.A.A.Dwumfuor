<?php
header('Content-Type: application/json');
require 'connection.php';

$course_code = trim($_GET['course_code'] ?? '');
if (!$course_code) {
    echo json_encode(['status' => false, 'msg' => 'Course code required']);
    exit;
}

$stmt = $conn->prepare("SELECT course_id FROM courses WHERE course_code = ?");
$stmt->bind_param("s", $course_code);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(['status' => true, 'course_id' => $row['course_id']]);
} else {
    echo json_encode(['status' => false, 'msg' => 'Course not found']);
}

$stmt->close();
$conn->close();
