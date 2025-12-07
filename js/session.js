const sessionModal = document.getElementById('sessionModal');
const openSessionBtn = document.getElementById('openSessionModal');
const closeSessionBtn = sessionModal.querySelector('.close-modal');
const saveSessionBtn = sessionModal.querySelector('.primary-btn');
const sessionTableBody = document.getElementById('sessionTableBody');

// ----------------- OPEN & CLOSE MODAL -----------------
openSessionBtn.addEventListener('click', () => sessionModal.style.display = 'block');
closeSessionBtn.addEventListener('click', () => sessionModal.style.display = 'none');

// ----------------- LOAD SESSIONS -----------------
async function loadSessions() {
    const response = await fetch('../php/get_sessions.php');
    const sessions = await response.json();

    sessionTableBody.innerHTML = '';

    sessions.forEach(session => {
        sessionTableBody.innerHTML += `
            <tr data-session-id="${session.session_id}">
                <td>${session.course_code}</td>
                <td>${session.topic}</td>
                <td>${session.location}</td>
                <td>${session.start_time}</td>
                <td>${session.end_time}</td>
                <td>${session.date}</td>
                <td>
                    <button class="view-btn">View</button>
                    <button class="edit-btn">Edit</button>
                    <button class="delete-btn">Delete</button>
                </td>
            </tr>
        `;
    });

    attachRowEvents();
}

// ----------------- SAVE NEW SESSION -----------------
saveSessionBtn.addEventListener('click', async () => {
    const course_code = sessionModal.querySelector('input[name="course_code"]').value.trim();
    const topic = sessionModal.querySelector('input[name="topic"]').value.trim();
    const location = sessionModal.querySelector('input[name="location"]').value.trim();
    const start_time = sessionModal.querySelector('input[name="start_time"]').value.trim();
    const end_time = sessionModal.querySelector('input[name="end_time"]').value.trim();
    const date = sessionModal.querySelector('input[name="session_date"]').value.trim();

    if (!course_code || !topic || !location || !start_time || !end_time || !date) {
        return Swal.fire({ title: 'Error', text: 'Please fill in all required fields', icon: 'error' });
    }

    // Fetch course_id from course_code
    const courseRes = await fetch(`../php/get_course_id.php?course_code=${encodeURIComponent(course_code)}`);
    const courseData = await courseRes.json();

    if (!courseData.status || !courseData.course_id) {
        return Swal.fire({ title: 'Error', text: 'Invalid course code', icon: 'error' });
    }

    const course_id = courseData.course_id;

    // Save session
    const response = await fetch('../php/add_session.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ course_id, topic, location, start_time, end_time, date })
    });

    const result = await response.json();

    if (result.status === 'success') {
        Swal.fire({ title: 'Added!', icon: 'success' }).then(() => {
            sessionModal.style.display = 'none';
            loadSessions(); // Refresh table dynamically
        });
    } else {
        Swal.fire({ title: 'Error', text: result.msg || 'Failed to add session', icon: 'error' });
    }
});

// ----------------- ATTACH VIEW, EDIT & DELETE EVENTS -----------------
function attachRowEvents() {
    document.querySelectorAll('#sessionTableBody .view-btn').forEach(btn => {
        btn.addEventListener('click', async e => {
            const row = e.target.closest('tr');
            const session_id = row.dataset.sessionId;

            const response = await fetch(`../php/view_session.php?session_id=${session_id}`);
            const session = await response.json();

            if (!session.status) {
                return Swal.fire({ title: 'Error', text: session.msg || 'Cannot fetch session', icon: 'error' });
            }

            const s = session.data;

            Swal.fire({
                title: `Session Details`,
                html: `
                    <p><strong>Course Code:</strong> ${s.course_code}</p>
                    <p><strong>Topic:</strong> ${s.topic}</p>
                    <p><strong>Location:</strong> ${s.location}</p>
                    <p><strong>Start Time:</strong> ${s.start_time}</p>
                    <p><strong>End Time:</strong> ${s.end_time}</p>
                    <p><strong>Date:</strong> ${s.date}</p>
                `
            });
        });
    });

    document.querySelectorAll('#sessionTableBody .edit-btn').forEach(btn => {
        btn.addEventListener('click', async e => {
            const row = e.target.closest('tr');
            const session_id = row.dataset.sessionId;

            const { value: formValues } = await Swal.fire({
                title: 'Edit Session',
                html: `
                    <input id="swal_course_code" class="swal2-input" placeholder="Course Code" value="${row.cells[0].innerText}">
                    <input id="swal_topic" class="swal2-input" placeholder="Topic" value="${row.cells[1].innerText}">
                    <input id="swal_location" class="swal2-input" placeholder="Location" value="${row.cells[2].innerText}">
                    <input id="swal_start_time" class="swal2-input" placeholder="Start Time" value="${row.cells[3].innerText}">
                    <input id="swal_end_time" class="swal2-input" placeholder="End Time" value="${row.cells[4].innerText}">
                    <input id="swal_date" class="swal2-input" type="date" placeholder="Date" value="${row.cells[5].innerText}">
                `,
                showCancelButton: true,
                confirmButtonText: 'Save',
                preConfirm: () => ({
                    course_code: document.getElementById('swal_course_code').value.trim(),
                    topic: document.getElementById('swal_topic').value.trim(),
                    location: document.getElementById('swal_location').value.trim(),
                    start_time: document.getElementById('swal_start_time').value.trim(),
                    end_time: document.getElementById('swal_end_time').value.trim(),
                    date: document.getElementById('swal_date').value.trim()
                })
            });

            if (!formValues) return;

            // Convert course_code to course_id
            const courseRes = await fetch(`../php/get_course_id.php?course_code=${encodeURIComponent(formValues.course_code)}`);
            const courseData = await courseRes.json();
            if (!courseData.status || !courseData.course_id) {
                return Swal.fire({ title: 'Error', text: 'Invalid course code', icon: 'error' });
            }
            const course_id = courseData.course_id;

            const response = await fetch('../php/update_session.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ session_id,course_id, ...formValues })
            });

            const result = await response.json();
            if (result.status === 'success') {
                Swal.fire({ title: 'Updated!', icon: 'success' }).then(loadSessions);
            } else {
                Swal.fire({ title: 'Error', text: result.msg || 'Failed to update session', icon: 'error' });
            }
        });
    });

    document.querySelectorAll('#sessionTableBody .delete-btn').forEach(btn => {
        btn.addEventListener('click', async e => {
            const row = e.target.closest('tr');
            const session_id = row.dataset.sessionId;

            const confirmDelete = await Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            });

            if (!confirmDelete.isConfirmed) return;

            const response = await fetch('../php/delete_session.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ session_id })
            });

            const result = await response.json();
            if (result.status === 'success') {
                Swal.fire({ title: 'Deleted!', icon: 'success' }).then(loadSessions);
            } else {
                Swal.fire({ title: 'Error', text: result.msg || 'Failed to delete session', icon: 'error' });
            }
        });
    });
}

// ----------------- INITIAL LOAD -----------------
loadSessions();
