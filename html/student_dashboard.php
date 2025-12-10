<?php 
require "../php/auth_check.php";

checkAccess('student');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

<script>
    // Make PHP session user_id available to JS
    window.currentStudentId = <?= json_encode($_SESSION['user_id'] ?? null) ?>;
</script>

<!-- ===================== NAVIGATION BAR ====================== -->
<nav class="navbar">
    <div class="nav-container">
        <ul>
            <li><a href="#MyCourses">My Courses</a></li>
            <li><a href="#SessionSchedule">Session Schedule</a></li>
            <li><a href="#GradesReports">Grades / Reports</a></li>
        </ul>

        <button id="logoutButton" class="logout-btn">Logout</button>
    </div>
</nav>

<h2 class="welcome">Welcome Back, <?= $_SESSION['username'] ?> </h2>

<!-- ========================= MY COURSES ============================== -->
<section id="MyCourses" class="section">
    <div class="section-header">
        <h3>My Courses</h3>
        <button class="primary-btn" id="joinCourseBtn">+ Join Course</button>
    </div>

    <div class="table-container">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Course ID</th>
                    <th>Course Name</th>
                    <th>Instructor</th>
                    <th>Status</th>
                    <th style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody id="myCoursesTable">
                <!-- dynamically loaded student courses -->
                <tr>
                    <td>CS101</td>
                    <td>Introduction to Programming</td>
                    <td>Dr. Kofi Mensah</td>
                    <td>Enrolled</td>
                    <td>
                        <button class="view-btn">View</button>
                        <button class="edit-btn">Auditor</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<!-- ========================= MODAL: JOIN COURSE ====================== -->
<div id="joinCourseModal" class="modal">
    <div class="modal-content">
        <h3>Join Course</h3>

        <label for="course_code">Course Code</label>
        <input type="text" id="course_code" name="course_code" placeholder="Enter Course Code">

        <label for="join_type">Join As</label>
        <select id="join_type" name="join_type">
            <option value="auditor">Auditor</option>
            <option value="observer">Observer</option>
        </select>

        <label for="request_reason">Reason (optional)</label>
        <textarea id="request_reason" name="request_reason" placeholder="Why do you want to join this course?" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:8px; margin-bottom:14px; font-size:14px; resize:none; height:60px;"></textarea>

        <div style="display:flex; justify-content:flex-end; gap:10px;">
            <button class="primary-btn" type="button" id="saveJoinBtn">Request to Join</button>
            <button class="close-modal">Cancel</button>
        </div>
    </div>
</div>



<hr>

<!-- ========================= SESSION SCHEDULE ============================== -->
<section id="SessionSchedule" class="section">
    <div class="section-header">
        <h3>Session Schedule</h3>
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
            <tbody id="sessionScheduleTable">
                <!-- dynamically loaded sessions -->
                <tr>
                    <td>CS101</td>
                    <td>Variables & Loops</td>
                    <td>Room 201</td>
                    <td>2025-12-10</td>
                    <td>10:00 AM</td>
                    <td>12:00 PM</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<hr>

<!-- ========================= GRADES / REPORTS ============================== -->
<section id="GradesReports" class="section">
    <div class="section-header">
        <h3>Grades & Feedback</h3>
    </div>

    <div class="table-container">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Grade</th>
                    <th>Instructor Feedback</th>
                    <th>Faculty Intern Feedback</th>
                </tr>
            </thead>
            <tbody id="gradesReportsTable">
                <tr>
                    <td>CS101</td>
                    <td>A</td>
                    <td>Excellent understanding of core concepts</td>
                    <td>Participated actively in labs</td>
                </tr>
                <tr>
                    <td>CS102</td>
                    <td>B+</td>
                    <td>Good progress, needs to review recursion</td>
                    <td>Attended most sessions on time</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/logout.js"></script>
<script>
    // Open and close modals
    document.getElementById("joinCourseBtn").addEventListener("click", () => {
        document.getElementById("joinCourseModal").style.display = "block";
    });

    document.querySelectorAll(".close-modal").forEach(btn => {
        btn.addEventListener("click", () => {
            btn.closest(".modal").style.display = "none";
        });
    });

    document.getElementById("saveJoinBtn").addEventListener("click", () => {
        Swal.fire('Success!', 'Your join request has been submitted.', 'success');
        document.getElementById("joinCourseModal").style.display = "none";
    });
</script>
</body>
</html>
