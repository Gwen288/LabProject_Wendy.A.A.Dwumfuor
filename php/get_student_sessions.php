<?php
header('Content-Type: application/json');
require 'connection.php';
session_start();

$studentId = $_SESSION['user_id'] ?? 0;
if (!$studentId) {
    echo json_encode(['status' => 'error', 'msg' => 'Student not logged in']);
    exit;
}

try {
    $sql = "
        SELECT s.session_id, s.topic, s.location, s.start_time, s.end_time, s.session_date,
               c.course_code, c.course_name
        FROM course_student_list cs
        JOIN sessions s ON cs.course_id = s.course_id
        JOIN courses c ON s.course_id = c.course_id
        WHERE cs.student_id = ? AND cs.status = 'enrolled'
        ORDER BY s.session_date ASC, s.start_time ASC
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception($conn->error);

    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();

    $sessions = [];
    while ($row = $result->fetch_assoc()) {
        $sessions[] = $row;
    }

    echo json_encode(['status'=>'success','sessions'=>$sessions]);

    $stmt->close();
    $conn->close();

} catch(Exception $e) {
    echo json_encode(['status'=>'error','msg'=>$e->getMessage()]);
}
?>
