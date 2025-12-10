// Make sure this runs after window.currentStudentId is set
document.addEventListener('DOMContentLoaded', () => {

    const myCoursesTable = document.getElementById('myCoursesTable');
    const joinCourseModal = document.getElementById('joinCourseModal');
    const joinCourseBtn = document.getElementById('joinCourseBtn');
    const saveJoinBtn = document.getElementById('saveJoinBtn');

    // Open / close modal
    joinCourseBtn.addEventListener('click', () => joinCourseModal.style.display = 'block');
    joinCourseModal.querySelectorAll('.close-modal').forEach(btn => {
        btn.addEventListener('click', () => joinCourseModal.style.display = 'none');
    });

    // Load courses
    async function loadStudentCourses() {
        try {
            const res = await fetch(`../php/get_student_courses.php?student_id=${window.currentStudentId}`);
            const data = await res.json();

            myCoursesTable.innerHTML = ''; // clear table

            if (data.status !== 'success') {
                console.error(data.msg);
                return;
            }

            data.courses.forEach(course => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${course.course_code}</td>
                    <td>${course.course_name}</td>
                    <td>${course.instructor_name}</td>
                    <td>${course.status.charAt(0).toUpperCase() + course.status.slice(1)}</td>
                    <td>
                        <button class="view-btn">View</button>
                        <button class="edit-btn">${course.role.charAt(0).toUpperCase() + course.role.slice(1)}</button>
                    </td>
                `;
                myCoursesTable.appendChild(tr);
            });

        } catch (err) {
            console.error(err);
        }
    }

    // Initial load
    loadStudentCourses();

    // Handle join request
    saveJoinBtn.addEventListener('click', async () => {
        const course_code = joinCourseModal.querySelector('input[name="course_code"]').value.trim();
        const role = joinCourseModal.querySelector('select[name="join_type"]').value;
        const reason = joinCourseModal.querySelector('textarea[name="request_reason"]').value.trim();

        if (!course_code || !role) {
            return Swal.fire('Error', 'Please fill in all required fields', 'error');
        }

        try {
            // Get course_id from course_code
            const courseRes = await fetch(`../php/get_course_id.php?course_code=${encodeURIComponent(course_code)}`);
            const courseData = await courseRes.json();

            if (!courseData.status || !courseData.course_id) {
                return Swal.fire('Error', 'Invalid course code', 'error');
            }

            const response = await fetch('../php/student_request_course.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    student_id: window.currentStudentId,
                    course_id: courseData.course_id,
                    role: role,
                    request_reason: reason
                })
            });

            const result = await response.json();

            if (result.status === 'success') {
                Swal.fire('Success!', 'Your join request has been submitted.', 'success');
                joinCourseModal.style.display = 'none';
                loadStudentCourses(); // reload table
            } else {
                Swal.fire('Error', result.msg || 'Failed to send request', 'error');
            }

        } catch (err) {
            console.error(err);
            Swal.fire('Error', 'Something went wrong', 'error');
        }
    });
});
