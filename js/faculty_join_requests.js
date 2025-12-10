const requestsTable = document.querySelector('#JoinRequests tbody');

async function loadJoinRequests() {
    try {
        const res = await fetch('../php/faculty_join_requests.php');
        const data = await res.json();

        requestsTable.innerHTML = '';

        if (data.status !== 'success') {
            requestsTable.innerHTML = `<tr><td colspan="4">Failed to load requests: ${data.msg || 'Unknown error'}</td></tr>`;
            return;
        }

        if (data.requests.length === 0) {
            requestsTable.innerHTML = `<tr><td colspan="4">No pending requests.</td></tr>`;
            return;
        }

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
            tr.querySelector('.approve-btn').addEventListener('click', async () => {
                await updateRequest(req.student_id, req.course_id, 'enrolled');
            });

            // Reject
            tr.querySelector('.reject-btn').addEventListener('click', async () => {
                await updateRequest(req.student_id, req.course_id, 'rejected');
            });

            requestsTable.appendChild(tr);
        });
    } catch (err) {
        console.error('Failed to load join requests:', err);
        requestsTable.innerHTML = `<tr><td colspan="4">Failed to load requests.</td></tr>`;
    }
}

async function updateRequest(student_id, course_id, status) {
    try {
        const res = await fetch('../php/update_join_request.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ student_id, course_id, status })
        });

        const result = await res.json();

        if (result.status === 'success') {
            Swal.fire({
                title: 'Success',
                text: `Request ${status === 'enrolled' ? 'approved' : 'rejected'}!`,
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
            loadJoinRequests(); // refresh table
        } else {
            Swal.fire({ title: 'Error', text: result.msg || 'Failed to update request', icon: 'error' });
        }
    } catch (err) {
        console.error('Update failed:', err);
        Swal.fire({ title: 'Error', text: 'Something went wrong', icon: 'error' });
    }
}

// Initial load
loadJoinRequests();
