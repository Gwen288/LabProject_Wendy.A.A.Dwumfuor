<?php
header('Content-Type: application/json');
require 'connection.php';

// Fetch all pending join requests
$sql = "
    SELECT cs.course_id, cs.student_id, cs.role, cs.request_reason, cs.request_date,
           c.course_name
    FROM course_student_list cs
    JOIN courses c ON cs.course_id = c.course_id
    WHERE cs.status = 'pending'
    ORDER BY cs.request_date ASC
";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode(['status' => 'error', 'msg' => $conn->error]);
    $conn->close();
    exit;
}

$requests = [];

while ($row = $result->fetch_assoc()) {
    // For now, we only have student_id instead of student_name
    $row['student_name'] = 'Student #' . $row['student_id']; // simple placeholder
    $requests[] = $row;
}

echo json_encode(['status' => 'success', 'requests' => $requests]);

$conn->close();
?>
