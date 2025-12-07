<?php
header('Content-Type: application/json');
require 'connection.php';

$result = $conn->query("SELECT * FROM courses ORDER BY course_code");

$courses = [];
while($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

echo json_encode($courses);

$conn->close();
?>
