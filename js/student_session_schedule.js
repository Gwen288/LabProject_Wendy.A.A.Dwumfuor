const sessionTableBody = document.getElementById('sessionTableBody');

async function loadStudentSessions() {
    try {
        const res = await fetch('../php/get_student_sessions.php');
        const data = await res.json();

        sessionTableBody.innerHTML = '';

        if (data.status !== 'success' || data.sessions.length === 0) {
            sessionTableBody.innerHTML = `<tr><td colspan="6">No sessions found.</td></tr>`;
            return;
        }

        data.sessions.forEach(s => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${s.course_code}</td>
                <td>${s.course_name}</td>
                <td>${s.topic}</td>
                <td>${s.location}</td>
                <td>${s.session_date}</td>
                <td>${s.start_time} - ${s.end_time}</td>
            `;
            sessionTableBody.appendChild(tr);
        });

    } catch(err) {
        console.error(err);
        sessionTableBody.innerHTML = `<tr><td colspan="6">Failed to load sessions.</td></tr>`;
    }
}

// Load sessions on page load
loadStudentSessions();
