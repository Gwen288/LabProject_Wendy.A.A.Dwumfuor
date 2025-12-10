const attendanceSessionSelect = document.getElementById('attendanceSessionSelect');

async function loadFacultySessions() {
    try {
        const res = await fetch('../php/get_faculty_sessions.php');
        const data = await res.json();

        attendanceSessionSelect.innerHTML = '<option value="">-- Select Session --</option>';

        if (data.status !== 'success' || data.sessions.length === 0) return;

        data.sessions.forEach(s => {
            const option = document.createElement('option');
            option.value = s.session_id;
            option.textContent = `${s.course_code} - ${s.topic} (${s.session_date})`;
            attendanceSessionSelect.appendChild(option);
        });

    } catch(err) {
        console.error('Failed to load sessions:', err);
    }
}

// call this on page load
loadFacultySessions();
