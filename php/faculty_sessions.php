<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'connection.php';
session_start();

// Make sure user is faculty
$facultyId = $_SESSION['user_id'] ?? 0;
if (!$facultyId) {
    echo json_encode(['status' => 'error', 'msg' => 'Faculty not logged in']);
    exit;
}

try {
    // Fetch sessions for this faculty with course info
    $sql = "
        SELECT s.session_id, s.topic, s.location, s.start_time, s.end_time, s.session_date,
               c.course_name, c.course_code
        FROM sessions s
        JOIN courses c ON s.course_id = c.course_id
        WHERE c.faculty_id = ?
        ORDER BY s.session_date DESC
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception($conn->error);

    $stmt->bind_param("i", $facultyId);
    $stmt->execute();
    $result = $stmt->get_result();

    $sessions = [];
    while ($row = $result->fetch_assoc()) {
        $sessions[] = $row;
    }

    echo json_encode(['status' => 'success', 'sessions' => $sessions]);

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'msg' => $e->getMessage()]);
}
?>
