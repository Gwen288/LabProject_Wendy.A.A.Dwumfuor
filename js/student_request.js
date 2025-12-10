// ----------------- ELEMENTS -----------------
const joinCourseModal = document.getElementById('joinCourseModal');
const joinCourseBtn = document.getElementById('joinCourseBtn');
const saveJoinBtn = document.getElementById('saveJoinBtn');
const myCoursesTable = document.getElementById('myCoursesTable');

// ----------------- OPEN & CLOSE MODAL -----------------
joinCourseBtn.addEventListener('click', () => joinCourseModal.style.display = 'block');
document.querySelectorAll('.close-modal').forEach(btn => {
    btn.addEventListener('click', () => joinCourseModal.style.display = 'none');
});

// ----------------- SAVE JOIN REQUEST -----------------
saveJoinBtn.addEventListener('click', async () => {
    const course_code = joinCourseModal.querySelector('input[name="course_code"]').value.trim();
    const role = joinCourseModal.querySelector('select[name="join_type"]').value;
    const reason = joinCourseModal.querySelector('textarea[name="request_reason"]').value.trim();

    if (!course_code || !role) {
        return Swal.fire({ title: 'Error', text: 'Please fill in all fields', icon: 'error' });
    }

    try {
        // Get course_id from course_code
        const courseRes = await fetch(`../php/get_course_id.php?course_code=${encodeURIComponent(course_code)}`);
        const courseData = await courseRes.json();

        if (courseData.status !== 'success' || !courseData.course_id) {
            return Swal.fire({ title: 'Error', text: 'Invalid course code', icon: 'error' });
        }

        const course_id = courseData.course_id;

        // Ensure student ID exists
        const student_id = window.currentStudentId;
        if (!student_id) {
            return Swal.fire({ title: 'Error', text: 'Student session missing', icon: 'error' });
        }

        // Send join request to backend
        const response = await fetch('../php/student_request_course.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ course_id, role, request_reason: reason, student_id })
        });

        const result = await response.json();

        if (result.status === 'success') {
            Swal.fire({ title: 'Request Sent!', icon: 'success', timer: 1500, showConfirmButton: false });
            joinCourseModal.style.display = 'none';
            await loadStudentCourses(); // refresh table after request
        } else {
            Swal.fire({ title: 'Error', text: result.msg || 'Failed to send request', icon: 'error' });
        }

    } catch (err) {
        console.error(err);
        Swal.fire({ title: 'Error', text: 'Something went wrong', icon: 'error' });
    }
});

// ----------------- LOAD STUDENT COURSES -----------------
async function loadStudentCourses() {
    try {
        const response = await fetch(`../php/get_student_courses.php?student_id=${window.currentStudentId}`);
        const data = await response.json();

        myCoursesTable.innerHTML = '';

        if (data.status !== 'success') {
            myCoursesTable.innerHTML = `<tr><td colspan="5">Error loading courses: ${data.msg || 'Unknown error'}</td></tr>`;
            return;
        }

        if (data.courses.length === 0) {
            myCoursesTable.innerHTML = `<tr><td colspan="5">No courses found.</td></tr>`;
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

        attachCourseRowEvents();

    } catch (err) {
        console.error(err);
        myCoursesTable.innerHTML = `<tr><td colspan="5">Failed to load courses.</td></tr>`;
    }
}

// ----------------- ATTACH BUTTON EVENTS -----------------
function attachCourseRowEvents() {
    document.querySelectorAll('#myCoursesTable .view-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            const tr = e.target.closest('tr');
            Swal.fire('Course Info', `Viewing course: ${tr.children[1].textContent}`, 'info');
        });
    });

    document.querySelectorAll('#myCoursesTable .edit-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            const tr = e.target.closest('tr');
            Swal.fire('Role Info', `Your role: ${tr.children[4].querySelector('.edit-btn').textContent}`, 'info');
        });
    });
}

// ----------------- INITIAL LOAD -----------------
loadStudentCourses();
