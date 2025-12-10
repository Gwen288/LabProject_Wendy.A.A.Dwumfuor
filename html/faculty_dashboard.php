<?php 
require "../php/auth_check.php";

checkAccess('faculty');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>

<!-- ===================== NAVIGATION BAR ====================== -->
<nav class="navbar">
    <div class="nav-container">
        <ul>
            <li><a href="#Course">Course Management</a></li>
            <li><a href="#Session">Sessions Overview</a></li>
            <li><a href="#JoinRequests">Join Requests</a></li>
            <li><a href="#Attendance">Attendance</a></li>
            <li><a href="#Reports">Reports</a></li>
        </ul>

        <button id="logoutButton" class="logout-btn">Logout</button>
    </div>
</nav>

<h2 class="welcome">Welcome Back, <?= $_SESSION['username'] ?> </h2>

<!-- ========================= COURSE MANAGEMENT ============================== -->
<section id="Course" class="section">
    <div class="section-header">
        <h3>Course Overview</h3>
        <button class="primary-btn" id="openCourseModal">+ Create Course</button>
    </div>

    <div class="table-container">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Course ID</th>
                    <th>Course Name</th>
                    <th>Description</th>
                    <th>Credit Hours</th>
                    <th>Faculty ID</th>
                    <th style="width: 120px;">Actions</th>
                </tr>
            </thead>

            <tbody id="courseTableBody">
                <!--dynamically loads courses from the database-->
            </tbody>
        </table>
    </div>
</section>

<!-- =========================== MODAL: CREATE COURSE ========================= -->
<div id="courseModal" class="modal">
    <div class="modal-content">
        <h3>Create New Course</h3>

        <label>Course ID</label>
        <input type="text" name="course_code" placeholder="e.g., CS103">

        <label>Course Name</label>
        <input type="text" name="course_name" placeholder="Course Title">

        <label>Description</label>
        <input type="text" name="description" placeholder="Course Description">

        <label>Credit Hours</label>
        <input type="number" name="credit_hours" placeholder="e.g., 3" step="0.5" min="0">

        <label>Faculty ID</label>
        <input type="text" name="faculty_id" placeholder="Faculty ID">

        <button type='button' class="primary-btn" id="saveCourseBtn">Save Course</button>
        <button class="close-modal">Cancel</button>
    </div>
</div>

<hr>

<!-- ========================= SESSION MANAGEMENT ============================== -->
<section id="Session" class="section">
    <div class="section-header">
        <h3>Session Management</h3>
        <button class="primary-btn" id="openSessionModal">+ Create Session</button>
    </div>

    <div class="table-container">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Topic</th>
                    <th>Location</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Date</th>
                    <th style="width: 160px;">Actions</th>
                </tr>
            </thead>

            <tbody id="sessionTableBody">
         
            </tbody>
        </table>
    </div>
</section>

<!-- =========================== MODAL: CREATE SESSION ========================= -->
<div id="sessionModal" class="modal">
    <div class="modal-content">
        <h3>Create New Session</h3>

        <label>Course</label>
        <input type="text" name="course_code" placeholder="Enter Course Code">

        <label>Topic</label>
        <input type="text" name="topic" placeholder="Topic">

        <label>Location</label>
        <input type="text" name="location" placeholder="Location">

        <label>Start Time</label>
        <input type="text" name="start_time" placeholder="Start Time">

        <label>End Time</label>
        <input type="text" name="end_time" placeholder="End Time">

        <label>Date</label>
        <input type="date" name="session_date" placeholder="Date">

        <button class="primary-btn" type='button' >Save Session</button>
        <button class="close-modal">Cancel</button>
    </div>
</div>

<hr>

<!-- ========================= JOIN REQUESTS ============================== -->
<section id="JoinRequests" class="section">
    <div class="section-header">
        <h3>Student Join Requests</h3>
    </div>

    <div class="table-container">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Course</th>
                    <th>Reason</th>
                    <th style="width: 150px;">Action</th>
                </tr>
            </thead>

            <tbody>

            </tbody>
        </table>
    </div>
</section>

<hr>

<!-- ========================= ATTENDANCE ============================== -->
<section id="Attendance" class="section">
    <h3>Attendance Overview</h3>

    <label for="attendanceSessionSelect">Select Session:</label>
    <select id="attendanceSessionSelect">
        <option value="">-- Select Session --</option>
    </select>

    <div class="table-container">
    <div id="attendanceTableContainer">
    <table class="styled-table" id="attendanceTable">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Status</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="4">Select a session to view students</td></tr>
        </tbody>
    </table>
</div>

<button class="primary-btn" id="saveAttendanceBtn">Save Attendance</button>

</section>

<hr>

<!-- ========================= REPORTS SECTION ============================== -->
<section id="Reports" class="section">
    <h3>Reports & Analytics</h3>

    <div class="report-grid">
        <div class="report-card">
            <h4>Total Students</h4>
            <p class="report-number">120</p>
        </div>

        <div class="report-card">
            <h4>Average Attendance</h4>
            <p class="report-number">88%</p>
        </div>

        <div class="report-card">
            <h4>Courses Offered</h4>
            <p class="report-number">6</p>
        </div>

        <div class="report-card">
            <h4>Sessions Held</h4>
            <p class="report-number">42</p>
        </div>
    </div>

    <button class="primary-btn download-btn">Download Full Report</button>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/logout.js"></script>
<script src="../js/course.js"></script>
<script src="../js/session.js"></script>
<script src="../js/faculty_join_requests.js"></script>
<script src="../js/attendance.js"></script>
</body>
</html>
