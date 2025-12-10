// OPEN & CLOSE MODAL 
const joinCourseModal = document.getElementById('joinCourseModal');
const joinCourseBtn = document.getElementById('joinCourseBtn');
const saveJoinBtn = document.getElementById('saveJoinBtn');
const closeJoinModalBtn = joinCourseModal.querySelector('.close-modal');
const myCoursesTable = document.getElementById('myCoursesTable');

joinCourseBtn.addEventListener('click', () => joinCourseModal.style.display = 'block');
closeJoinModalBtn.addEventListener('click', () => joinCourseModal.style.display = 'none');

// SAVE JOIN REQUEST 
saveJoinBtn.addEventListener('click', async () => {
    const course_code = joinCourseModal.querySelector('input[name="course_code"]').value.trim();
    const role = joinCourseModal.querySelector('select[name="join_type"]').value;
    const reason = joinCourseModal.querySelector('textarea[name="request_reason"]') 
                   ? joinCourseModal.querySelector('textarea[name="request_reason"]').value.trim() 
                   : '';

    if (!course_code || !role || !reason) {
        return Swal.fire({ title: 'Error', text: 'Please fill in all fields', icon: 'error' });
    }

    try {
        // Get course_id from course_code
        const courseRes = await fetch(`../php/get_course_id.php?course_code=${encodeURIComponent(course_code)}`);
        const courseData = await courseRes.json();

        if (!courseData.status || !courseData.course_id) {
            return Swal.fire({ title: 'Error', text: 'Invalid course code', icon: 'error' });
        }

        const course_id = courseData.course_id;

        // Assume you have a way to get current student_id from session
        const student_id = window.currentStudentId; 

        // Send join request to backend
        const response = await fetch('../php/student_request_course.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ course_id, role, request_reason: reason })
        });

        const result = await response.json();

        if (result.status === 'success') {
            Swal.fire({ title: 'Request Sent!', icon: 'success' }).then(() => {
                joinCourseModal.style.display = 'none';
                loadStudentCourses(); 
            });
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
        const courses = await response.json();

        myCoursesTable.innerHTML = '';

        courses.forEach(course => {
            myCoursesTable.innerHTML += `
                <tr>
                    <td>${course.course_code}</td>
                    <td>${course.course_name}</td>
                    <td>${course.instructor_name}</td>
                    <td>${course.status.charAt(0).toUpperCase() + course.status.slice(1)}</td>
                    <td>
                        <button class="view-btn">View</button>
                        <button class="edit-btn">${course.role.charAt(0).toUpperCase() + course.role.slice(1)}</button>
                    </td>
                </tr>
            `;
        });

        attachCourseRowEvents(); 

    } catch (err) {
        console.error(err);
    }
}

// Initial load
loadStudentCourses();
