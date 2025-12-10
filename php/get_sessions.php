<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'connection.php';

try {
    // Fetch all sessions with their course code
    $sql = "SELECT s.session_id, c.course_code, s.topic, s.location, s.start_time, s.end_time, s.date
            FROM sessions s
            JOIN courses c ON s.course_id = c.course_id
            ORDER BY s.date DESC";
    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception($conn->error);
    }

    $sessions = [];
    while ($row = $result->fetch_assoc()) {
        $sessions[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'sessions' => $sessions
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'msg' => $e->getMessage()
    ]);
}

$conn->close();
?>
