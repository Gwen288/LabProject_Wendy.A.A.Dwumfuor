<?php
header('Content-Type: application/json');
require 'connection.php';
session_start();

// Make sure user is faculty
$facultyId = $_SESSION['user_id'] ?? 0;
if (!$facultyId) {
    echo json_encode(['status'=>'error','msg'=>'Faculty not logged in']);
    exit;
}

// Fetch sessions for this faculty
$sql = "
    SELECT s.session_id, s.topic, s.location, s.start_time, s.end_time, s.session_date,
           c.course_name, c.course_code
    FROM sessions s
    JOIN courses c ON s.course_id = c.course_id
    WHERE c.faculty_id = ?
    ORDER BY s.session_date DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $facultyId);
$stmt->execute();
$result = $stmt->get_result();

$sessions = [];
while($row = $result->fetch_assoc()) {
    $sessions[] = $row;
}

echo json_encode(['status'=>'success','sessions'=>$sessions]);

$stmt->close();
$conn->close();
?>
