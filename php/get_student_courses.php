<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'connection.php';

$response = [];

try {
    // Get student_id from GET
    $student_id = trim($_GET['student_id'] ?? '');
    if (!$student_id) {
        throw new Exception('Student ID required');
    }

    // Prepare query
    $stmt = $conn->prepare("
        SELECT c.course_id, c.course_code, c.course_name, f.name AS instructor_name, cs.status, cs.role
        FROM course_student_list cs
        JOIN courses c ON cs.course_id = c.course_id
        JOIN faculty f ON c.faculty_id = f.faculty_id
        WHERE cs.student_id = ? AND cs.status IN ('pending', 'enrolled')
        ORDER BY c.course_code
    ");

    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $courses = [];
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }

    $response = [
        'status' => 'success',
        'courses' => $courses
    ];

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    $response = [
        'status' => 'error',
        'msg' => $e->getMessage()
    ];
}

echo json_encode($response);
