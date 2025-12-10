const attendanceSessionSelect = document.getElementById('attendanceSessionSelect');

// Load sessions for this faculty
async function loadFacultySessions() {
    try {
        const res = await fetch('../php/faculty_sessions.php');
        const data = await res.json();
        if (data.status !== 'success') return;

        data.sessions.forEach(s => {
            const option = document.createElement('option');
            option.value = s.session_id;
            option.textContent = `${s.course_name} - ${s.topic} (${s.date})`;
            attendanceSessionSelect.appendChild(option);
        });
    } catch (err) {
        console.error('Failed to load sessions:', err);
    }
}

loadFacultySessions();
