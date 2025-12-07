<?php
header('Content-Type: application/json');
require 'connection.php';

// Fetch all pending join requests
$sql = "
    SELECT cs.course_id, cs.student_id, cs.role, cs.request_reason, cs.request_date,
           s.name AS student_name, c.course_name
    FROM course_student_list cs
    JOIN students s ON cs.student_id = s.student_id
    JOIN courses c ON cs.course_id = c.course_id
    WHERE cs.status = 'pending'
    ORDER BY cs.request_date ASC
";

$result = $conn->query($sql);
if (!$result) {
    echo json_encode(['status' => 'error', 'msg' => $conn->error]);
    exit;
}

$requests = [];
while ($row = $result->fetch_assoc()) {
    $requests[] = $row;
}

echo json_encode(['status' => 'success', 'requests' => $requests]);
$conn->close();
?>
