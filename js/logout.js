document.getElementById("logoutButton").addEventListener("click",
    async function logout() {
    try {
        let response = await fetch("../php/logout.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" }
        });

        let result = await response.json();

        if (result.logout) {
            Swal.fire({
                title: "Logged Out",
                text: "You have been successfully logged out.",
                icon: "success",
                timer: 1500,
                showConfirmButton: false
            });

            setTimeout(() => {
                window.location.href = "../html/login.html";
            }, 1500);
        } else {
            Swal.fire({
                title: "Error",
                text: "Logout failed.",
                icon: "error"
            });
        }
    } catch (error) {
        Swal.fire({
            title: "Error",
            text: "Server error occurred.",
            icon: "error"
        });
    }
});
