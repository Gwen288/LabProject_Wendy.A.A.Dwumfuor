const requestsTable = document.querySelector('#JoinRequests tbody');

async function loadJoinRequests() {
    const res = await fetch('../php/faculty_join_requests.php');
    const data = await res.json();

    requestsTable.innerHTML = '';
    if (data.status !== 'success') return;

    data.requests.forEach(req => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${req.student_name}</td>
            <td>${req.course_name}</td>
            <td>${req.request_reason}</td>
            <td>
                <button class="approve-btn">Approve</button>
                <button class="reject-btn">Reject</button>
            </td>
        `;

        // Approve
        tr.querySelector('.approve-btn').addEventListener('click', () => updateRequest(req.student_id, req.course_id, 'enrolled'));

        // Reject
        tr.querySelector('.reject-btn').addEventListener('click', () => updateRequest(req.student_id, req.course_id, 'rejected'));

        requestsTable.appendChild(tr);
    });
}

async function updateRequest(student_id, course_id, status) {
    const res = await fetch('../php/update_join_request.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ student_id, course_id, status })
    });
    const result = await res.json();

    if (result.status === 'success') {
        alert(`Request ${status === 'enrolled' ? 'approved' : 'rejected'}!`);
        loadJoinRequests(); // refresh table
    } else {
        alert('Failed: ' + result.msg);
    }
}

// Initial load
loadJoinRequests();
