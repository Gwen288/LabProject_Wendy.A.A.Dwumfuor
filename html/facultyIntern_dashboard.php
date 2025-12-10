<?php 
require "../php/auth_check.php";

checkAccess('facultyIntern');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Intern Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

<!-- ===================== NAVIGATION BAR ====================== -->
<nav class="navbar">
    <div class="nav-container">
        <ul>
            <li><a href="#CourseList">Course List</a></li>
            <li><a href="#Sessions">Sessions</a></li>
            <li><a href="#Reports">Reports</a></li>
        </ul>
        <button id="logoutButton" class="logout-btn">Logout</button>
    </div>
</nav>

<h2 class="welcome">Welcome Back, Faculty Intern</h2>

<!-- ========================= COURSE LIST ============================== -->
<section id="CourseList" class="section">
    <div class="section-header">
        <h3>Course List</h3>
    </div>

    <div class="table-container">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Course ID</th>
                    <th>Course Name</th>
                    <th>Instructor</th>
                    <th>Auditors / Observers</th>
                    <th style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody id="courseListTable">
                <!-- Loaded dynamically from JS -->
            </tbody>
        </table>
    </div>
</section>

<hr>

<!-- ========================= SESSIONS ============================== -->
<section id="Sessions" class="section">
    <div class="section-header">
        <h3>Sessions</h3>
    </div>

    <div class="table-container">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Topic</th>
                    <th>Location</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                </tr>
            </thead>
            <tbody id="sessionsTable">
                <!-- Loaded dynamically from JS -->
            </tbody>
        </table>
    </div>
</section>

<hr>

<!-- ========================= REPORTS ============================== -->
<section id="Reports" class="section">
    <div class="section-header">
        <h3>Reports & Feedback</h3>
    </div>

    <div class="table-container">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Student</th>
                    <th>Feedback</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="reportsTable">
                <!-- Loaded dynamically from JS -->
            </tbody>
        </table>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/logout.js"></script>

<script>
    // ======== STATIC JSON DATA (local) ========
    const courseData = [
        {id: "CS101", name: "Intro to Programming", instructor: "Dr. Kofi Mensah", students: ["Auditor: Kofi", "Observer: Abena"]},
        {id: "CS102", name: "Data Structures", instructor: "Dr. Serwah Sarpong", students: ["Auditor: Dickson"]}
    ];

    const sessionData = [
        {course: "CS101", topic: "Variables & Loops", location: "Room 201", date: "2025-12-10", start: "10:00 AM", end: "12:00 PM"},
        {course: "CS102", topic: "Trees & Graphs", location: "Room 202", date: "2025-12-11", start: "2:00 PM", end: "4:00 PM"}
    ];

    const reportsData = [
        {course: "CS101", student: "Kofi", feedback: "Participates actively", status: "Reviewed"},
        {course: "CS102", student: "Dickson", feedback: "Needs improvement in recursion", status: "Pending"}
    ];

    // ======== LOAD TABLE DATA ========
    function loadTableData() {
        const courseTable = document.getElementById("courseListTable");
        courseTable.innerHTML = "";
        courseData.forEach(c => {
            courseTable.innerHTML += `<tr>
                <td>${c.id}</td>
                <td>${c.name}</td>
                <td>${c.instructor}</td>
                <td>${c.students.join(", ")}</td>
                <td>
                    <button class="view-btn">View Students</button>
                </td>
            </tr>`;
        });

        const sessionsTable = document.getElementById("sessionsTable");
        sessionsTable.innerHTML = "";
        sessionData.forEach(s => {
            sessionsTable.innerHTML += `<tr>
                <td>${s.course}</td>
                <td>${s.topic}</td>
                <td>${s.location}</td>
                <td>${s.date}</td>
                <td>${s.start}</td>
                <td>${s.end}</td>
            </tr>`;
        });

        const reportsTable = document.getElementById("reportsTable");
        reportsTable.innerHTML = "";
        reportsData.forEach(r => {
            reportsTable.innerHTML += `<tr>
                <td>${r.course}</td>
                <td>${r.student}</td>
                <td>${r.feedback}</td>
                <td>${r.status}</td>
            </tr>`;
        });
    }

    document.addEventListener("DOMContentLoaded", loadTableData);
</script>
</body>
</html>
