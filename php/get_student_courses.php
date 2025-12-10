<?php
header('Content-Type: application/json');
require 'connection.php';

// Get student_id from GET
$student_id = trim($_GET['student_id'] ?? '');
if (!$student_id) {
    echo json_encode(['status' => 'error', 'msg' => 'Student ID required']);
    exit;
}

// Fetch only pending or enrolled courses
$stmt = $conn->prepare("
    SELECT c.course_id, c.course_code, c.course_name, cs.status, cs.role
    FROM course_student_list cs
    JOIN courses c ON cs.course_id = c.course_id
    WHERE cs.student_id = ? AND cs.status IN ('pending', 'enrolled')
    ORDER BY c.course_code
");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

echo json_encode(['status' => 'success', 'courses' => $courses]);

$stmt->close();
$conn->close();
?>
