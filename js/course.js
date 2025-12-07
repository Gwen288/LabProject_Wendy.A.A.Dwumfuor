const courseModal = document.getElementById('courseModal');
const openCourseBtn = document.getElementById('openCourseModal');
const closeCourseBtn = courseModal.querySelector('.close-modal');
const saveCourseBtn = courseModal.querySelector('#saveCourseBtn');
const courseTableBody = document.getElementById('courseTableBody');

// ----------------- OPEN & CLOSE MODAL -----------------
openCourseBtn.addEventListener('click', () => courseModal.style.display = 'block');
closeCourseBtn.addEventListener('click', () => courseModal.style.display = 'none');

// ----------------- LOAD COURSES -----------------
async function loadCourses() {
    const response = await fetch('../php/get_courses.php');
    const courses = await response.json();

    courseTableBody.innerHTML = '';

    courses.forEach(course => {
        courseTableBody.innerHTML += `
            <tr data-course-id="${course.course_code}">
                <td>${course.course_code}</td>
                <td>${course.course_name}</td>
                <td>${course.description}</td>
                <td>${course.credit_hours}</td>
                <td>${course.faculty_id}</td>
                <td>
                    <button class="edit-btn">Edit</button>
                    <button class="delete-btn">Delete</button>
                </td>
            </tr>
        `;
    });

    attachEditDeleteEvents();
}

// ----------------- ADD NEW COURSE -----------------
saveCourseBtn.addEventListener('click', async () => {
    const course_code = courseModal.querySelector('input[name="course_code"]').value.trim();
    const course_name = courseModal.querySelector('input[name="course_name"]').value.trim();
    const description = courseModal.querySelector('input[name="description"]').value.trim();
    const credit_hours = parseFloat(courseModal.querySelector('input[name="credit_hours"]').value.trim());
    const faculty_id = courseModal.querySelector('input[name="faculty_id"]').value.trim();

    if(!course_code || !course_name || isNaN(credit_hours) || !faculty_id){
        return Swal.fire({ title: 'Error', text: 'Please fill in all required fields', icon: 'error' });
    }

    const response = await fetch('../php/add_course.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ course_code, course_name, description, credit_hours, faculty_id })
    });

    const result = await response.json();

    if(result.status === 'success'){
        Swal.fire({ title: 'Added!', icon: 'success' }).then(() => {
            courseModal.style.display = 'none';
            loadCourses(); // refresh table
        });
    } else {
        Swal.fire({ title: 'Error', text: result.msg || 'Failed to add course', icon: 'error' });
    }
});

// ----------------- ATTACH EDIT & DELETE EVENTS -----------------
function attachEditDeleteEvents() {
    document.querySelectorAll('#courseTableBody .edit-btn').forEach(btn => {
        btn.addEventListener('click', async e => {
            const row = e.target.closest('tr');
            const course_code = row.cells[0].innerText;
            const course_name = row.cells[1].innerText;
            const description = row.cells[2].innerText;
            const credit_hours = row.cells[3].innerText;
            const faculty_id = row.cells[4].innerText;

            const { value: formValues } = await Swal.fire({
                title: 'Edit Course',
                html:
                    `<input id="swal_course_code" class="swal2-input" placeholder="Course Code" value="${course_code}">
                     <input id="swal_course_name" class="swal2-input" placeholder="Course Name" value="${course_name}">
                     <input id="swal_description" class="swal2-input" placeholder="Description" value="${description}">
                     <input id="swal_credit_hours" class="swal2-input" placeholder="Credit Hours" value="${credit_hours}">
                     <input id="swal_faculty_id" class="swal2-input" placeholder="Faculty ID" value="${faculty_id}">`,
                showCancelButton: true,
                confirmButtonText: 'Save',
                preConfirm: () => ({
                    course_code: document.getElementById('swal_course_code').value.trim(),
                    course_name: document.getElementById('swal_course_name').value.trim(),
                    description: document.getElementById('swal_description').value.trim(),
                    credit_hours: document.getElementById('swal_credit_hours').value.trim(),
                    faculty_id: document.getElementById('swal_faculty_id').value.trim()
                })
            });

            if(!formValues) return;

            const response = await fetch('../php/update_course.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ original_course_code: course_code, ...formValues })
            });

            const result = await response.json();
            if(result.status === 'success') {
                Swal.fire({ title: 'Updated!', icon: 'success' }).then(loadCourses);
            } else {
                Swal.fire({ title: 'Error', text: result.msg || 'Failed to update course', icon: 'error' });
            }
        });
    });

    document.querySelectorAll('#courseTableBody .delete-btn').forEach(btn => {
        btn.addEventListener('click', async e => {
            const row = e.target.closest('tr');
            const course_code = row.cells[0].innerText;

            const confirmDelete = await Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            });

            if(!confirmDelete.isConfirmed) return;

            const response = await fetch('../php/delete_course.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ course_code })
            });

            const result = await response.json();
            if(result.status === 'success') {
                Swal.fire({ title: 'Deleted!', icon: 'success' }).then(loadCourses);
            } else {
                Swal.fire({ title: 'Error', text: result.msg || 'Failed to delete course', icon: 'error' });
            }
        });
    });
}

// ----------------- INITIAL LOAD -----------------
loadCourses();
